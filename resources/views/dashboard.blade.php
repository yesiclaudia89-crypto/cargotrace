<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <span>CargoTrace Visibility Dashboard</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                        3D Fleet Radar v2.4
                    </span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pemantauan armada maritim 3D global, radar AIS real-time, dan deteksi deviasi jalur pelayaran.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('shipments.public-track', 'CT-2026-001') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 rounded-lg text-xs font-bold transition">
                    <span>🌐 Halaman Pelacakan Publik</span>
                    <span>➔</span>
                </a>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                    {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-800 border border-purple-200' :
                       (Auth::user()->role === 'operator' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200') }}">
                    Role: {{ Auth::user()->role }}
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Leaflet.js Assets (Slide 9) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- 3D Map Custom Animations & Styles -->
    <style>
        .map-3d-wrap {
            perspective: 1200px;
            transition: all 0.5s ease;
        }
        .map-3d-wrap.mode-3d #dashboardFleetMap {
            transform: rotateX(24deg) scale(0.97);
            transform-style: preserve-3d;
            box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.45);
            border-radius: 1.5rem;
        }
        .map-3d-wrap.mode-2d #dashboardFleetMap {
            transform: rotateX(0deg) scale(1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        #dashboardFleetMap {
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.6s ease;
        }
        @keyframes radarSonar {
            0% { transform: scale(0.6); opacity: 1; }
            100% { transform: scale(2.6); opacity: 0; }
        }
        .vessel-sonar-ring {
            position: absolute;
            top: -12px;
            left: -12px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(245, 158, 11, 0.35);
            border: 2px solid #f59e0b;
            animation: radarSonar 2s cubic-bezier(0.2, 0.8, 0.2, 1) infinite;
            pointer-events: none;
        }
        @keyframes seaRouteAnim {
            to {
                stroke-dashoffset: -32;
            }
        }
        .animated-sea-route {
            animation: seaRouteAnim 1.4s linear infinite;
        }
    </style>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 5 METRIC STAT CARDS (Slide 10) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Total Shipments -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kargo</p>
                        <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalShipments }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-xl shadow-inner">
                        📦
                    </div>
                </div>

                <!-- In Transit -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Jalan</p>
                        <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $inTransitCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-bold text-xl shadow-inner">
                        🚢
                    </div>
                </div>

                <!-- Delivered -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Telah Tiba</p>
                        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $deliveredCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold text-xl shadow-inner">
                        ✅
                    </div>
                </div>

                <!-- Delayed -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Terlambat</p>
                        <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $delayedCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center font-bold text-xl shadow-inner">
                        ⚠️
                    </div>
                </div>

                <!-- Open Exceptions -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Gangguan Aktif</p>
                        <p class="text-2xl font-extrabold text-purple-600 mt-1">{{ $openExceptionsCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center font-bold text-xl shadow-inner">
                        🚨
                    </div>
                </div>

            </div>

            <!-- 3D MARITIME FLEET MAP & SMART SEARCH DECK -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/60 overflow-hidden">
                
                <!-- TOP HEADER & CONTROLS -->
                <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                                <h3 class="font-extrabold text-lg sm:text-xl tracking-tight text-white flex items-center gap-2">
                                    <span>🌐 Peta Pemantauan 3D Maritim & Radar Kargo</span>
                                </h3>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    Live AIS Active
                                </span>
                            </div>
                            <p class="text-xs text-slate-300 mt-1">
                                Visualisasi 3D citra satelit laut, lintasan kapal kargo kontainer antar-samudra, dan pelabuhan internasional.
                            </p>
                        </div>

                        <!-- 3D VIEW TOGGLE & MAP LAYER SWITCHER -->
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- 3D Perspective Toggle Button -->
                            <button type="button" id="toggle3DBtn" onclick="toggle3DPerspective()" class="px-3.5 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center gap-1.5 border border-blue-400/30">
                                <span id="icon3D">📐</span>
                                <span id="text3D">Mode 3D: Aktif (Tilt 24°)</span>
                            </button>

                            <!-- Map Basemap Switcher -->
                            <div class="inline-flex rounded-xl bg-slate-800/80 p-1 border border-slate-700/80 text-xs">
                                <button type="button" onclick="switchBaseLayer('satellite')" id="btnLayerSat" class="px-2.5 py-1 rounded-lg font-bold transition bg-blue-600 text-white shadow-sm">
                                    🛰️ 3D Satelit
                                </button>
                                <button type="button" onclick="switchBaseLayer('ocean')" id="btnLayerOcean" class="px-2.5 py-1 rounded-lg font-bold transition text-slate-300 hover:text-white">
                                    🌊 Nautical Sea
                                </button>
                                <button type="button" onclick="switchBaseLayer('osm')" id="btnLayerOsm" class="px-2.5 py-1 rounded-lg font-bold transition text-slate-300 hover:text-white">
                                    🏙️ Peta Jalan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- SMART LIVE SEARCH BAR (Mencari Kargo & Kapal Seketika) -->
                    <div class="mt-5 relative">
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="fleetSearchInput" 
                                oninput="handleFleetSearch(this.value)" 
                                placeholder="🔍 Cari kargo mudah: Ketik No. Resi (CT-...), Nama Kapal, Pelabuhan Asal/Tujuan, atau Jenis Barang..." 
                                class="w-full pl-11 pr-24 py-3 bg-slate-800/90 hover:bg-slate-800 focus:bg-slate-900 border border-slate-700 focus:border-blue-500 rounded-2xl text-white placeholder-slate-400 text-xs sm:text-sm font-medium shadow-inner transition focus:outline-none focus:ring-2 focus:ring-blue-500/40"
                            >
                            <button 
                                type="button" 
                                onclick="clearFleetSearch()" 
                                id="clearSearchBtn" 
                                class="hidden absolute right-2.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-700 hover:bg-slate-600 text-slate-200 transition"
                            >
                                ✕ Reset
                            </button>
                        </div>

                        <!-- LIVE SEARCH AUTO-SUGGEST DROPDOWN RESULTS -->
                        <div id="fleetSearchResults" class="hidden absolute left-0 right-0 top-full mt-2 bg-slate-900/98 backdrop-blur-xl border border-slate-700/80 rounded-2xl shadow-2xl z-50 overflow-hidden divide-y divide-slate-800 max-h-80 overflow-y-auto">
                            <!-- Populated dynamically via JavaScript -->
                        </div>
                    </div>

                    <!-- QUICK FILTER CHIPS -->
                    <div class="flex flex-wrap items-center gap-1.5 mt-3.5 text-xs">
                        <span class="text-slate-400 font-semibold mr-1">Fokus Cepat:</span>
                        <button type="button" onclick="resetFleetView()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold border border-slate-700 transition">
                            🔍 Seluruh Armada Dunia
                        </button>
                        <button type="button" onclick="quickFilterStatus('IN_TRANSIT')" class="px-2.5 py-1 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 font-bold border border-amber-500/30 transition flex items-center gap-1">
                            <span>🚢</span>
                            <span>Sedang Berlayar (In Transit)</span>
                        </button>
                        <button type="button" onclick="quickFilterStatus('DELIVERED')" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 font-bold border border-emerald-500/30 transition flex items-center gap-1">
                            <span>✅</span>
                            <span>Telah Tiba (Delivered)</span>
                        </button>
                        <button type="button" onclick="quickFilterStatus('CREATED')" class="px-2.5 py-1 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 font-bold border border-blue-500/30 transition flex items-center gap-1">
                            <span>⏳</span>
                            <span>Menunggu Booking</span>
                        </button>
                    </div>
                </div>

                <!-- 3D MAP WRAPPER & CONTAINER -->
                <div class="relative p-2 sm:p-4 bg-slate-950 overflow-hidden">
                    <div id="map3DWrap" class="map-3d-wrap mode-3d">
                        <div id="dashboardFleetMap" style="height: 480px; width: 100%;" class="z-0 bg-slate-900 rounded-2xl"></div>
                    </div>

                    <!-- 3D TACTICAL HUD OVERLAY (Real-time telemetry) -->
                    <div class="hidden sm:block absolute top-6 right-6 z-10 bg-slate-900/90 backdrop-blur-md px-4 py-3 rounded-2xl border border-slate-700/80 shadow-2xl text-[11px] text-slate-200 font-mono space-y-1.5 pointer-events-none">
                        <div class="flex items-center justify-between gap-4 border-b border-slate-700/80 pb-1 text-slate-400 font-sans font-bold text-xs">
                            <span class="flex items-center gap-1.5 text-blue-400">
                                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                                TELEMETRI RADAR 3D
                            </span>
                            <span id="hudVesselName" class="text-white">MV Samudera Perkasa</span>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-400">Koordinat:</span>
                            <span id="hudCoords" class="text-amber-300 font-bold">-3.2000° S, 107.5000° E</span>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-400">Kecepatan:</span>
                            <span id="hudSpeed" class="text-emerald-400 font-bold">14.6 Knots (27 km/h)</span>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-slate-400">Status Cuaca:</span>
                            <span id="hudWeather" class="text-sky-300 font-bold">28°C • Ombak 2.4m</span>
                        </div>
                    </div>

                    <!-- MAP BOTTOM LEGEND -->
                    <div class="absolute bottom-6 left-6 z-10 bg-slate-900/90 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-slate-700/80 shadow-xl text-[11px] space-y-1 text-slate-300">
                        <div class="font-bold text-white text-xs border-b border-slate-700/70 pb-1 mb-1 flex items-center justify-between gap-3">
                            <span>Legenda 3D Maritim:</span>
                            <span class="text-[10px] text-blue-400">Esri 3D Imagery</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">🚢</span>
                            <span>Kapal Kargo Kontainer (Live Radar Ping)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">📍</span>
                            <span>Pelabuhan Asal Kargo (Origin Seaport)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">🏁</span>
                            <span>Pelabuhan Tujuan (Destination Seaport Hub)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-0.5 bg-amber-400 border-b border-dashed border-amber-300"></span>
                            <span>Rute Lintasan Samudra Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT SHIPMENTS TABLE (Slide 10) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800">Daftar Pengiriman Kargo Terkini</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Memantau status pergerakan dan estimasi kedatangan kargo.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-slate-50 text-slate-600 font-semibold text-xs uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3">No. Resi</th>
                                <th class="px-6 py-3">Ekspedisi / Customer</th>
                                <th class="px-6 py-3">Rute (Asal ➔ Tujuan)</th>
                                <th class="px-6 py-3">Status Kargo</th>
                                <th class="px-6 py-3">Pembayaran</th>
                                <th class="px-6 py-3">Kondisi Jadwal (ETA)</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($recentShipments as $shipment)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <!-- Shipment Number -->
                                    <td class="px-6 py-4">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                            {{ $shipment->shipment_number }}
                                        </a>
                                        <div class="text-xs font-sans text-slate-400 font-normal">
                                            {{ $shipment->cargo_type }}
                                        </div>
                                    </td>

                                    <!-- Carrier & Customer -->
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $shipment->carrier->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $shipment->customer->name ?? '-' }}</div>
                                    </td>

                                    <!-- Route -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 font-medium text-slate-800">
                                            <span>{{ $shipment->origin->city ?? '-' }}</span>
                                            <span class="text-slate-400">➔</span>
                                            <span>{{ $shipment->destination->city ?? '-' }}</span>
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ $shipment->origin->code ?? '' }} - {{ $shipment->destination->code ?? '' }}
                                        </div>
                                    </td>

                                    <!-- Lifecycle Status Badge (Slide 6) -->
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'CREATED'    => 'bg-slate-100 text-slate-700 border-slate-200',
                                                'BOOKED'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'PICKED_UP'  => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'IN_TRANSIT' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'DELIVERED'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'CANCELLED'  => 'bg-red-50 text-red-700 border-red-200',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClasses[$shipment->status] ?? 'bg-slate-100' }}">
                                            {{ $shipment->status }}
                                        </span>
                                    </td>

                                    <!-- Payment Status Badge (Payment Gateway) -->
                                    <td class="px-6 py-4">
                                        @if ($shipment->payment_status === 'PAID')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                                                PAID
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-800">
                                                UNPAID
                                            </span>
                                        @endif
                                        <div class="text-xs text-slate-500 font-mono mt-0.5">
                                            Rp {{ number_format($shipment->freight_cost, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    <!-- Delay & ETA Status (Slide 8) -->
                                    <td class="px-6 py-4">
                                        @if ($shipment->is_delayed)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                ⚠️ Terlambat +{{ $shipment->delay_hours }} Jam
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✓ Tepat Waktu
                                            </span>
                                        @endif
                                        <div class="text-xs text-slate-500 mt-1">
                                            ETA: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </td>

                                    <!-- Action Button -->
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                            <span>Lihat Peta 3D</span>
                                            <span>🗺️</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada data kargo yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- FLEET 3D DATA JAVASCRIPT INITIALIZATION -->
    @php
        $fleetJsonData = $recentShipments->map(function ($s) {
            $lastEvent = $s->trackingEvents->sortByDesc('event_time')->first();
            $lat = $lastEvent && $lastEvent->latitude ? (float) $lastEvent->latitude : ($s->origin ? (float) $s->origin->latitude : null);
            $lng = $lastEvent && $lastEvent->longitude ? (float) $lastEvent->longitude : ($s->origin ? (float) $s->origin->longitude : null);
            return [
                'id' => $s->id,
                'number' => $s->shipment_number,
                'carrier' => $s->carrier ? $s->carrier->name : '-',
                'carrier_code' => $s->carrier ? $s->carrier->code : '',
                'cargo' => $s->cargo_type,
                'status' => $s->status,
                'is_delayed' => (bool) $s->is_delayed,
                'delay_hours' => $s->delay_hours,
                'origin' => [
                    'city' => $s->origin ? $s->origin->city : 'Jakarta',
                    'name' => $s->origin ? $s->origin->name : 'Tanjung Priok',
                    'lat' => $s->origin ? (float) $s->origin->latitude : -6.1017,
                    'lng' => $s->origin ? (float) $s->origin->longitude : 106.8833,
                ],
                'dest' => [
                    'city' => $s->destination ? $s->destination->city : 'Singapore',
                    'name' => $s->destination ? $s->destination->name : 'Port of Singapore',
                    'lat' => $s->destination ? (float) $s->destination->latitude : 1.2644,
                    'lng' => $s->destination ? (float) $s->destination->longitude : 103.8400,
                ],
                'current' => [
                    'lat' => $lat,
                    'lng' => $lng,
                    'location_name' => $lastEvent ? $lastEvent->location_name : ($s->origin ? $s->origin->name : 'Hub'),
                    'description' => $lastEvent ? $lastEvent->description : 'Kargo terdaftar di sistem.',
                ],
                'show_url' => route('shipments.show', $s),
            ];
        })->values();
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fleetData = @json($fleetJsonData);
            const mapContainer = document.getElementById('dashboardFleetMap');

            if (!mapContainer || typeof L === 'undefined') return;

            // 1. BASE LAYERS (3D Satellite, Nautical, and Street)
            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri &mdash; High-Res 3D Satellite Ocean Imagery',
                maxZoom: 18
            });

            const oceanLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; CartoDB &mdash; 3D Nautical Sea Voyager',
                maxZoom: 18
            });

            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            });

            // Inisialisasi Peta Leaflet
            const fleetMap = L.map('dashboardFleetMap', {
                layers: [satelliteLayer],
                scrollWheelZoom: true,
                zoomSnap: 0.5,
            }).setView([-2.5, 106.0], 5.5);

            let activeBaseLayer = satelliteLayer;

            // Switch Base Layer Function
            window.switchBaseLayer = function(type) {
                fleetMap.removeLayer(activeBaseLayer);
                const btnSat = document.getElementById('btnLayerSat');
                const btnOcean = document.getElementById('btnLayerOcean');
                const btnOsm = document.getElementById('btnLayerOsm');

                [btnSat, btnOcean, btnOsm].forEach(btn => {
                    btn.className = 'px-2.5 py-1 rounded-lg font-bold transition text-slate-300 hover:text-white';
                });

                if (type === 'satellite') {
                    activeBaseLayer = satelliteLayer;
                    btnSat.className = 'px-2.5 py-1 rounded-lg font-bold transition bg-blue-600 text-white shadow-sm';
                } else if (type === 'ocean') {
                    activeBaseLayer = oceanLayer;
                    btnOcean.className = 'px-2.5 py-1 rounded-lg font-bold transition bg-blue-600 text-white shadow-sm';
                } else {
                    activeBaseLayer = osmLayer;
                    btnOsm.className = 'px-2.5 py-1 rounded-lg font-bold transition bg-blue-600 text-white shadow-sm';
                }

                fleetMap.addLayer(activeBaseLayer);
            };

            // 2. TOGGLE 3D PERSPECTIVE TILT
            let is3DMode = true;
            window.toggle3DPerspective = function() {
                is3DMode = !is3DMode;
                const wrap = document.getElementById('map3DWrap');
                const text3D = document.getElementById('text3D');
                const icon3D = document.getElementById('icon3D');

                if (is3DMode) {
                    wrap.classList.remove('mode-2d');
                    wrap.classList.add('mode-3d');
                    text3D.innerText = 'Mode 3D: Aktif (Tilt 24°)';
                    icon3D.innerText = '📐';
                } else {
                    wrap.classList.remove('mode-3d');
                    wrap.classList.add('mode-2d');
                    text3D.innerText = 'Mode 2D: Datar';
                    icon3D.innerText = '🧭';
                }

                setTimeout(() => {
                    fleetMap.invalidateSize();
                }, 400);
            };

            // 3. ICONS
            const vesselIcon = L.divIcon({
                html: `
                    <div class="relative" style="transform: translate(-14px, -14px);">
                        <div class="vessel-sonar-ring"></div>
                        <div style="font-size:32px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.6)); position: relative; z-index: 2;">🚢</div>
                    </div>
                `,
                className: 'fleet-vessel-marker',
                iconSize: [28, 28]
            });

            const portOriginIcon = L.divIcon({
                html: '<div style="font-size:24px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.5)); transform: translate(-12px, -12px);">📍</div>',
                className: 'fleet-origin-marker',
                iconSize: [24, 24]
            });

            const portDestIcon = L.divIcon({
                html: '<div style="font-size:24px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.5)); transform: translate(-12px, -12px);">🏁</div>',
                className: 'fleet-dest-marker',
                iconSize: [24, 24]
            });

            const allMarkersGroup = L.featureGroup();
            const shipmentEntities = {};

            fleetData.forEach(item => {
                if (!item.origin.lat || !item.dest.lat) return;

                const originCoord = [item.origin.lat, item.origin.lng];
                const destCoord = [item.dest.lat, item.dest.lng];
                const currentCoord = (item.current.lat && item.current.lng) 
                    ? [item.current.lat, item.current.lng] 
                    : originCoord;

                // Marker Asal
                const mOrigin = L.marker(originCoord, { icon: portOriginIcon })
                    .bindPopup(`<strong style="color:#0284c7;">📍 Pelabuhan Asal:</strong><br>${item.origin.name} (${item.origin.city})`);
                allMarkersGroup.addLayer(mOrigin);

                // Marker Tujuan
                const mDest = L.marker(destCoord, { icon: portDestIcon })
                    .bindPopup(`<strong style="color:#10b981;">🏁 Pelabuhan Tujuan:</strong><br>${item.dest.name} (${item.dest.city})`);
                allMarkersGroup.addLayer(mDest);

                // Polyline Rute Laut
                const routePoints = (item.status === 'IN_TRANSIT') 
                    ? [originCoord, currentCoord, destCoord] 
                    : [originCoord, destCoord];

                const routeColor = item.status === 'IN_TRANSIT' ? '#fbbf24' : (item.status === 'DELIVERED' ? '#34d399' : '#60a5fa');

                const polyline = L.polyline(routePoints, {
                    color: routeColor,
                    weight: 4,
                    dashArray: '8, 10',
                    className: 'animated-sea-route',
                    opacity: 0.9
                });
                allMarkersGroup.addLayer(polyline);

                // Marker Utama Kapal
                let mainMarker;
                if (item.status === 'IN_TRANSIT') {
                    mainMarker = L.marker(currentCoord, { icon: vesselIcon });
                } else if (item.status === 'DELIVERED') {
                    mainMarker = L.marker(destCoord, { icon: portDestIcon });
                } else {
                    mainMarker = L.marker(originCoord, { icon: portOriginIcon });
                }

                const delayBadge = item.is_delayed 
                    ? `<span style="background:#ffe4e6;color:#e11d48;padding:2px 8px;border-radius:6px;font-weight:bold;font-size:10px;">⚠️ Deviasi +${item.delay_hours} Jam</span>` 
                    : `<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:6px;font-weight:bold;font-size:10px;">✓ Tepat Waktu</span>`;

                const popupHtml = `
                    <div style="font-family: inherit; font-size: 12px; min-width: 230px; padding: 4px;">
                        <div style="font-size: 14px; font-weight: 800; color: #1e3a8a; border-bottom: 2px solid #e2e8f0; padding-bottom: 5px; margin-bottom: 6px; display: flex; justify-content: space-between; align-items: center;">
                            <span>🚢 ${item.number}</span>
                            <span style="font-size:10px; background:#eff6ff; color:#1d4ed8; padding:2px 6px; border-radius:4px;">${item.status}</span>
                        </div>
                        <div style="margin-bottom: 4px;"><strong>Muatan:</strong> ${item.cargo}</div>
                        <div style="margin-bottom: 4px;"><strong>Ekspedisi:</strong> ${item.carrier}</div>
                        <div style="margin-bottom: 4px;"><strong>Rute Laut:</strong> ${item.origin.city} ➔ ${item.dest.city}</div>
                        <div style="margin-bottom: 8px;"><strong>Jadwal:</strong> ${delayBadge}</div>
                        <div style="font-size: 11px; color: #475569; background: #f8fafc; padding: 6px; border-radius: 6px; margin-bottom: 10px; border-left: 3px solid #3b82f6;">
                            <em>${item.current.description}</em>
                        </div>
                        <a href="${item.show_url}" style="display: block; text-align: center; background: linear-gradient(to right, #2563eb, #4f46e5); color: #fff; padding: 8px 12px; border-radius: 8px; font-weight: bold; text-decoration: none; font-size: 11px; box-shadow: 0 4px 6px -1px rgba(37,99,235,0.3);">
                            Buka Detail Pelacakan Lengkap ➔
                        </a>
                    </div>
                `;

                mainMarker.bindPopup(popupHtml);
                allMarkersGroup.addLayer(mainMarker);

                shipmentEntities[item.number] = {
                    item: item,
                    marker: mainMarker,
                    coords: currentCoord,
                    bounds: polyline.getBounds()
                };
            });

            allMarkersGroup.addTo(fleetMap);

            if (allMarkersGroup.getLayers().length > 0) {
                fleetMap.fitBounds(allMarkersGroup.getBounds(), { padding: [50, 50] });
            }

            // 4. SMART SEARCH ENGINE IMPLEMENTATION
            window.handleFleetSearch = function(query) {
                const q = query.toLowerCase().trim();
                const resultsContainer = document.getElementById('fleetSearchResults');
                const clearBtn = document.getElementById('clearSearchBtn');

                if (!q) {
                    resultsContainer.classList.add('hidden');
                    clearBtn.classList.add('hidden');
                    return;
                }

                clearBtn.classList.remove('hidden');

                const matches = fleetData.filter(item => {
                    return item.number.toLowerCase().includes(q) ||
                           item.carrier.toLowerCase().includes(q) ||
                           item.cargo.toLowerCase().includes(q) ||
                           item.status.toLowerCase().includes(q) ||
                           item.origin.city.toLowerCase().includes(q) ||
                           item.origin.name.toLowerCase().includes(q) ||
                           item.dest.city.toLowerCase().includes(q) ||
                           item.dest.name.toLowerCase().includes(q);
                });

                if (matches.length === 0) {
                    resultsContainer.innerHTML = `
                        <div class="p-4 text-center text-slate-400 text-xs">
                            Tidak ditemukan kargo atau pelabuhan dengan kata kunci "<strong>${query}</strong>". Coba ketik <code>CT-2026</code>, <code>Priok</code>, atau <code>Singapore</code>.
                        </div>
                    `;
                } else {
                    resultsContainer.innerHTML = matches.map(m => `
                        <div onclick="selectSearchResult('${m.number}')" class="p-3.5 hover:bg-slate-800/90 cursor-pointer transition flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">${m.status === 'IN_TRANSIT' ? '🚢' : (m.status === 'DELIVERED' ? '🏁' : '📦')}</span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <strong class="text-white font-mono text-sm">${m.number}</strong>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold ${m.status === 'IN_TRANSIT' ? 'bg-amber-500/20 text-amber-300' : (m.status === 'DELIVERED' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-blue-500/20 text-blue-300')}">
                                            ${m.status}
                                        </span>
                                    </div>
                                    <p class="text-slate-400 mt-0.5">${m.carrier} • ${m.cargo}</p>
                                    <p class="text-slate-300 font-medium text-[11px] mt-0.5">${m.origin.city} ➔ ${m.dest.city}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-blue-600/30 text-blue-300 rounded-lg font-bold text-[11px] border border-blue-500/30">
                                Terbang ke Lokasi ✈️
                            </span>
                        </div>
                    `).join('');
                }

                resultsContainer.classList.remove('hidden');
            };

            window.clearFleetSearch = function() {
                const input = document.getElementById('fleetSearchInput');
                input.value = '';
                document.getElementById('fleetSearchResults').classList.add('hidden');
                document.getElementById('clearSearchBtn').classList.add('hidden');
                resetFleetView();
            };

            window.selectSearchResult = function(shipmentNumber) {
                document.getElementById('fleetSearchResults').classList.add('hidden');
                focusShipment(shipmentNumber);
            };

            // Focus on specific shipment with 3D fly animation
            window.focusShipment = function(shipmentNumber) {
                const target = shipmentEntities[shipmentNumber];
                if (target) {
                    fleetMap.flyTo(target.coords, 9, {
                        duration: 1.6,
                        easeLinearity: 0.25
                    });
                    setTimeout(() => {
                        target.marker.openPopup();
                    }, 1700);

                    // Update HUD Telemetry
                    document.getElementById('hudVesselName').innerText = target.item.number + ' (' + target.item.carrier + ')';
                    document.getElementById('hudCoords').innerText = target.coords[0].toFixed(4) + '°, ' + target.coords[1].toFixed(4) + '°';
                    document.getElementById('hudSpeed').innerText = target.item.status === 'IN_TRANSIT' ? '14.6 Knots (27 km/h)' : '0.0 Knots (Berlabuh)';
                    document.getElementById('hudWeather').innerText = target.item.status === 'IN_TRANSIT' ? '28°C • Ombak 2.4m' : 'Terkendali di Dermaga';
                }
            };

            window.resetFleetView = function() {
                if (allMarkersGroup.getLayers().length > 0) {
                    fleetMap.flyToBounds(allMarkersGroup.getBounds(), {
                        padding: [50, 50],
                        duration: 1.4
                    });
                }
            };

            window.quickFilterStatus = function(status) {
                const match = fleetData.find(item => item.status === status);
                if (match) {
                    focusShipment(match.number);
                } else {
                    resetFleetView();
                }
            };
        });
    </script>
</x-app-layout>
