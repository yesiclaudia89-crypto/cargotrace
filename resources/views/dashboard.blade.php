<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 leading-tight flex items-center gap-2">
                    <span>CargoTrace Visibility Dashboard</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 flex items-center gap-1">
                        <span>🌍</span>
                        <span>3D Earth Globe Engine v3.0</span>
                    </span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pemantauan kargo antariksa 3D Globe, radar AIS satelit real-time, dan deteksi deviasi jalur maritim.
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

    <!-- Three.js & Globe.gl Assets for Interactive 3D Earth Globe -->
    <script src="https://unpkg.com/three@0.160.0/build/three.min.js"></script>
    <script src="https://unpkg.com/globe.gl@2.32.2/dist/globe.gl.min.js"></script>

    <!-- Leaflet.js Assets for Tactical 2D/3D Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- 3D Styles -->
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
        #globeContainer canvas {
            outline: none;
            cursor: grab;
            border-radius: 1.25rem;
        }
        #globeContainer canvas:active {
            cursor: grabbing;
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

            <!-- 3D GLOBE & TACTICAL MARITIME RADAR DECK -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/60 overflow-hidden">
                
                <!-- TOP HEADER & CONTROLS -->
                <div class="p-5 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500"></span>
                                </span>
                                <h3 class="font-extrabold text-lg sm:text-xl tracking-tight text-white flex items-center gap-2">
                                    <span>🌍 3D Interactive Earth Globe & Maritime Radar</span>
                                </h3>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                    Orbital View
                                </span>
                            </div>
                            <p class="text-xs text-slate-300 mt-1">
                                Rotasi bola dunia 3D interaktif Three.js, visualisasi lintasan busur samudra (Great Circle), dan pelacakan armada kapal kontainer.
                            </p>
                        </div>

                        <!-- DISPLAY MODE SWITCHER: 3D GLOBE vs PETA TAKTIS LEAFLET -->
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Switch Tab: 3D Globe vs Leaflet Map -->
                            <div class="inline-flex rounded-2xl bg-slate-800/90 p-1 border border-slate-700 text-xs">
                                <button type="button" onclick="switchVisualMode('globe')" id="tabBtnGlobe" class="px-3.5 py-1.5 rounded-xl font-extrabold transition bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md flex items-center gap-1.5">
                                    <span>🌍</span>
                                    <span>3D Globe Bumi</span>
                                </button>
                                <button type="button" onclick="switchVisualMode('map')" id="tabBtnMap" class="px-3.5 py-1.5 rounded-xl font-bold transition text-slate-300 hover:text-white flex items-center gap-1.5">
                                    <span>🗺️</span>
                                    <span>Peta Taktis Leaflet</span>
                                </button>
                            </div>

                            <!-- 3D Globe Controls (Visible only in Globe mode) -->
                            <div id="globeControlsGroup" class="flex items-center gap-1.5">
                                <button type="button" onclick="toggleGlobeRotate()" id="btnGlobeRotate" title="Putar Otomatis" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-cyan-300 border border-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                    <span id="rotateIcon">🔄</span>
                                    <span id="rotateText">Rotasi: Aktif</span>
                                </button>
                                <button type="button" onclick="toggleGlobeTheme()" id="btnGlobeTheme" title="Ganti Mode Malam/Siang" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-amber-300 border border-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                    <span id="themeIcon">☀️</span>
                                    <span>Siang/Malam</span>
                                </button>
                                <button type="button" onclick="resetGlobeView()" title="Pusatkan Indonesia & Asia" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold transition">
                                    🔭 Reset Orbit
                                </button>
                            </div>

                            <!-- Leaflet Map Controls (Visible only in Map mode) -->
                            <div id="leafletControlsGroup" class="hidden flex items-center gap-1.5">
                                <button type="button" id="toggle3DBtn" onclick="toggle3DPerspective()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl border border-slate-700 transition flex items-center gap-1">
                                    <span id="icon3D">📐</span>
                                    <span id="text3D">Tilt 3D</span>
                                </button>
                                <div class="inline-flex rounded-xl bg-slate-800 p-0.5 border border-slate-700 text-xs">
                                    <button type="button" onclick="switchBaseLayer('satellite')" id="btnLayerSat" class="px-2 py-1 rounded-lg font-bold bg-blue-600 text-white">🛰️ Satelit</button>
                                    <button type="button" onclick="switchBaseLayer('ocean')" id="btnLayerOcean" class="px-2 py-1 rounded-lg font-bold text-slate-300">🌊 Laut</button>
                                    <button type="button" onclick="switchBaseLayer('osm')" id="btnLayerOsm" class="px-2 py-1 rounded-lg font-bold text-slate-300">🏙️ Jalan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SMART LIVE SEARCH BAR -->
                    <div class="mt-5 relative">
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="fleetSearchInput" 
                                oninput="handleFleetSearch(this.value)" 
                                placeholder="🔍 Cari kargo mudah di 3D Globe: Ketik No. Resi (CT-...), Kapal, Pelabuhan, atau Kargo..." 
                                class="w-full pl-11 pr-24 py-3 bg-slate-900/90 hover:bg-slate-900 focus:bg-slate-950 border border-slate-700/80 focus:border-cyan-500 rounded-2xl text-white placeholder-slate-400 text-xs sm:text-sm font-medium shadow-inner transition focus:outline-none focus:ring-2 focus:ring-cyan-500/40"
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
                        <span class="text-slate-400 font-semibold mr-1">Fokus Orbit Cepat:</span>
                        <button type="button" onclick="focusShipment('CT-2026-001')" class="px-2.5 py-1 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 font-bold border border-amber-500/30 transition flex items-center gap-1">
                            <span>🚢</span>
                            <span>MV Samudera Perkasa (In Transit)</span>
                        </button>
                        <button type="button" onclick="focusShipment('CT-2026-002')" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 font-bold border border-emerald-500/30 transition flex items-center gap-1">
                            <span>🏁</span>
                            <span>CT-2026-002 (Rotterdam)</span>
                        </button>
                        <button type="button" onclick="focusShipment('CT-2026-003')" class="px-2.5 py-1 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 font-bold border border-blue-500/30 transition flex items-center gap-1">
                            <span>📦</span>
                            <span>CT-2026-003 (Booking Priok)</span>
                        </button>
                        <button type="button" onclick="resetFleetView()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium border border-slate-700 transition">
                            🌐 Reset Orbit Globe
                        </button>
                    </div>
                </div>

                <!-- 3D VISUALIZATION VIEWPORT -->
                <div class="relative bg-slate-950 overflow-hidden p-2 sm:p-4">
                    
                    <!-- 1. THREE.JS 3D EARTH GLOBE CONTAINER -->
                    <div id="globeViewWrap" class="w-full flex justify-center items-center bg-slate-950 rounded-2xl overflow-hidden relative">
                        <div id="globeContainer" class="w-full" style="height: 520px; min-height: 520px;"></div>

                        <!-- 3D Globe Hint Overlay -->
                        <div class="absolute top-4 left-4 z-10 bg-slate-900/80 backdrop-blur-md px-3.5 py-2 rounded-xl border border-slate-700/70 text-[11px] text-slate-300 flex items-center gap-2 pointer-events-none">
                            <span class="text-base">🖱️</span>
                            <span>Geser kursor untuk memutar bola bumi 360° • Scroll untuk zoom in/out</span>
                        </div>
                    </div>

                    <!-- 2. LEAFLET TACTICAL MAP CONTAINER (Hidden by default, shown when switched) -->
                    <div id="leafletViewWrap" class="hidden">
                        <div id="map3DWrap" class="map-3d-wrap mode-3d">
                            <div id="dashboardFleetMap" style="height: 520px; width: 100%;" class="z-0 bg-slate-900 rounded-2xl"></div>
                        </div>
                    </div>

                    <!-- 3D TACTICAL HUD OVERLAY (Real-time telemetry) -->
                    <div class="hidden sm:block absolute top-6 right-6 z-10 bg-slate-900/90 backdrop-blur-md px-4 py-3 rounded-2xl border border-slate-700/80 shadow-2xl text-[11px] text-slate-200 font-mono space-y-1.5 pointer-events-none">
                        <div class="flex items-center justify-between gap-4 border-b border-slate-700/80 pb-1 text-slate-400 font-sans font-bold text-xs">
                            <span class="flex items-center gap-1.5 text-cyan-400">
                                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                                TELEMETRI 3D GLOBE
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
                            <span>Legenda 3D Globe:</span>
                            <span class="text-[10px] text-cyan-400 font-mono">Three.js Engine</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">🚢</span>
                            <span>Kapal Kargo Kontainer (Live AIS Radar)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">📍</span>
                            <span>Pelabuhan Asal Kargo (Origin Hub)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">🏁</span>
                            <span>Pelabuhan Tujuan (Destination Hub)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-0.5 bg-cyan-400"></span>
                            <span>Busur Lintasan Samudra 3D (Great Circle)</span>
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

    <!-- JAVASCRIPT: 3D GLOBE (GLOBE.GL) & LEAFLET DUAL ENGINE -->
    @php
        $fleetJsonData = $recentShipments->map(function ($s) {
            $lastEvent = $s->trackingEvents->sortByDesc('event_time')->first();
            $lat = $lastEvent && $lastEvent->latitude ? (float) $lastEvent->latitude : ($s->origin ? (float) $s->origin->latitude : null);
            $lng = $lastEvent && $lastEvent->longitude ? (float) $lastEvent->longitude : ($s->origin ? (float) $s->origin->longitude : null);
            return [
                'id' => $s->id,
                'number' => $s->shipment_number,
                'carrier' => $s->carrier ? $s->carrier->name : '-',
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
        const fleetData = @json($fleetJsonData);
        let currentVisualMode = 'globe';
        let myGlobe = null;
        let isGlobeRotating = true;
        let isGlobeNight = false;

        // Leaflet Global Variables
        let fleetMap = null;
        let activeBaseLayer = null;
        let satelliteLayer = null, oceanLayer = null, osmLayer = null;
        let allMarkersGroup = null;
        let shipmentEntities = {};

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Initialize 3D Globe First
            init3DGlobe();

            // 2. Initialize Leaflet Map (for tab switching)
            initLeafletMap();
        });

        // ==========================================
        // 1. THREE.JS & GLOBE.GL 3D GLOBE ENGINE
        // ==========================================
        function init3DGlobe() {
            const container = document.getElementById('globeContainer');
            if (!container || typeof Globe === 'undefined') return;

            // Prepare 3D Arcs (Curved maritime routes across the globe)
            const arcsData = [];
            const pointsData = [];
            const ringsData = [];
            const labelsData = [];

            fleetData.forEach(item => {
                const originCoord = [item.origin.lat, item.origin.lng];
                const destCoord = [item.dest.lat, item.dest.lng];
                const currentCoord = (item.current.lat && item.current.lng) ? [item.current.lat, item.current.lng] : originCoord;

                // 3D Curved Arc from Origin to Current to Destination
                if (item.status === 'IN_TRANSIT') {
                    // Segment 1: Origin to Ship
                    arcsData.push({
                        startLat: item.origin.lat,
                        startLng: item.origin.lng,
                        endLat: currentCoord[0],
                        endLng: currentCoord[1],
                        color: ['#0284c7', '#f59e0b'],
                        altitude: 0.22,
                        name: `${item.number}: ${item.origin.city} ➔ Kapal (In Transit)`
                    });
                    // Segment 2: Ship to Dest
                    arcsData.push({
                        startLat: currentCoord[0],
                        startLng: currentCoord[1],
                        endLat: item.dest.lat,
                        endLng: item.dest.lng,
                        color: ['#f59e0b', '#10b981'],
                        altitude: 0.22,
                        name: `${item.number}: Kapal ➔ ${item.dest.city}`
                    });
                } else {
                    arcsData.push({
                        startLat: item.origin.lat,
                        startLng: item.origin.lng,
                        endLat: item.dest.lat,
                        endLng: item.dest.lng,
                        color: item.status === 'DELIVERED' ? ['#10b981', '#34d399'] : ['#38bdf8', '#818cf8'],
                        altitude: 0.25,
                        name: `${item.number}: ${item.origin.city} ➔ ${item.dest.city} (${item.status})`
                    });
                }

                // Points & Labels
                pointsData.push({
                    lat: currentCoord[0],
                    lng: currentCoord[1],
                    size: item.status === 'IN_TRANSIT' ? 0.9 : 0.6,
                    color: item.status === 'IN_TRANSIT' ? '#f59e0b' : (item.status === 'DELIVERED' ? '#10b981' : '#38bdf8'),
                    number: item.number,
                    item: item
                });

                // Sonar Ripple Ring on Ship & Destination
                ringsData.push({
                    lat: currentCoord[0],
                    lng: currentCoord[1],
                    maxR: item.status === 'IN_TRANSIT' ? 6 : 4,
                    propagationSpeed: item.status === 'IN_TRANSIT' ? 2 : 1.2,
                    repeatPeriod: 1200
                });

                labelsData.push({
                    lat: currentCoord[0],
                    lng: currentCoord[1],
                    text: item.status === 'IN_TRANSIT' ? `🚢 ${item.number}` : (item.status === 'DELIVERED' ? `✅ ${item.number}` : `📦 ${item.number}`),
                    color: item.status === 'IN_TRANSIT' ? '#fbbf24' : '#ffffff',
                    size: 1.0,
                    number: item.number
                });
            });

            // Add Ports
            pointsData.push(
                { lat: -6.1017, lng: 106.8833, size: 0.5, color: '#0284c7', number: 'CT-2026-001' },
                { lat: 1.2644, lng: 103.8400, size: 0.5, color: '#10b981', number: 'CT-2026-001' },
                { lat: 51.9547, lng: 4.1378, size: 0.5, color: '#10b981', number: 'CT-2026-002' }
            );

            labelsData.push(
                { lat: -6.1017, lng: 106.8833, text: '📍 Tanjung Priok', color: '#38bdf8', size: 0.85 },
                { lat: 1.2644, lng: 103.8400, text: '🏁 Port of Singapore', color: '#4ade80', size: 0.85 },
                { lat: 51.9547, lng: 4.1378, text: '🏁 Port of Rotterdam', color: '#4ade80', size: 0.85 }
            );

            const width = container.clientWidth || 950;
            const height = 520;

            myGlobe = Globe()
                (container)
                .width(width)
                .height(height)
                .globeImageUrl('https://unpkg.com/three-globe/example/img/earth-blue-marble.jpg')
                .bumpImageUrl('https://unpkg.com/three-globe/example/img/earth-topology.png')
                .backgroundImageUrl('https://unpkg.com/three-globe/example/img/night-sky.png')
                .atmosphereColor('#38bdf8')
                .atmosphereAltitude(0.24)
                // Arcs
                .arcsData(arcsData)
                .arcColor('color')
                .arcDashLength(0.4)
                .arcDashGap(0.2)
                .arcDashAnimateTime(1600)
                .arcStroke(1.8)
                .arcAltitude('altitude')
                // Points
                .pointsData(pointsData)
                .pointColor('color')
                .pointAltitude(0.04)
                .pointRadius('size')
                // Rings
                .ringsData(ringsData)
                .ringColor(() => '#fbbf24')
                .ringMaxRadius('maxR')
                .ringPropagationSpeed('propagationSpeed')
                .ringRepeatPeriod('repeatPeriod')
                // Labels
                .labelsData(labelsData)
                .labelText('text')
                .labelSize('size')
                .labelColor('color')
                .labelDotRadius(0.3)
                .labelAltitude(0.02)
                .onPointClick(point => {
                    if (point.number) focusShipment(point.number);
                })
                .onLabelClick(label => {
                    if (label.number) focusShipment(label.number);
                });

            // Initial camera angle centered on Indonesia & Southeast Asia
            myGlobe.pointOfView({ lat: -2.5, lng: 108.0, altitude: 2.2 }, 1200);

            // Enable smooth auto-rotation
            myGlobe.controls().autoRotate = true;
            myGlobe.controls().autoRotateSpeed = 0.8;

            window.addEventListener('resize', () => {
                if (myGlobe && container) {
                    myGlobe.width(container.clientWidth);
                }
            });
        }

        // ==========================================
        // 2. 3D GLOBE INTERACTIVE CONTROLS
        // ==========================================
        window.toggleGlobeRotate = function() {
            if (!myGlobe) return;
            isGlobeRotating = !isGlobeRotating;
            myGlobe.controls().autoRotate = isGlobeRotating;
            document.getElementById('rotateText').innerText = isGlobeRotating ? 'Rotasi: Aktif' : 'Rotasi: Diam';
            document.getElementById('rotateIcon').innerText = isGlobeRotating ? '🔄' : '⏸️';
        };

        window.toggleGlobeTheme = function() {
            if (!myGlobe) return;
            isGlobeNight = !isGlobeNight;
            const texture = isGlobeNight 
                ? 'https://unpkg.com/three-globe/example/img/earth-night.jpg' 
                : 'https://unpkg.com/three-globe/example/img/earth-blue-marble.jpg';
            myGlobe.globeImageUrl(texture);
            document.getElementById('themeIcon').innerText = isGlobeNight ? '🌙' : '☀️';
        };

        window.resetGlobeView = function() {
            if (myGlobe) {
                myGlobe.pointOfView({ lat: -2.5, lng: 108.0, altitude: 2.2 }, 1500);
            }
        };

        // ==========================================
        // 3. SWITCH BETWEEN 3D GLOBE & LEAFLET MAP
        // ==========================================
        window.switchVisualMode = function(mode) {
            currentVisualMode = mode;
            const globeWrap = document.getElementById('globeViewWrap');
            const leafletWrap = document.getElementById('leafletViewWrap');
            const globeControls = document.getElementById('globeControlsGroup');
            const leafletControls = document.getElementById('leafletControlsGroup');
            const tabBtnGlobe = document.getElementById('tabBtnGlobe');
            const tabBtnMap = document.getElementById('tabBtnMap');

            if (mode === 'globe') {
                globeWrap.classList.remove('hidden');
                leafletWrap.classList.add('hidden');
                globeControls.classList.remove('hidden');
                leafletControls.classList.add('hidden');

                tabBtnGlobe.className = 'px-3.5 py-1.5 rounded-xl font-extrabold transition bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md flex items-center gap-1.5';
                tabBtnMap.className = 'px-3.5 py-1.5 rounded-xl font-bold transition text-slate-300 hover:text-white flex items-center gap-1.5';

                if (myGlobe) {
                    const container = document.getElementById('globeContainer');
                    myGlobe.width(container.clientWidth);
                }
            } else {
                globeWrap.classList.add('hidden');
                leafletWrap.classList.remove('hidden');
                globeControls.classList.add('hidden');
                leafletControls.classList.remove('hidden');

                tabBtnMap.className = 'px-3.5 py-1.5 rounded-xl font-extrabold transition bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md flex items-center gap-1.5';
                tabBtnGlobe.className = 'px-3.5 py-1.5 rounded-xl font-bold transition text-slate-300 hover:text-white flex items-center gap-1.5';

                setTimeout(() => {
                    if (fleetMap) fleetMap.invalidateSize();
                }, 200);
            }
        };

        // ==========================================
        // 4. LEAFLET TACTICAL MAP INITIALIZATION
        // ==========================================
        function initLeafletMap() {
            const mapContainer = document.getElementById('dashboardFleetMap');
            if (!mapContainer || typeof L === 'undefined') return;

            satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri &mdash; High-Res 3D Satellite Ocean Imagery',
                maxZoom: 18
            });

            oceanLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; CartoDB &mdash; 3D Nautical Sea Voyager',
                maxZoom: 18
            });

            osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            });

            fleetMap = L.map('dashboardFleetMap', {
                layers: [satelliteLayer],
                scrollWheelZoom: true,
                zoomSnap: 0.5,
            }).setView([-2.5, 106.0], 5.5);

            activeBaseLayer = satelliteLayer;

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

            allMarkersGroup = L.featureGroup();

            fleetData.forEach(item => {
                if (!item.origin.lat || !item.dest.lat) return;

                const originCoord = [item.origin.lat, item.origin.lng];
                const destCoord = [item.dest.lat, item.dest.lng];
                const currentCoord = (item.current.lat && item.current.lng) ? [item.current.lat, item.current.lng] : originCoord;

                const mOrigin = L.marker(originCoord, { icon: portOriginIcon })
                    .bindPopup(`<strong style="color:#0284c7;">📍 Pelabuhan Asal:</strong><br>${item.origin.name} (${item.origin.city})`);
                allMarkersGroup.addLayer(mOrigin);

                const mDest = L.marker(destCoord, { icon: portDestIcon })
                    .bindPopup(`<strong style="color:#10b981;">🏁 Pelabuhan Tujuan:</strong><br>${item.dest.name} (${item.dest.city})`);
                allMarkersGroup.addLayer(mDest);

                const routePoints = (item.status === 'IN_TRANSIT') ? [originCoord, currentCoord, destCoord] : [originCoord, destCoord];
                const routeColor = item.status === 'IN_TRANSIT' ? '#fbbf24' : (item.status === 'DELIVERED' ? '#34d399' : '#60a5fa');

                const polyline = L.polyline(routePoints, {
                    color: routeColor,
                    weight: 4,
                    dashArray: '8, 10',
                    className: 'animated-sea-route',
                    opacity: 0.9
                });
                allMarkersGroup.addLayer(polyline);

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
                        <a href="${item.show_url}" style="display: block; text-align: center; background: linear-gradient(to right, #2563eb, #4f46e5); color: #fff; padding: 8px 12px; border-radius: 8px; font-weight: bold; text-decoration: none; font-size: 11px;">
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
        }

        window.switchBaseLayer = function(type) {
            if (!fleetMap) return;
            fleetMap.removeLayer(activeBaseLayer);
            const btnSat = document.getElementById('btnLayerSat');
            const btnOcean = document.getElementById('btnLayerOcean');
            const btnOsm = document.getElementById('btnLayerOsm');

            [btnSat, btnOcean, btnOsm].forEach(btn => {
                btn.className = 'px-2 py-1 rounded-lg font-bold text-slate-300';
            });

            if (type === 'satellite') {
                activeBaseLayer = satelliteLayer;
                btnSat.className = 'px-2 py-1 rounded-lg font-bold bg-blue-600 text-white';
            } else if (type === 'ocean') {
                activeBaseLayer = oceanLayer;
                btnOcean.className = 'px-2 py-1 rounded-lg font-bold bg-blue-600 text-white';
            } else {
                activeBaseLayer = osmLayer;
                btnOsm.className = 'px-2 py-1 rounded-lg font-bold bg-blue-600 text-white';
            }
            fleetMap.addLayer(activeBaseLayer);
        };

        let is3DMode = true;
        window.toggle3DPerspective = function() {
            is3DMode = !is3DMode;
            const wrap = document.getElementById('map3DWrap');
            const text3D = document.getElementById('text3D');
            const icon3D = document.getElementById('icon3D');

            if (is3DMode) {
                wrap.classList.remove('mode-2d');
                wrap.classList.add('mode-3d');
                text3D.innerText = 'Tilt 3D';
                icon3D.innerText = '📐';
            } else {
                wrap.classList.remove('mode-3d');
                wrap.classList.add('mode-2d');
                text3D.innerText = 'Datar';
                icon3D.innerText = '🧭';
            }
            setTimeout(() => { if (fleetMap) fleetMap.invalidateSize(); }, 400);
        };

        // ==========================================
        // 5. SMART SEARCH ENGINE (DUAL GLOBE & MAP)
        // ==========================================
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
                        Tidak ditemukan kargo dengan kata kunci "<strong>${query}</strong>". Coba ketik <code>CT-2026</code>, <code>Priok</code>, atau <code>Singapore</code>.
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
                        <span class="px-2.5 py-1 bg-cyan-600/30 text-cyan-300 rounded-lg font-bold text-[11px] border border-cyan-500/30">
                            Terbang ke Orbit 🌍
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

        // Focus and glide to specific shipment in 3D Globe or Leaflet
        window.focusShipment = function(shipmentNumber) {
            const target = fleetData.find(item => item.number === shipmentNumber);
            if (!target) return;

            const targetLat = target.current.lat || target.origin.lat;
            const targetLng = target.current.lng || target.origin.lng;

            // 1. If in 3D Globe mode: fly smoothly to point of view
            if (currentVisualMode === 'globe' && myGlobe) {
                myGlobe.controls().autoRotate = false;
                myGlobe.pointOfView({
                    lat: targetLat,
                    lng: targetLng,
                    altitude: 1.35
                }, 1800);
            }

            // 2. If in Leaflet mode: flyTo bounds
            if (fleetMap && shipmentEntities[shipmentNumber]) {
                const entity = shipmentEntities[shipmentNumber];
                fleetMap.flyTo(entity.coords, 9, { duration: 1.5 });
                setTimeout(() => entity.marker.openPopup(), 1600);
            }

            // 3. Update HUD Telemetry
            document.getElementById('hudVesselName').innerText = target.number + ' (' + target.carrier + ')';
            document.getElementById('hudCoords').innerText = targetLat.toFixed(4) + '°, ' + targetLng.toFixed(4) + '°';
            document.getElementById('hudSpeed').innerText = target.status === 'IN_TRANSIT' ? '14.6 Knots (27 km/h)' : '0.0 Knots (Berlabuh)';
            document.getElementById('hudWeather').innerText = target.status === 'IN_TRANSIT' ? '28°C • Ombak 2.4m' : 'Terkendali di Dermaga';
        };

        window.resetFleetView = function() {
            if (currentVisualMode === 'globe' && myGlobe) {
                resetGlobeView();
            } else if (fleetMap && allMarkersGroup) {
                fleetMap.flyToBounds(allMarkersGroup.getBounds(), { padding: [50, 50], duration: 1.4 });
            }
        };
    </script>
</x-app-layout>
