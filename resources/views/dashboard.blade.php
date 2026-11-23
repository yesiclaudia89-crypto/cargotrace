<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    CargoTrace Visibility Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pemantauan kargo global, status armada kapal, dan deteksi deviasi rute real-time.
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

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 5 METRIC STAT CARDS (Slide 10) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                
                <!-- Total Shipments -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kargo</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalShipments }}</p>
                    </div>
                    <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold text-lg">
                        📦
                    </div>
                </div>

                <!-- In Transit -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Sedang Jalan</p>
                        <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $inTransitCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center font-bold text-lg">
                        🚢
                    </div>
                </div>

                <!-- Delivered -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Telah Tiba</p>
                        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $deliveredCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center font-bold text-lg">
                        ✅
                    </div>
                </div>

                <!-- Delayed -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Terlambat</p>
                        <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $delayedCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center font-bold text-lg">
                        ⚠️
                    </div>
                </div>

                <!-- Open Exceptions -->
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Gangguan Aktif</p>
                        <p class="text-2xl font-extrabold text-purple-600 mt-1">{{ $openExceptionsCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center font-bold text-lg">
                        🚨
                    </div>
                </div>

            </div>

            <!-- LIVE MARITIME FLEET MAP CARD (Interactive Leaflet.js - Slide 9) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 -ml-4.5"></span>
                            <h3 class="font-extrabold text-lg text-slate-900">
                                🗺️ Peta Pemantauan Posisi Armada Maritim (Live Fleet Map)
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Visualisasi posisi kapal kargo aktif, rute pelayaran laut antar-pelabuhan hub, dan koordinat GPS real-time.
                        </p>
                    </div>

                    <!-- Quick Focus Ship Selector -->
                    <div class="flex flex-wrap items-center gap-1.5 text-xs">
                        <button type="button" onclick="resetFleetView()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg border border-slate-300 transition">
                            🔍 Seluruh Armada
                        </button>
                        @foreach ($recentShipments as $s)
                            @if ($s->status === 'IN_TRANSIT')
                                <button type="button" onclick="focusShipment('{{ $s->shipment_number }}')" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold rounded-lg border border-amber-300 transition flex items-center gap-1">
                                    <span>🚢</span>
                                    <span>{{ $s->shipment_number }} (In Transit)</span>
                                </button>
                            @elseif ($s->status === 'DELIVERED')
                                <button type="button" onclick="focusShipment('{{ $s->shipment_number }}')" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold rounded-lg border border-emerald-200 transition">
                                    ✓ {{ $s->shipment_number }}
                                </button>
                            @else
                                <button type="button" onclick="focusShipment('{{ $s->shipment_number }}')" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 font-medium rounded-lg border border-slate-200 transition">
                                    {{ $s->shipment_number }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Leaflet Map Container -->
                <div class="relative">
                    <div id="dashboardFleetMap" style="height: 440px; width: 100%;" class="z-0 bg-slate-100"></div>

                    <!-- Map Overlay Legend -->
                    <div class="absolute bottom-4 left-4 z-10 bg-white/95 backdrop-blur-md px-3.5 py-2.5 rounded-xl border border-slate-200/80 shadow-lg text-[11px] space-y-1 text-slate-700">
                        <div class="font-bold text-slate-900 border-b border-slate-100 pb-1 mb-1">Legenda Peta Maritim:</div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">🚢</span>
                            <span>Kapal Kargo Aktif (In Transit)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">📍</span>
                            <span>Pelabuhan Asal (Origin Port)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-base">🏁</span>
                            <span>Pelabuhan Tujuan (Destination Hub)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-0.5 bg-amber-500 border-b border-dashed border-amber-600"></span>
                            <span>Jalur Lintasan Samudra Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT SHIPMENTS TABLE (Slide 10) -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Daftar Pengiriman Kargo Terkini</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Memantau status pergerakan dan estimasi kedatangan kargo.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold text-xs uppercase tracking-wider border-b border-gray-200">
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
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse ($recentShipments as $shipment)
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <!-- Shipment Number -->
                                    <td class="px-6 py-4">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                            {{ $shipment->shipment_number }}
                                        </a>
                                        <div class="text-xs font-sans text-gray-400 font-normal">
                                            {{ $shipment->cargo_type }}
                                        </div>
                                    </td>

                                    <!-- Carrier & Customer -->
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $shipment->carrier->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $shipment->customer->name ?? '-' }}</div>
                                    </td>

                                    <!-- Route -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 font-medium text-gray-800">
                                            <span>{{ $shipment->origin->city ?? '-' }}</span>
                                            <span class="text-gray-400">➔</span>
                                            <span>{{ $shipment->destination->city ?? '-' }}</span>
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $shipment->origin->code ?? '' }} - {{ $shipment->destination->code ?? '' }}
                                        </div>
                                    </td>

                                    <!-- Lifecycle Status Badge (Slide 6) -->
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'CREATED'    => 'bg-gray-100 text-gray-700 border-gray-200',
                                                'BOOKED'     => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'PICKED_UP'  => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'IN_TRANSIT' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'DELIVERED'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'CANCELLED'  => 'bg-red-50 text-red-700 border-red-200',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClasses[$shipment->status] ?? 'bg-gray-100' }}">
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
                                        <div class="text-xs text-gray-500 font-mono mt-0.5">
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
                                        <div class="text-xs text-gray-500 mt-1">
                                            ETA: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </td>

                                    <!-- Action Button -->
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm transition">
                                            <span>Lihat Peta Lengkap</span>
                                            <span>🗺️</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
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

    <!-- FLEET MAP JAVASCRIPT INITIALIZATION (Slide 9 & 15) -->
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
        document.addEventListener('DOMContentLoaded', function () {
            const fleetData = @json($fleetJsonData);
            const mapContainer = document.getElementById('dashboardFleetMap');

            if (!mapContainer || typeof L === 'undefined') return;

            // Inisialisasi peta berpusat di Laut Jawa / Kepulauan Nusantara
            const fleetMap = L.map('dashboardFleetMap', {
                scrollWheelZoom: true,
                zoomSnap: 0.5,
            }).setView([-2.5, 106.0], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors • CargoTrace Engine',
                maxZoom: 18,
            }).addTo(fleetMap);

            // Custom Vessel Marker Icon (Slide 9)
            const vesselIcon = L.divIcon({
                html: '<div style="font-size:28px; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.4)); animation: pulse 2s infinite; transform: translate(-14px, -14px);">🚢</div>',
                className: 'fleet-vessel-marker',
                iconSize: [28, 28]
            });

            // Port Icon
            const portOriginIcon = L.divIcon({
                html: '<div style="font-size:20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); transform: translate(-10px, -10px);">📍</div>',
                className: 'fleet-origin-marker',
                iconSize: [20, 20]
            });

            const portDestIcon = L.divIcon({
                html: '<div style="font-size:20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); transform: translate(-10px, -10px);">🏁</div>',
                className: 'fleet-dest-marker',
                iconSize: [20, 20]
            });

            const allMarkersGroup = L.featureGroup();
            const shipmentMarkers = {};

            fleetData.forEach(item => {
                if (!item.origin.lat || !item.dest.lat) return;

                const originCoord = [item.origin.lat, item.origin.lng];
                const destCoord = [item.dest.lat, item.dest.lng];
                const currentCoord = (item.current.lat && item.current.lng) 
                    ? [item.current.lat, item.current.lng] 
                    : originCoord;

                // 1. Marker Origin
                const mOrigin = L.marker(originCoord, { icon: portOriginIcon })
                    .bindPopup(`<strong>Pelabuhan Asal:</strong><br>${item.origin.name} (${item.origin.city})`);
                allMarkersGroup.addLayer(mOrigin);

                // 2. Marker Destination
                const mDest = L.marker(destCoord, { icon: portDestIcon })
                    .bindPopup(`<strong>Pelabuhan Tujuan:</strong><br>${item.dest.name} (${item.dest.city})`);
                allMarkersGroup.addLayer(mDest);

                // 3. Polyline Lintasan Laut
                const routePoints = (item.status === 'IN_TRANSIT') 
                    ? [originCoord, currentCoord, destCoord] 
                    : [originCoord, destCoord];

                const routeColor = item.status === 'IN_TRANSIT' ? '#f59e0b' : (item.status === 'DELIVERED' ? '#10b981' : '#3b82f6');

                const polyline = L.polyline(routePoints, {
                    color: routeColor,
                    weight: 3.5,
                    dashArray: '6, 8',
                    opacity: 0.85
                }).addTo(fleetMap);
                allMarkersGroup.addLayer(polyline);

                // 4. Marker Kapal / Posisi Terkini
                let mainMarker;
                if (item.status === 'IN_TRANSIT') {
                    mainMarker = L.marker(currentCoord, { icon: vesselIcon });
                } else if (item.status === 'DELIVERED') {
                    mainMarker = L.marker(destCoord, { icon: portDestIcon });
                } else {
                    mainMarker = L.marker(originCoord, { icon: portOriginIcon });
                }

                const delayBadge = item.is_delayed 
                    ? `<span style="background:#ffe4e6;color:#e11d48;padding:2px 6px;border-radius:4px;font-weight:bold;font-size:10px;">⚠️ Terlambat +${item.delay_hours} Jam</span>` 
                    : `<span style="background:#d1fae5;color:#065f46;padding:2px 6px;border-radius:4px;font-weight:bold;font-size:10px;">✓ Tepat Waktu</span>`;

                const popupHtml = `
                    <div style="font-family: inherit; font-size: 12px; min-width: 200px;">
                        <div style="font-size: 13px; font-weight: bold; color: #1e3a8a; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 6px;">
                            🚢 ${item.number}
                        </div>
                        <div style="margin-bottom: 3px;"><strong>Ekspedisi:</strong> ${item.carrier}</div>
                        <div style="margin-bottom: 3px;"><strong>Rute:</strong> ${item.origin.city} ➔ ${item.dest.city}</div>
                        <div style="margin-bottom: 3px;"><strong>Status:</strong> <span style="font-weight:bold;">${item.status}</span></div>
                        <div style="margin-bottom: 8px;"><strong>Jadwal:</strong> ${delayBadge}</div>
                        <div style="font-size: 11px; color: #475569; margin-bottom: 8px;"><em>${item.current.description}</em></div>
                        <a href="${item.show_url}" style="display: block; text-align: center; background: #2563eb; color: #fff; padding: 6px 10px; border-radius: 6px; font-weight: bold; text-decoration: none; font-size: 11px;">
                            Buka Detail Pelacakan Lengkap ➔
                        </a>
                    </div>
                `;

                mainMarker.bindPopup(popupHtml);
                allMarkersGroup.addLayer(mainMarker);

                shipmentMarkers[item.number] = {
                    marker: mainMarker,
                    coords: currentCoord,
                    bounds: polyline.getBounds()
                };
            });

            allMarkersGroup.addTo(fleetMap);

            if (allMarkersGroup.getLayers().length > 0) {
                fleetMap.fitBounds(allMarkersGroup.getBounds(), { padding: [40, 40] });
            }

            // Expose globally for buttons
            window.resetFleetView = function () {
                if (allMarkersGroup.getLayers().length > 0) {
                    fleetMap.fitBounds(allMarkersGroup.getBounds(), { padding: [40, 40] });
                }
            };

            window.focusShipment = function (shipmentNumber) {
                const target = shipmentMarkers[shipmentNumber];
                if (target) {
                    fleetMap.flyToBounds(target.bounds, { padding: [50, 50], duration: 1.2 });
                    target.marker.openPopup();
                }
            };
        });
    </script>
</x-app-layout>
