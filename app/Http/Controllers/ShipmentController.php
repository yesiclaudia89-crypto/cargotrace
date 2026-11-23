<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\ShipmentException;
use App\Models\TrackingEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function index(Request $request): View
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

        $shipments = $query->paginate(10)->withQueryString();
        $carriers = Carrier::where('is_active', true)->get();

        return view('shipments.index', compact('shipments', 'carriers'));
    }

    public function create(): View
    {
        $carriers = Carrier::where('is_active', true)->get();
        $locations = Location::all();
        $customers = User::where('role', 'customer')->get();

        return view('shipments.create', compact('carriers', 'locations', 'customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'carrier_id' => ['required', 'exists:carriers,id'],
            'origin_id' => ['required', 'exists:locations,id', 'different:destination_id'],
            'destination_id' => ['required', 'exists:locations,id'],
            'cargo_type' => ['required', 'string', 'max:255'],
            'total_weight' => ['required', 'numeric', 'min:1'],
            'freight_cost' => ['required', 'numeric', 'min:0'],
            'expected_departure' => ['required', 'date'],
            'expected_arrival' => ['required', 'date', 'after:expected_departure'],
        ]);

        $shipmentNumber = 'CT-' . date('Ym') . '-' . str_pad((string) (Shipment::count() + 1), 3, '0', STR_PAD_LEFT);

        $shipment = Shipment::create([
            ...$validated,
            'shipment_number' => $shipmentNumber,
            'estimated_arrival' => $validated['expected_arrival'],
            'status' => 'CREATED',
            'payment_status' => 'UNPAID',
        ]);

        // Add initial tracking event
        $origin = Location::find($validated['origin_id']);
        TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'location_id' => $origin->id,
            'created_by' => $request->user()->id,
            'event_type' => 'BOOKING_CREATED',
            'status' => 'CREATED',
            'location_name' => $origin->name,
            'latitude' => $origin->latitude,
            'longitude' => $origin->longitude,
            'description' => 'Shipment booking created in the system. Awaiting payment.',
            'event_time' => now(),
        ]);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Shipment berhasil dibuat!');
    }

    public function show(Shipment $shipment, Request $request): View
    {
        $user = $request->user();

        // Customer authorization: cannot view other customer's shipment (Slide 5 & 13)
        if ($user->isCustomer() && $shipment->user_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda hanya boleh melihat kargo milik Anda sendiri.');
        }

        $shipment->load([
            'carrier',
            'origin',
            'destination',
            'customer',
            'items',
            'trackingEvents' => fn ($q) => $q->orderBy('event_time', 'asc'),
            'exceptions' => fn ($q) => $q->latest('logged_at'),
        ]);

        $locations = Location::all();

        // Fetch External API live maritime weather for latest cargo coordinates (Slide 9 & 15)
        $latestCoordEvent = $shipment->trackingEvents->filter(fn ($e) => ! empty($e->latitude) && ! empty($e->longitude))->last();
        $targetLat = (float) ($latestCoordEvent?->latitude ?? $shipment->origin->latitude);
        $targetLng = (float) ($latestCoordEvent?->longitude ?? $shipment->origin->longitude);

        $weatherService = app(\App\Services\WeatherService::class);
        $weather = $weatherService->getMaritimeWeather($targetLat, $targetLng);

        return view('shipments.show', compact('shipment', 'locations', 'weather'));
    }

    /**
     * Public Tracking View - Slide 10 Requirement (No auth required)
     */
    public function publicTrack(Request $request, ?string $tracking_number = null): View
    {
        $rawQuery = $tracking_number 
            ?? $request->query('tracking_number') 
            ?? $request->query('number') 
            ?? $request->query('search') 
            ?? 'CT-2026-001';

        $query = trim((string) $rawQuery);

        $shipment = Shipment::where('shipment_number', $query)->first();

        if (! $shipment) {
            $shipment = Shipment::where('shipment_number', 'like', "%{$query}%")->first() 
                ?? Shipment::first();
        }

        if (! $shipment) {
            abort(404, 'Data kargo tidak ditemukan di sistem.');
        }

        $shipment->load([
            'carrier',
            'origin',
            'destination',
            'customer',
            'items',
            'trackingEvents' => fn ($q) => $q->orderBy('event_time', 'asc'),
            'exceptions' => fn ($q) => $q->latest('logged_at'),
        ]);

        $latestCoordEvent = $shipment->trackingEvents->filter(fn ($e) => ! empty($e->latitude) && ! empty($e->longitude))->last();
        $targetLat = (float) ($latestCoordEvent?->latitude ?? $shipment->origin?->latitude ?? -6.1044);
        $targetLng = (float) ($latestCoordEvent?->longitude ?? $shipment->origin?->longitude ?? 106.8833);

        $weatherService = app(\App\Services\WeatherService::class);
        $weather = $weatherService->getMaritimeWeather($targetLat, $targetLng);

        return view('shipments.public-track', compact('shipment', 'weather'));
    }

    /**
     * Print Official Maritime Waybill / Bill of Lading (Auth User)
     */
    public function printReceipt(Shipment $shipment): View
    {
        $user = auth()->user();
        if ($user && $user->isCustomer() && $shipment->user_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda hanya diizinkan mencetak resi kargo milik perusahaan Anda sendiri.');
        }

        $shipment->load([
            'carrier',
            'origin',
            'destination',
            'customer',
            'items',
            'trackingEvents' => fn ($q) => $q->orderBy('event_time', 'asc'),
        ]);

        return view('shipments.print', compact('shipment'));
    }

    /**
     * Print Public Maritime Waybill / Bill of Lading (No Auth Required)
     */
    public function publicPrintReceipt(Request $request, ?string $tracking_number = null): View
    {
        $rawQuery = $tracking_number 
            ?? $request->query('tracking_number') 
            ?? $request->query('number') 
            ?? $request->query('search') 
            ?? 'CT-2026-001';

        $query = trim((string) $rawQuery);

        $shipment = Shipment::where('shipment_number', $query)->first();

        if (! $shipment) {
            $shipment = Shipment::where('shipment_number', 'like', "%{$query}%")->first() 
                ?? Shipment::first();
        }

        if (! $shipment) {
            abort(404, 'Data kargo tidak ditemukan di sistem.');
        }

        $shipment->load([
            'carrier',
            'origin',
            'destination',
            'customer',
            'items',
            'trackingEvents' => fn ($q) => $q->orderBy('event_time', 'asc'),
        ]);

        return view('shipments.print', compact('shipment'));
    }

    public function storeTracking(Request $request, Shipment $shipment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string'],
            'event_type' => ['required', 'string'],
            'location_name' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['required', 'string'],
            'event_time' => ['required', 'date'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'new_eta' => ['nullable', 'date'],
        ]);

        // State machine rule validation (Slide 6)
        if ($validated['status'] !== $shipment->status && ! $shipment->canTransitionTo($validated['status'])) {
            return back()->withErrors([
                'status' => "Transisi status invalid! Status '{$shipment->status}' tidak dapat langsung diubah ke '{$validated['status']}'.",
            ]);
        }

        TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'location_id' => $validated['location_id'] ?? null,
            'created_by' => $request->user()->id,
            'event_type' => $validated['event_type'],
            'status' => $validated['status'],
            'location_name' => $validated['location_name'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'description' => $validated['description'],
            'event_time' => $validated['event_time'],
        ]);

        // Update shipment status & calculate delay rules (Slide 8)
        $shipment->status = $validated['status'];

        if (! empty($validated['new_eta'])) {
            $newEta = Carbon::parse($validated['new_eta']);
            $shipment->estimated_arrival = $newEta;

            $diffHours = (int) $shipment->expected_arrival->diffInHours($newEta, false);
            if ($diffHours > 0) {
                $shipment->delay_hours = $diffHours;
                $shipment->is_delayed = true;
                $shipment->delay_severity = $diffHours <= 6 ? 'LOW' : ($diffHours <= 12 ? 'MEDIUM' : 'HIGH');
            } else {
                $shipment->delay_hours = 0;
                $shipment->is_delayed = false;
                $shipment->delay_severity = 'NONE';
            }
        }

        $shipment->save();

        return redirect()->route('shipments.show', $shipment)->with('success', 'Tracking event berhasil dicatat.');
    }

    public function storeException(Request $request, Shipment $shipment): RedirectResponse
    {
        $validated = $request->validate([
            'exception_type' => ['required', 'in:CUSTOMS_HOLD,WEATHER_DELAY,PORT_CONGESTION,DAMAGED,MISROUTED,DOCUMENTATION_ISSUE'],
            'severity' => ['required', 'in:LOW,MEDIUM,HIGH'],
            'description' => ['required', 'string'],
        ]);

        ShipmentException::create([
            'shipment_id' => $shipment->id,
            'logged_by' => $request->user()->id,
            'exception_type' => $validated['exception_type'],
            'severity' => $validated['severity'],
            'status' => 'INVESTIGATING',
            'description' => $validated['description'],
            'logged_at' => now(),
        ]);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Laporan kendala kargo (exception) berhasil dicatat.');
    }

    public function pay(Request $request, Shipment $shipment): RedirectResponse
    {
        // Payment gateway simulation / Midtrans callback simulation
        $shipment->update([
            'payment_status' => 'PAID',
            'status' => 'BOOKED',
            'payment_reference' => 'MIDTRANS-SIM-' . strtoupper(uniqid()),
        ]);

        TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'created_by' => $request->user()->id,
            'event_type' => 'PAYMENT_SETTLED',
            'status' => 'BOOKED',
            'location_name' => $shipment->origin->name,
            'latitude' => $shipment->origin->latitude,
            'longitude' => $shipment->origin->longitude,
            'description' => 'Pembayaran tagihan kargo sebesar Rp ' . number_format($shipment->freight_cost, 0, ',', '.') . ' berhasil diverifikasi via Payment Gateway. Status kargo resmi BOOKED.',
            'event_time' => now(),
        ]);

        return redirect()->route('shipments.show', $shipment)->with('success', 'Pembayaran berhasil! Kargo telah berstatus BOOKED.');
    }
}
