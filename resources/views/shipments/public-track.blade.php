<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pelacakan Publik: {{ $shipment->shipment_number }} — CargoTrace</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body class="bg-[#070D18] text-slate-100 font-sans min-h-screen flex flex-col justify-between selection:bg-blue-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#070D18]/90 backdrop-blur-xl border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 py-3.5 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-xl shadow-md">
                    🚢
                </div>
                <div>
                    <span class="font-extrabold text-xl text-white">Cargo<span class="text-blue-400">Trace</span></span>
                    <span class="block text-[10px] text-slate-400 uppercase tracking-wider">Public Shipment Tracker</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="/" class="text-xs text-slate-400 hover:text-white px-3 py-2 transition">
                    ← Kembali ke Beranda
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl shadow-md transition">
                    Masuk Portal Internal ➔
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Top Header Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl backdrop-blur-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-400">Hasil Pelacakan Kontainer Publik</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-1 flex items-center gap-3">
                    <span class="font-mono">{{ $shipment->shipment_number }}</span>
                    <span class="text-base text-slate-400 font-normal">({{ $shipment->cargo_type }})</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Carrier: <strong class="text-slate-200">{{ $shipment->carrier->name }}</strong> • Kode: <span class="font-mono text-blue-400">{{ $shipment->carrier->code }}</span>
                </p>
            </div>

            <!-- Status Badge -->
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $statusColors = [
                        'CREATED'    => 'bg-slate-800 text-slate-300 border-slate-700',
                        'BOOKED'     => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                        'PICKED_UP'  => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                        'IN_TRANSIT' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                        'DELIVERED'  => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                        'CANCELLED'  => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                    ];
                @endphp
                <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold border {{ $statusColors[$shipment->status] ?? 'bg-slate-800' }}">
                    Status: {{ $shipment->status }}
                </span>
                @if ($shipment->payment_status === 'PAID')
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        ✓ Lunas
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
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
            <div class="p-4 rounded-2xl bg-amber-950/40 border border-amber-500/40 text-amber-200 flex items-start gap-3">
                <span class="text-xl">⚠️</span>
                <div class="text-xs space-y-0.5">
                    <span class="font-bold text-amber-300 text-sm block">Peringatan Keterlambatan Terdeteksi ({{ $delayStatus }})</span>
                    <p>
                        Kargo mengalami deviasi jadwal sebesar <strong class="text-amber-100">+{{ $delayHours }} Jam</strong> dibandingkan jadwal awal. Estimasi tiba telah disesuaikan secara otomatis.
                    </p>
                </div>
            </div>
        @else
            <div class="p-3.5 rounded-2xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-200 flex items-center gap-3 text-xs">
                <span class="text-lg">✓</span>
                <span><strong>Operasional Sesuai Jadwal:</strong> Kargo berlayar tepat waktu tanpa deviasi keterlambatan signifikan.</span>
            </div>
        @endif

        <!-- Grid 2 Cols: Left Map & Weather, Right Timeline -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT 7 COLS: INTERACTIVE LEAFLET MAP & WEATHER -->
            <div class="lg:col-span-7 space-y-6">

                <!-- LEAFLET MAP CONTAINER -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 shadow-xl space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🗺️</span>
                            <span class="font-bold text-sm text-white">Visualisasi Rute Pelayaran & Posisi Terkini</span>
                        </div>
                        <span class="text-[11px] font-mono text-cyan-400 bg-cyan-950/60 px-2 py-0.5 rounded border border-cyan-800/40">
                            Leaflet.js Vector Engine
                        </span>
                    </div>

                    <!-- Map Focus Toolbar -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span class="text-slate-400 text-[11px]">Fokus:</span>
                        <button type="button" onclick="focusFullRoute()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-blue-300 text-xs border border-slate-700 transition">
                            Seluruh Rute
                        </button>
                        <button type="button" onclick="focusVessel()" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-cyan-300 text-xs border border-slate-700 transition">
                            Posisi Kapal 🚢
                        </button>
                    </div>

                    <!-- Map Box -->
                    <div id="map" class="w-full rounded-2xl overflow-hidden border border-slate-800 shadow-inner z-0" style="height: 420px; min-height: 420px;"></div>

                    <!-- Port Leg Footer -->
                    <div class="grid grid-cols-2 gap-3 text-xs pt-1">
                        <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800">
                            <span class="text-slate-500 text-[10px] uppercase font-bold block">Pelabuhan Asal</span>
                            <span class="font-bold text-emerald-400 mt-0.5 block">{{ $shipment->origin->name }}</span>
                            <span class="text-[11px] text-slate-400">{{ $shipment->origin->country }} ({{ $shipment->origin->code }})</span>
                        </div>
                        <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800">
                            <span class="text-slate-500 text-[10px] uppercase font-bold block">Pelabuhan Tujuan</span>
                            <span class="font-bold text-rose-400 mt-0.5 block">{{ $shipment->destination->name }}</span>
                            <span class="text-[11px] text-slate-400">{{ $shipment->destination->country }} ({{ $shipment->destination->code }})</span>
                        </div>
                    </div>
                </div>

                <!-- LIVE WEATHER CARD (OPEN-METEO) -->
                @if ($weather)
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 shadow-xl space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🌦️</span>
                                <span class="font-bold text-sm text-white">Sensor Cuaca Maritim Terkini (Open-Meteo)</span>
                            </div>
                            <span class="text-[10px] font-mono text-slate-400">Koordinat: {{ number_format($weather['latitude'], 2) }}°, {{ number_format($weather['longitude'], 2) }}°</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-xs">
                            <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800">
                                <span class="text-slate-500 text-[10px] block">Kondisi Laut</span>
                                <span class="font-bold text-sky-300 text-sm mt-0.5 block">{{ $weather['description'] }}</span>
                            </div>
                            <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800">
                                <span class="text-slate-500 text-[10px] block">Suhu Udara</span>
                                <span class="font-bold text-amber-300 text-sm mt-0.5 block">{{ $weather['temperature'] }} °C</span>
                            </div>
                            <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800">
                                <span class="text-slate-500 text-[10px] block">Kecepatan Angin</span>
                                <span class="font-bold text-blue-300 text-sm mt-0.5 block">{{ $weather['windspeed'] }} km/h</span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT 5 COLS: TRACKING TIMELINE & SCHEDULE -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Timeline Card -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <h3 class="font-bold text-sm text-white flex items-center gap-2">
                            <span>⏱️</span> Riwayat Milestone Perjalanan
                        </h3>
                        <span class="text-[11px] font-mono text-slate-400">{{ $shipment->trackingEvents->count() }} Peristiwa</span>
                    </div>

                    <div class="space-y-6 relative pl-6 before:content-[''] before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-800">
                        @forelse ($shipment->trackingEvents as $event)
                            <div class="relative space-y-1">
                                <span class="absolute -left-6 top-1 w-3 h-3 rounded-full bg-blue-500 ring-4 ring-slate-900"></span>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-white">{{ $event->event_type }}</span>
                                    <span class="text-[11px] font-mono text-slate-400">{{ $event->event_time->format('d M H:i') }}</span>
                                </div>
                                <div class="text-xs text-blue-400 font-semibold">{{ $event->location_name }}</div>
                                <p class="text-xs text-slate-400 leading-relaxed">{{ $event->description }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500">Belum ada riwayat event pelacakan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
                    <h3 class="font-bold text-sm text-white flex items-center gap-2 pb-2 border-b border-slate-800">
                        <span>📅</span> Perbandingan Jadwal & ETA
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-800/60">
                            <span class="text-slate-400">Rencana Keberangkatan:</span>
                            <span class="font-mono text-white">{{ $shipment->expected_departure->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800/60">
                            <span class="text-slate-400">Rencana Tiba Awal:</span>
                            <span class="font-mono text-white">{{ $shipment->expected_arrival->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-800/60">
                            <span class="text-slate-400">Estimasi Tiba Dinamis (ETA):</span>
                            <span class="font-mono text-amber-400 font-bold">{{ $shipment->estimated_arrival->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 bg-[#060B14] py-6 text-center text-xs text-slate-500">
        CargoTrace • Public Tracking Engine • Leaflet.js & OpenStreetMap • UAS Web Programming 2
    </footer>

    <!-- Leaflet Setup Script -->
    <script>
        const originCoords = [{{ $shipment->origin->latitude }}, {{ $shipment->origin->longitude }}];
        const destCoords = [{{ $shipment->destination->latitude }}, {{ $shipment->destination->longitude }}];
        
        @php
            $latestCoordEvent = $shipment->trackingEvents->filter(fn ($e) => !empty($e->latitude) && !empty($e->longitude))->last();
            $vesselLat = $latestCoordEvent?->latitude ?? $shipment->origin->latitude;
            $vesselLng = $latestCoordEvent?->longitude ?? $shipment->origin->longitude;
        @endphp
        const vesselCoords = [{{ $vesselLat }}, {{ $vesselLng }}];

        const map = L.map('map', {
            zoomSnap: 0.25,
            zoomDelta: 0.5
        }).setView(vesselCoords, 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Origin Marker
        L.marker(originCoords).addTo(map).bindPopup('<strong>Asal: {{ $shipment->origin->name }}</strong>');

        // Destination Marker
        L.marker(destCoords).addTo(map).bindPopup('<strong>Tujuan: {{ $shipment->destination->name }}</strong>');

        // Vessel Marker (with 🚢 emoji)
        const vesselIcon = L.divIcon({
            html: '<div style="font-size:28px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.5)); transform: translate(-14px, -14px);">🚢</div>',
            className: 'vessel-marker',
            iconSize: [28, 28]
        });
        const vesselMarker = L.marker(vesselCoords, { icon: vesselIcon }).addTo(map).bindPopup('<strong>Kapal: {{ $shipment->carrier->name }}</strong><br>Posisi Terkini');

        // Polyline Route
        const routeCoords = [originCoords, vesselCoords, destCoords];
        const routeLine = L.polyline(routeCoords, { color: '#3b82f6', weight: 4, dashArray: '6, 6' }).addTo(map);

        function focusFullRoute() {
            map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
        }

        function focusVessel() {
            map.setView(vesselCoords, 8);
            vesselMarker.openPopup();
        }

        setTimeout(focusFullRoute, 400);
    </script>

</body>
</html>
