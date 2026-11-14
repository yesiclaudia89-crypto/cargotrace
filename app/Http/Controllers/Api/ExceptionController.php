<?php

namespace App\Http\Controllers\Api;

use App\Models\Shipment;
use App\Models\ShipmentException;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExceptionController extends BaseApiController
{
    /**
     * Get shipment exceptions/disruptions log (Slide 13).
     */
    public function index(Request $request, string $id): JsonResponse
    {
        $shipment = Shipment::where('id', $id)
            ->orWhere('shipment_number', $id)
            ->first();

        if (! $shipment) {
            return $this->sendError('Data kargo tidak ditemukan.', [], 404);
        }

        if ($request->user()->isCustomer() && $shipment->user_id !== $request->user()->id) {
            return $this->sendError('Akses Ditolak: Anda hanya berhak melihat data kargo milik Anda sendiri.', [], 403);
        }

        $exceptions = $shipment->exceptions()
            ->with(['logger', 'location'])
            ->latest('logged_at')
            ->get();

        return $this->sendResponse([
            'shipment_number' => $shipment->shipment_number,
            'exceptions'      => $exceptions,
        ], 'Daftar kendala operasional (exceptions) kargo.');
    }

    /**
     * Log an operational exception & auto-adjust ETA (Slide 8, 9, 13 - Admin/Operator only).
     */
    public function store(Request $request, string $id): JsonResponse
    {
        $shipment = Shipment::where('id', $id)
            ->orWhere('shipment_number', $id)
            ->first();

        if (! $shipment) {
            return $this->sendError('Data kargo tidak ditemukan.', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'exception_type'     => ['required', 'string'],
            'description'        => ['required', 'string'],
            'delay_impact_hours' => ['required', 'integer', 'min:0'],
            'location_id'        => ['nullable', 'exists:locations,id'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $validated = $validator->validated();

        $exception = ShipmentException::create([
            'shipment_id'        => $shipment->id,
            'location_id'        => $validated['location_id'] ?? null,
            'logged_by'          => $request->user()->id,
            'exception_type'     => $validated['exception_type'],
            'description'        => $validated['description'],
            'delay_impact_hours' => $validated['delay_impact_hours'],
            'logged_at'          => now(),
            'is_resolved'        => false,
        ]);

        // Auto-adjust ETA based on delay impact
        if ($validated['delay_impact_hours'] > 0) {
            $baseTime = $shipment->estimated_arrival ?? $shipment->expected_arrival;
            $shipment->estimated_arrival = Carbon::parse($baseTime)->addHours((int) $validated['delay_impact_hours']);
            $shipment->save();
        }

        return $this->sendResponse([
            'exception'       => $exception,
            'new_eta'         => $shipment->estimated_arrival,
            'new_delay_hours' => $shipment->delay_hours,
            'new_delay_status'=> $shipment->delay_status,
        ], 'Kendala operasional berhasil dicatat. Estimasi tiba (ETA) telah disesuaikan.', 201);
    }
}
