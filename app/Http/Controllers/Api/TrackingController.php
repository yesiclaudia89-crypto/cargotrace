<?php

namespace App\Http\Controllers\Api;

use App\Models\Shipment;
use App\Models\TrackingEvent;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackingController extends BaseApiController
{
    /**
     * Get shipment tracking timeline events (Slide 13).
     */
    public function index(Request $request, string $id): JsonResponse
    {
        $shipment = Shipment::where('id', $id)
            ->orWhere('shipment_number', $id)
            ->first();

        if (! $shipment) {
            return $this->sendError('Data kargo tidak ditemukan.', [], 404);
        }

        // Customer authorization
        if ($request->user()->isCustomer() && $shipment->user_id !== $request->user()->id) {
            return $this->sendError('Akses Ditolak: Anda hanya berhak melihat data kargo milik Anda sendiri.', [], 403);
        }

        $events = $shipment->trackingEvents()
            ->with('location')
            ->orderBy('event_time', 'asc')
            ->get();

        return $this->sendResponse([
            'shipment_number' => $shipment->shipment_number,
            'current_status'  => $shipment->status,
            'events'          => $events,
        ], 'Riwayat timeline event pelacakan kargo.');
    }

    /**
     * Add new tracking event & update GPS coordinates / status (Slide 6 & 13 - Admin/Operator only).
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
            'status'        => ['required', 'string'],
            'event_type'    => ['required', 'string'],
            'location_name' => ['required', 'string', 'max:255'],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
            'description'   => ['required', 'string'],
            'event_time'    => ['required', 'date'],
            'location_id'   => ['nullable', 'exists:locations,id'],
            'new_eta'       => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $validated = $validator->validated();

        // State Machine validation (Slide 6)
        if ($validated['status'] !== $shipment->status && ! $shipment->canTransitionTo($validated['status'])) {
            return $this->sendError(
                "Transisi status invalid! Status saat ini '{$shipment->status}' tidak dapat langsung diubah ke '{$validated['status']}'.",
                ['allowed_transitions' => $shipment->status === 'CREATED' ? ['BOOKED', 'CANCELLED'] : []],
                422
            );
        }

        $event = TrackingEvent::create([
            'shipment_id'   => $shipment->id,
            'location_id'   => $validated['location_id'] ?? null,
            'created_by'    => $request->user()->id,
            'event_type'    => $validated['event_type'],
            'status'        => $validated['status'],
            'location_name' => $validated['location_name'],
            'latitude'      => $validated['latitude'] ?? null,
            'longitude'     => $validated['longitude'] ?? null,
            'description'   => $validated['description'],
            'event_time'    => $validated['event_time'],
        ]);

        // Update shipment status
        $shipment->status = $validated['status'];

        if (! empty($validated['new_eta'])) {
            $shipment->estimated_arrival = Carbon::parse($validated['new_eta']);
        }

        if ($validated['status'] === 'DELIVERED') {
            $shipment->actual_arrival = Carbon::parse($validated['event_time']);
        }

        $shipment->save();

        return $this->sendResponse([
            'event'           => $event,
            'updated_status'  => $shipment->status,
            'estimated_eta'   => $shipment->estimated_arrival,
            'delay_hours'     => $shipment->delay_hours,
            'delay_status'    => $shipment->delay_status,
        ], 'Event pelacakan berhasil ditambahkan.', 201);
    }
}
