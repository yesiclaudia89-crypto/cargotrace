<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\ShipmentException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // 1. Ambil data kargo (Jika role Customer, hanya tampilkan miliknya - Slide 5)
        $shipmentsQuery = Shipment::query()
            ->with(['carrier', 'origin', 'destination', 'customer', 'trackingEvents', 'exceptions']);

        if ($user->isCustomer()) {
            $shipmentsQuery->where('user_id', $user->id);
        }

        // 2. Hitung statistik nyata dari database (Slide 10)
        $totalShipments = (clone $shipmentsQuery)->count();
        $inTransitCount = (clone $shipmentsQuery)->where('status', 'IN_TRANSIT')->count();
        $deliveredCount = (clone $shipmentsQuery)->where('status', 'DELIVERED')->count();
        $delayedCount = (clone $shipmentsQuery)->where('is_delayed', true)->count();

        // 3. Hitung gangguan aktif (open exceptions)
        $exceptionsQuery = ShipmentException::query();
        if ($user->isCustomer()) {
            $exceptionsQuery->whereHas('shipment', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        $openExceptionsCount = $exceptionsQuery->where('status', '!=', 'RESOLVED')->count();

        // 4. Ambil semua kargo untuk peta dan tabel ringkasan
        $recentShipments = (clone $shipmentsQuery)->latest()->take(10)->get();

        return view('dashboard', compact(
            'totalShipments',
            'inTransitCount',
            'deliveredCount',
            'delayedCount',
            'openExceptionsCount',
            'recentShipments'
        ));
    }
}
