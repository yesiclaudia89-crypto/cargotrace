<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShipmentController extends BaseApiController
{
    /**
     * List all shipments with filters & role scoping (Slide 5, 12, 13).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Shipment::query()
            ->with(['carrier', 'origin', 'destination', 'customer'])
            ->latest();

        // Role restriction: Customer only sees their own (Slide 5 & 13)
        if ($user->isCustomer()) {
            $query->where('user_id', $user->id);
        }

        // Search by shipment number
        if ($request->filled('search')) {
            $query->where('shipment_number', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by carrier
        if ($request->filled('carrier_id')) {
            $query->where('carrier_id', $request->carrier_id);
        }

        $shipments = $query->paginate(15);

        return $this->sendResponse([
            'items' => $shipments->items(),
            'pagination' => [
                'current_page' => $shipments->currentPage(),
                'last_page'    => $shipments->lastPage(),
                'per_page'     => $shipments->perPage(),
                'total'        => $shipments->total(),
            ],
        ], 'Daftar pengiriman kargo.');
    }

    /**
     * Create a new shipment booking (Slide 6 & 13 - Admin/Operator only).
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id'            => ['required', 'exists:users,id'],
            'carrier_id'         => ['required', 'exists:carriers,id'],
            'origin_id'          => ['required', 'exists:locations,id', 'different:destination_id'],
            'destination_id'     => ['required', 'exists:locations,id'],
            'cargo_type'         => ['required', 'string', 'max:255'],
            'total_weight'       => ['required', 'numeric', 'min:1'],
            'freight_cost'       => ['required', 'numeric', 'min:0'],
            'expected_departure' => ['required', 'date'],
            'expected_arrival'   => ['required', 'date', 'after:expected_departure'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $validated = $validator->validated();

        $shipmentNumber = 'CT-' . date('Ym') . '-' . str_pad((string) (Shipment::count() + 1), 3, '0', STR_PAD_LEFT);

        $shipment = Shipment::create([
            ...$validated,
            'shipment_number'   => $shipmentNumber,
            'estimated_arrival' => $validated['expected_arrival'],
            'status'            => 'CREATED',
            'payment_status'    => 'UNPAID',
        ]);

        // Add initial tracking event
        $origin = Location::find($validated['origin_id']);
        TrackingEvent::create([
            'shipment_id'   => $shipment->id,
            'location_id'   => $origin->id,
            'created_by'    => $request->user()->id,
            'event_type'    => 'BOOKING_CREATED',
            'status'        => 'CREATED',
            'location_name' => $origin->name,
            'latitude'      => $origin->latitude,
            'longitude'     => $origin->longitude,
            'description'   => 'Shipment booking created in the system via API. Awaiting payment.',
            'event_time'    => now(),
        ]);

        $shipment->load(['carrier', 'origin', 'destination', 'customer']);

        return $this->sendResponse($shipment, 'Shipment booking berhasil dibuat.', 201);
    }

    /**
     * Get shipment details by ID or shipment number (Slide 13).
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $shipment = Shipment::where('id', $id)
            ->orWhere('shipment_number', $id)
            ->first();

        if (! $shipment) {
            return $this->sendError('Data pengiriman kargo tidak ditemukan.', [], 404);
        }

        // Customer authorization: cannot access other customer's cargo (Slide 5 & 13)
        if ($request->user()->isCustomer() && $shipment->user_id !== $request->user()->id) {
            return $this->sendError('Akses Ditolak: Anda hanya berhak melihat data kargo milik Anda sendiri.', [], 403);
        }

        $shipment->load([
            'carrier',
            'origin',
            'destination',
            'customer',
            'items',
            'trackingEvents' => fn ($q) => $q->orderBy('event_time', 'asc'),
            'exceptions'     => fn ($q) => $q->latest('logged_at'),
        ]);

        // External API Weather at current vessel coordinate
        $latestCoordEvent = $shipment->trackingEvents->filter(fn ($e) => ! empty($e->latitude) && ! empty($e->longitude))->last();
        $targetLat = (float) ($latestCoordEvent?->latitude ?? $shipment->origin->latitude);
        $targetLng = (float) ($latestCoordEvent?->longitude ?? $shipment->origin->longitude);

        $weather = app(WeatherService::class)->getMaritimeWeather($targetLat, $targetLng);

        $data = $shipment->toArray();
        $data['delay_hours']  = $shipment->delay_hours;
        $data['delay_status'] = $shipment->delay_status;
        $data['live_weather'] = $weather;

        return $this->sendResponse($data, 'Detail pengiriman kargo.');
    }

    /**
     * Update shipment details (Admin/Operator only).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (! $shipment) {
            return $this->sendError('Data kargo tidak ditemukan.', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'cargo_type'   => ['sometimes', 'string', 'max:255'],
            'total_weight' => ['sometimes', 'numeric', 'min:1'],
            'freight_cost' => ['sometimes', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $shipment->update($validator->validated());

        return $this->sendResponse($shipment->fresh(['carrier', 'origin', 'destination']), 'Data pengiriman berhasil diperbarui.');
    }
}
