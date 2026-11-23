<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pelacakan 3D Publik: {{ $shipment->shipment_number }} — CargoTrace</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        .map-3d-wrap {
            perspective: 1200px;
            transition: all 0.5s ease;
        }
        .map-3d-wrap.mode-3d #map {
            transform: rotateX(24deg) scale(0.97);
            transform-style: preserve-3d;
            box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.45);
            border-radius: 1.5rem;
        }
        .map-3d-wrap.mode-2d #map {
            transform: rotateX(0deg) scale(1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        #map {
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.6s ease;
        }
        @keyframes sonarWave {
            0% { transform: scale(0.6); opacity: 1; }
            100% { transform: scale(2.6); opacity: 0; }
        }
        .sonar-beacon {
            position: absolute;
            top: -12px;
            left: -12px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(245, 158, 11, 0.35);
            border: 2px solid #f59e0b;
            animation: sonarWave 2s cubic-bezier(0.2, 0.8, 0.2, 1) infinite;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 py-3.5 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-xl text-white shadow-md">
                    🚢
                </div>
                <div>
                    <span class="font-extrabold text-xl text-slate-900">Cargo<span class="text-blue-600">Trace</span></span>
                    <span class="block text-[10px] text-slate-500 uppercase tracking-wider font-semibold">3D Maritime Public Tracker</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="/" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 transition">
                    ← Kembali ke Beranda
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition">
                    Masuk Portal Internal ➔
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- FAST SEARCH BAR (Cari Kargo Lain) -->
        <div class="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-700 w-full sm:w-auto">
                <span class="text-lg">🔍</span>
                <span>Lacak Kargo Lain:</span>
            </div>
            <form action="{{ route('shipments.public-track') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto flex-1 max-w-md">
                <input 
                    type="text" 
                    name="number" 
                    placeholder="Ketik No. Resi (misal: CT-2026-002)..." 
                    value="{{ $shipment->shipment_number }}"
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition whitespace-nowrap">
                    Cari ➔
                </button>
            </form>
            <div class="flex items-center gap-1.5 text-xs">
                <span class="text-slate-400 text-[11px]">Sampel:</span>
                <a href="/track/CT-2026-001" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono text-[11px] font-bold">CT-001</a>
                <a href="/track/CT-2026-002" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono text-[11px] font-bold">CT-002</a>
                <a href="/track/CT-2026-003" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono text-[11px] font-bold">CT-003</a>
            </div>
        </div>

        <!-- Top Header Card -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xl shadow-slate-200/60 backdrop-blur-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Hasil Pelacakan 3D Kargo Maritim</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 flex items-center gap-3">
                    <span class="font-mono">{{ $shipment->shipment_number }}</span>
                    <span class="text-base text-slate-500 font-normal">({{ $shipment->cargo_type }})</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Carrier: <strong class="text-slate-800">{{ $shipment->carrier->name }}</strong> • Kode: <span class="font-mono text-blue-600 font-bold">{{ $shipment->carrier->code }}</span>
                </p>
            </div>

            <!-- Status Badge -->
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $statusColors = [
                        'CREATED'    => 'bg-slate-100 text-slate-700 border-slate-300',
                        'BOOKED'     => 'bg-blue-50 text-blue-700 border-blue-300',
                        'PICKED_UP'  => 'bg-indigo-50 text-indigo-700 border-indigo-300',
                        'IN_TRANSIT' => 'bg-amber-50 text-amber-800 border-amber-300',
                        'DELIVERED'  => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                        'CANCELLED'  => 'bg-rose-50 text-rose-700 border-rose-300',
                    ];
                @endphp
                <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold border {{ $statusColors[$shipment->status] ?? 'bg-slate-100' }}">
                    Status: {{ $shipment->status }}
                </span>
                @if ($shipment->payment_status === 'PAID')
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        ✓ Lunas
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                        Belum Dibayar
                    </span>
                @endif
            </div>
        </div>

        <!-- Delay Engine Alert (Slide 9) -->
        @php
            $delayHours = $shipment->delay_hours;
            $delayStatus = $shipment->delay_status;
        @endphp
        @if ($delayHours > 0)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 flex items-start gap-3 shadow-sm">
                <span class="text-xl">⚠️</span>
                <div class="text-xs space-y-0.5">
                    <span class="font-extrabold text-amber-900 text-sm block">Peringatan Keterlambatan Terdeteksi ({{ $delayStatus }})</span>
                    <p class="text-amber-800">
                        Kargo mengalami deviasi jadwal sebesar <strong class="text-amber-950">+{{ $delayHours }} Jam</strong> dibandingkan jadwal awal. Estimasi tiba telah disesuaikan secara otomatis oleh Delay Detection Engine.
                    </p>
                </div>
            </div>
        @else
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center gap-3 shadow-sm">
                <span class="text-xl">✓</span>
                <div class="text-xs">
                    <strong class="font-bold">Kargo Bergerak Sesuai Jadwal (On Time)</strong>
                    <span class="text-emerald-700 block">Tidak terdeteksi deviasi keterlambatan rute maritim.</span>
                </div>
            </div>
        @endif

        <!-- 2-COLUMNS: 3D MAP & TIMELINE -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT 7 COLS: INTERACTIVE 3D MAP & WEATHER -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 3D LEAFLET MAP CONTAINER -->
                <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xl shadow-slate-200/60 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-2 border-b border-slate-100 gap-3">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🗺️</span>
                            <span class="font-bold text-sm text-slate-900">Peta 3D Lintasan Maritim & Radar Kapal</span>
                        </div>

                        <!-- 3D & Layer Controls -->
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <button type="button" onclick="togglePublic3D()" id="btnPublic3D" class="px-2.5 py-1 rounded-lg bg-blue-600 text-white font-bold transition shadow-sm">
                                📐 Mode 3D: Aktif
                            </button>
                            <button type="button" onclick="setPublicLayer('sat')" id="pLayerSat" class="px-2 py-1 rounded-lg bg-slate-800 text-white font-bold transition">
                                🛰️ Satelit
                            </button>
                            <button type="button" onclick="setPublicLayer('osm')" id="pLayerOsm" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition">
                                🏙️ Peta
                            </button>
                        </div>
                    </div>

                    <!-- Map Focus Toolbar -->
                    <div class="flex flex-wrap items-center justify-between text-xs gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-400 text-[11px] font-medium">Fokus Kamera:</span>
                            <button type="button" onclick="focusFullRoute()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs border border-slate-200 transition font-bold">
                                🔍 Seluruh Rute
                            </button>
                            <button type="button" onclick="focusVessel()" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs border border-blue-200 transition font-bold flex items-center gap-1">
                                <span>🚢</span>
                                <span>Posisi Kapal</span>
                            </button>
                        </div>
                        <div class="text-[11px] text-slate-500 font-mono">
                            Kecepatan: <strong class="text-slate-800">{{ $shipment->status === 'IN_TRANSIT' ? '14.6 Knots' : '0.0 Knots' }}</strong>
                        </div>
                    </div>

                    <!-- 3D Map Box -->
                    <div id="publicMapWrap" class="map-3d-wrap mode-3d">
                        <div id="map" class="w-full rounded-2xl overflow-hidden border border-slate-200 shadow-inner z-0 bg-slate-900" style="height: 420px; min-height: 420px;"></div>
                    </div>

                    <!-- Port Leg Footer -->
                    <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <span class="text-slate-500 text-[10px] uppercase font-bold block">Pelabuhan Asal</span>
                            <span class="font-bold text-emerald-700 mt-0.5 block">{{ $shipment->origin->name }}</span>
                            <span class="text-[11px] text-slate-500">{{ $shipment->origin->country }} ({{ $shipment->origin->code }})</span>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                            <span class="text-slate-500 text-[10px] uppercase font-bold block">Pelabuhan Tujuan</span>
                            <span class="font-bold text-rose-700 mt-0.5 block">{{ $shipment->destination->name }}</span>
                            <span class="text-[11px] text-slate-500">{{ $shipment->destination->country }} ({{ $shipment->destination->code }})</span>
                        </div>
                    </div>
                </div>

                <!-- LIVE WEATHER CARD (OPEN-METEO) -->
                @if ($weather)
                    <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xl shadow-slate-200/60 space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🌦️</span>
                                <span class="font-bold text-sm text-slate-900">Sensor Cuaca Maritim Terkini (Open-Meteo)</span>
                            </div>
                            <span class="text-[10px] font-mono text-slate-500">Koordinat: {{ number_format($weather['latitude'], 2) }}°, {{ number_format($weather['longitude'], 2) }}°</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-xs">
                            <div class="bg-sky-50 p-3 rounded-xl border border-sky-100">
                                <span class="text-sky-700 text-[10px] block font-medium">Kondisi Laut</span>
                                <span class="font-bold text-sky-900 text-sm mt-0.5 block">{{ $weather['description'] }}</span>
                            </div>
                            <div class="bg-amber-50 p-3 rounded-xl border border-amber-100">
                                <span class="text-amber-700 text-[10px] block font-medium">Suhu Udara</span>
                                <span class="font-bold text-amber-900 text-sm mt-0.5 block">{{ $weather['temperature'] }} °C</span>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                                <span class="text-blue-700 text-[10px] block font-medium">Kecepatan Angin</span>
                                <span class="font-bold text-blue-900 text-sm mt-0.5 block">{{ $weather['windspeed'] }} km/h</span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT 5 COLS: TRACKING TIMELINE & SCHEDULE -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Timeline Card -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xl shadow-slate-200/60 space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <span>⏱️</span> Riwayat Milestone Perjalanan
                        </h3>
                        <span class="text-[11px] font-mono text-slate-500 font-semibold">{{ $shipment->trackingEvents->count() }} Peristiwa</span>
                    </div>

                    <div class="space-y-6 relative pl-6 before:content-[''] before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                        @forelse ($shipment->trackingEvents as $event)
                            <div class="relative space-y-1">
                                <span class="absolute -left-6 top-1 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-white"></span>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-900">{{ $event->event_type }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $event->event_time->format('d M H:i') }}</span>
                                </div>
                                <div class="text-xs text-blue-600 font-semibold">{{ $event->location_name }}</div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $event->description }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500">Belum ada riwayat event pelacakan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xl shadow-slate-200/60 space-y-4">
                    <h3 class="font-bold text-sm text-slate-900 flex items-center gap-2 pb-2 border-b border-slate-100">
                        <span>📅</span> Perbandingan Jadwal & ETA
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Rencana Keberangkatan:</span>
                            <span class="font-mono text-slate-900 font-semibold">{{ $shipment->expected_departure->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Rencana Tiba (Target Awal):</span>
                            <span class="font-mono text-slate-900 font-semibold">{{ $shipment->expected_arrival->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Estimasi Tiba (Revisi ETA):</span>
                            <span class="font-mono font-bold {{ $shipment->is_delayed ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y H:i') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <span class="text-slate-500">Status Pembayaran Freight:</span>
                            <span class="font-mono font-bold {{ $shipment->payment_status === 'PAID' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $shipment->payment_status }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500">
        CargoTrace • 3D Public Maritime Tracking Engine • Leaflet.js & Esri 3D Satellite • UAS Pemrograman Web 2
    </footer>

    <!-- Leaflet Setup Script with 3D Features -->
    <script>
        const originCoords = [{{ $shipment->origin->latitude }}, {{ $shipment->origin->longitude }}];
        const destCoords = [{{ $shipment->destination->latitude }}, {{ $shipment->destination->longitude }}];
        
        @php
            $latestCoordEvent = $shipment->trackingEvents->filter(fn ($e) => !empty($e->latitude) && !empty($e->longitude))->last();
            $vesselLat = $latestCoordEvent?->latitude ?? $shipment->origin->latitude;
            $vesselLng = $latestCoordEvent?->longitude ?? $shipment->origin->longitude;
        @endphp
        const vesselCoords = [{{ $vesselLat }}, {{ $vesselLng }}];

        // 3D Satellite & OSM Layers
        const satTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '&copy; Esri &mdash; High-Res 3D Satellite Ocean'
        });

        const osmTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        });

        const map = L.map('map', {
            layers: [satTile],
            zoomSnap: 0.25,
            zoomDelta: 0.5
        }).setView(vesselCoords, 5.5);

        let curPublicLayer = satTile;

        window.setPublicLayer = function(type) {
            map.removeLayer(curPublicLayer);
            const bSat = document.getElementById('pLayerSat');
            const bOsm = document.getElementById('pLayerOsm');

            if (type === 'sat') {
                curPublicLayer = satTile;
                bSat.className = 'px-2 py-1 rounded-lg bg-slate-800 text-white font-bold transition';
                bOsm.className = 'px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition';
            } else {
                curPublicLayer = osmTile;
                bOsm.className = 'px-2 py-1 rounded-lg bg-blue-600 text-white font-bold transition';
                bSat.className = 'px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition';
            }
            map.addLayer(curPublicLayer);
        };

        let isPublic3D = true;
        window.togglePublic3D = function() {
            isPublic3D = !isPublic3D;
            const wrap = document.getElementById('publicMapWrap');
            const btn = document.getElementById('btnPublic3D');
            if (isPublic3D) {
                wrap.classList.remove('mode-2d');
                wrap.classList.add('mode-3d');
                btn.innerText = '📐 Mode 3D: Aktif';
                btn.className = 'px-2.5 py-1 rounded-lg bg-blue-600 text-white font-bold transition shadow-sm';
            } else {
                wrap.classList.remove('mode-3d');
                wrap.classList.add('mode-2d');
                btn.innerText = '🧭 Mode 2D: Datar';
                btn.className = 'px-2.5 py-1 rounded-lg bg-slate-700 text-white font-bold transition';
            }
            setTimeout(() => map.invalidateSize(), 400);
        };

        // Origin Marker
        L.marker(originCoords).addTo(map).bindPopup('<strong>📍 Pelabuhan Asal: {{ $shipment->origin->name }}</strong>');

        // Destination Marker
        L.marker(destCoords).addTo(map).bindPopup('<strong>🏁 Pelabuhan Tujuan: {{ $shipment->destination->name }}</strong>');

        // 3D Vessel Marker with Sonar Wave
        const vesselIcon = L.divIcon({
            html: `
                <div class="relative" style="transform: translate(-14px, -14px);">
                    <div class="sonar-beacon"></div>
                    <div style="font-size:32px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.6)); position: relative; z-index: 2;">🚢</div>
                </div>
            `,
            className: 'vessel-marker',
            iconSize: [28, 28]
        });
        const vesselMarker = L.marker(vesselCoords, { icon: vesselIcon }).addTo(map).bindPopup('<strong>🚢 Kapal: {{ $shipment->carrier->name }}</strong><br>Posisi Terkini: {{ $shipment->status }}');

        // Polyline Route with Glowing Amber
        const routeCoords = [originCoords, vesselCoords, destCoords];
        const routeLine = L.polyline(routeCoords, { color: '#fbbf24', weight: 4, dashArray: '6, 8', opacity: 0.9 }).addTo(map);

        function focusFullRoute() {
            map.flyToBounds(routeLine.getBounds(), { padding: [50, 50], duration: 1.2 });
        }

        function focusVessel() {
            map.flyTo(vesselCoords, 8, { duration: 1.4 });
            setTimeout(() => vesselMarker.openPopup(), 1500);
        }

        setTimeout(focusFullRoute, 500);
    </script>

</body>
</html>
