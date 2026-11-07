<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('shipments.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                        ← Kembali ke Daftar Kargo
                    </a>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-900 mt-1 flex items-center gap-2">
                    <span>Pelacakan Kargo:</span>
                    <span class="font-mono text-blue-600">{{ $shipment->shipment_number }}</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $shipment->cargo_type }} • Ekspedisi: <span class="font-semibold text-slate-700">{{ $shipment->carrier->name }}</span>
                </p>
            </div>

            <!-- Status Badges -->
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $statusClasses = [
                        'CREATED'    => 'bg-gray-100 text-gray-700 border-gray-300',
                        'BOOKED'     => 'bg-blue-50 text-blue-700 border-blue-300',
                        'PICKED_UP'  => 'bg-indigo-50 text-indigo-700 border-indigo-300',
                        'IN_TRANSIT' => 'bg-amber-50 text-amber-800 border-amber-300',
                        'DELIVERED'  => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                        'CANCELLED'  => 'bg-red-50 text-red-700 border-red-300',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClasses[$shipment->status] ?? 'bg-gray-100' }}">
                    Status: {{ $shipment->status }}
                </span>

                @if ($shipment->payment_status === 'PAID')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        ✓ LUNAS (PAID)
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 animate-pulse">
                        BELUM DIBAYAR (UNPAID)
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <!-- Leaflet CSS & JS (Slide 9) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- EXCEPTION ALERT BANNER (Slide 8) -->
            @if ($shipment->exceptions->where('status', '!=', 'RESOLVED')->count() > 0)
                @php
                    $activeExc = $shipment->exceptions->where('status', '!=', 'RESOLVED')->first();
                @endphp
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-amber-900 text-sm">GANGGUAN OPERASIONAL AKTIF: {{ $activeExc->exception_type }}</h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-200 text-amber-900">
                                    Severity: {{ $activeExc->severity }}
                                </span>
                            </div>
                            <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                                {{ $activeExc->description }}
                            </p>
                            @if ($activeExc->resolution_notes)
                                <p class="text-xs text-amber-700 mt-1 italic">
                                    Tindakan mitigasi: {{ $activeExc->resolution_notes }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- PAYMENT GATEWAY CALL TO ACTION (Jika UNPAID) -->
            @if ($shipment->payment_status !== 'PAID')
                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 text-white p-6 rounded-2xl shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-semibold bg-amber-400 text-slate-900 mb-2">
                            💳 Tagihan Menunggu Pembayaran
                        </div>
                        <h3 class="text-xl font-bold">Biaya Pengiriman Kargo (Freight Fee)</h3>
                        <p class="text-xs text-blue-200 mt-1">
                            Selesaikan pembayaran untuk mengonfirmasi jadwal kargo dari status <strong class="text-white">CREATED</strong> ke <strong class="text-white">BOOKED</strong>.
                        </p>
                    </div>

                    <div class="text-right flex flex-col sm:flex-row items-center gap-4">
                        <div class="text-left sm:text-right">
                            <span class="text-xs text-blue-300 block">Total Tagihan:</span>
                            <span class="text-2xl font-black text-amber-300 font-mono">
                                Rp {{ number_format($shipment->freight_cost, 0, ',', '.') }}
                            </span>
                        </div>

                        <form action="{{ route('shipments.pay', $shipment) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Simulasi: Lanjutkan pembayaran via Payment Gateway Midtrans Sandbox?')" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-sm rounded-xl shadow-md transition flex items-center gap-2">
                                <span>Bayar Sekarang (Snap Sandbox)</span>
                                <span>➔</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- 2 COLUMN LAYOUT: MAP (LEFT) & TIMELINE (RIGHT) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LEFT: LEAFLET MAP & SHIPMENT SPECS (2 Cols) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- LEAFLET.JS MAP CONTAINER (Slide 9) -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                                    <span>🗺️</span> Peta Pelacakan Rute Maritim
                                </h3>
                                <p class="text-xs text-slate-500">Visualisasi titik pelabuhan asal, tujuan, dan posisi kapal terkini.</p>
                            </div>
                            <span class="text-[11px] text-slate-400">Leaflet.js + OpenStreetMap</span>
                        </div>

                        <!-- MAP DIV -->
                        <div id="map" style="height: 420px; min-height: 420px; width: 100%; border-radius: 12px; border: 1px solid #e2e8f0; z-index: 1;"></div>

                        <!-- Legend below map -->
                        <div class="mt-3 flex flex-wrap items-center justify-between text-xs text-slate-600 px-1">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-emerald-500 rounded-full inline-block"></span> Asal: {{ $shipment->origin->city }}</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-blue-600 rounded-full inline-block"></span> Posisi Terkini</span>
                                <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-rose-500 rounded-full inline-block"></span> Tujuan: {{ $shipment->destination->city }}</span>
                            </div>
                            <span class="text-slate-400 text-[10px]">Attribution: © OpenStreetMap contributors</span>
                        </div>
                    </div>

                    <!-- SHIPMENT SPECIFICATIONS CARD -->
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                            Spesifikasi & Detail Kargo
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                            <div class="p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-500 block">Pemilik Kargo:</span>
                                <strong class="text-slate-900 text-sm">{{ $shipment->customer->name }}</strong>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-500 block">Total Berat:</span>
                                <strong class="text-slate-900 text-sm">{{ number_format($shipment->total_weight, 0) }} kg</strong>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-500 block">Volume:</span>
                                <strong class="text-slate-900 text-sm">{{ $shipment->total_volume ?? '-' }} CBM</strong>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-lg">
                                <span class="text-slate-500 block">Status Keterlambatan:</span>
                                @if ($shipment->is_delayed)
                                    <strong class="text-rose-600 text-sm">+{{ $shipment->delay_hours }} Jam ({{ $shipment->delay_severity }})</strong>
                                @else
                                    <strong class="text-emerald-600 text-sm">On Schedule</strong>
                                @endif
                            </div>
                        </div>

                        <!-- Commodity Items List -->
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <span class="text-xs font-semibold text-slate-700 block mb-2">Item Barang di dalam Kontainer:</span>
                            <div class="space-y-2">
                                @forelse ($shipment->items as $item)
                                    <div class="flex items-center justify-between text-xs p-2 bg-slate-50 rounded border border-slate-200/60">
                                        <span class="font-semibold text-slate-800">{{ $item->item_name }} ({{ $item->quantity }} Unit)</span>
                                        <span class="text-slate-500 font-mono">HS: {{ $item->hs_code ?? 'N/A' }} • {{ number_format($item->weight_kg, 0) }} kg</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic">Belum ada item barang rinci.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- OPERATOR ACTION: TAMBAH EVENT & KENDALA (Slide 5: Operator only) -->
                    @if (!Auth::user()->isCustomer())
                        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
                            <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center justify-between">
                                <span>🛠️ Tindakan Operasional (Operator / Admin)</span>
                                <span class="text-xs font-normal text-slate-400">Pembaruan Riwayat Lapangan</span>
                            </h4>

                            <!-- Form 1: Tambah Tracking Event -->
                            <form action="{{ route('shipments.tracking.store', $shipment) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 space-y-3">
                                    <h5 class="font-bold text-xs text-blue-900 uppercase">Catat Titik Perjalanan (Tracking Event Baru)</h5>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Update Status Kargo (State Machine):</label>
                                            <select name="status" class="w-full p-2 border border-slate-300 rounded-lg text-xs" required>
                                                @foreach (['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'CANCELLED'] as $st)
                                                    <option value="{{ $st }}" {{ $shipment->status === $st ? 'selected' : '' }}>
                                                        {{ $st }} {{ $shipment->status === $st ? '(Saat Ini)' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Jenis Aktivitas (Event Type):</label>
                                            <input type="text" name="event_type" placeholder="Contoh: PORT_ARRIVAL, CUSTOMS_CLEARED" value="IN_TRANSIT_UPDATE" class="w-full p-2 border border-slate-300 rounded-lg text-xs" required>
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Nama Pos / Posisi Saat Ini:</label>
                                            <input type="text" name="location_name" placeholder="Misal: Selat Sunda / Selat Malaka" class="w-full p-2 border border-slate-300 rounded-lg text-xs" required>
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Waktu Kejadian:</label>
                                            <input type="datetime-local" name="event_time" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full p-2 border border-slate-300 rounded-lg text-xs" required>
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Koordinat Latitude (Peta):</label>
                                            <input type="number" step="0.0000001" name="latitude" placeholder="-5.8900" class="w-full p-2 border border-slate-300 rounded-lg text-xs">
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Koordinat Longitude (Peta):</label>
                                            <input type="number" step="0.0000001" name="longitude" placeholder="105.8000" class="w-full p-2 border border-slate-300 rounded-lg text-xs">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-semibold text-slate-700 text-xs mb-1">Catatan Keterangan:</label>
                                        <textarea name="description" rows="2" placeholder="Kondisi pelayaran dan posisi kontainer..." class="w-full p-2 border border-slate-300 rounded-lg text-xs" required></textarea>
                                    </div>

                                    <div>
                                        <label class="block font-semibold text-slate-700 text-xs mb-1">Revisi Perkiraan Tiba / ETA Baru (Jika terlambat):</label>
                                        <input type="datetime-local" name="new_eta" class="w-full p-2 border border-slate-300 rounded-lg text-xs">
                                        <span class="text-[10px] text-slate-500">Sistem akan otomatis menghitung selisih jam keterlambatan (Slide 8).</span>
                                    </div>

                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow transition">
                                        Simpan Tracking Event ➔
                                    </button>
                                </div>
                            </form>

                            <!-- Form 2: Laporkan Gangguan (Exception) -->
                            <form action="{{ route('shipments.exceptions.store', $shipment) }}" method="POST">
                                @csrf
                                <div class="bg-rose-50/50 p-4 rounded-xl border border-rose-100 space-y-3">
                                    <h5 class="font-bold text-xs text-rose-900 uppercase">Laporkan Kendala / Gangguan (Shipment Exception)</h5>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Jenis Kendala (Slide 8):</label>
                                            <select name="exception_type" class="w-full p-2 border border-slate-300 rounded-lg text-xs" required>
                                                <option value="CUSTOMS_HOLD">CUSTOMS_HOLD (Tertahan Bea Cukai)</option>
                                                <option value="WEATHER_DELAY">WEATHER_DELAY (Cuaca Buruk / Ombak)</option>
                                                <option value="PORT_CONGESTION">PORT_CONGESTION (Pelabuhan Penuh)</option>
                                                <option value="DAMAGED">DAMAGED (Kerusakan Kargo)</option>
                                                <option value="MISROUTED">MISROUTED (Salah Jalur)</option>
                                                <option value="DOCUMENTATION_ISSUE">DOCUMENTATION_ISSUE (Dokumen Bermasalah)</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block font-semibold text-slate-700 mb-1">Tingkat Keparahan (Severity):</label>
                                            <select name="severity" class="w-full p-2 border border-slate-300 rounded-lg text-xs" required>
                                                <option value="LOW">LOW (Kecil - Masih bisa jalan)</option>
                                                <option value="MEDIUM" selected>MEDIUM (Sedang - Menunda jadwal)</option>
                                                <option value="HIGH">HIGH (Kritis - Berhenti total)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-semibold text-slate-700 text-xs mb-1">Deskripsi Kendala:</label>
                                        <textarea name="description" rows="2" placeholder="Jelaskan alasan keterlambatan atau kendala yang dihadapi..." class="w-full p-2 border border-slate-300 rounded-lg text-xs" required></textarea>
                                    </div>

                                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow transition">
                                        Kirim Laporan Exception ⚠️
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>

                <!-- RIGHT: CHRONOLOGICAL TRACKING TIMELINE (1 Col) -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-base text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                            <span>⏱️ Garis Waktu Perjalanan</span>
                            <span class="text-xs font-normal text-slate-400">{{ $shipment->trackingEvents->count() }} Peristiwa</span>
                        </h3>

                        <!-- TIMELINE (Slide 7) -->
                        <div class="mt-6 relative pl-6 border-l-2 border-blue-200 space-y-8">
                            @forelse ($shipment->trackingEvents as $ev)
                                <div class="relative">
                                    <!-- Timeline Node Circle -->
                                    <div class="absolute -left-[31px] top-0 w-4 h-4 rounded-full border-2 border-white 
                                        {{ $loop->last ? 'bg-blue-600 ring-4 ring-blue-100' : 'bg-slate-300' }}">
                                    </div>

                                    <!-- Event Header -->
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-slate-800 uppercase tracking-wider">
                                            {{ $ev->event_type }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">
                                            {{ $ev->event_time->format('d M Y, H:i') }}
                                        </span>
                                    </div>

                                    <!-- Location & Status Badge -->
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-blue-700 font-semibold">📍 {{ $ev->location_name }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-mono">{{ $ev->status }}</span>
                                    </div>

                                    <!-- Event Description -->
                                    <p class="text-xs text-slate-600 mt-2 leading-relaxed bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        {{ $ev->description }}
                                    </p>

                                    <!-- Author -->
                                    <div class="text-[10px] text-slate-400 mt-1">
                                        Dicatat oleh: <span class="font-medium text-slate-600">{{ $ev->author->name ?? 'System' }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic">Belum ada catatan perjalanan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- LEAFLET.JS MAP SCRIPT INITIALIZATION (Slide 9) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Koordinat Asal & Tujuan
            const originLat = {{ $shipment->origin->latitude }};
            const originLng = {{ $shipment->origin->longitude }};
            const originName = "{{ $shipment->origin->name }}";

            const destLat = {{ $shipment->destination->latitude }};
            const destLng = {{ $shipment->destination->longitude }};
            const destName = "{{ $shipment->destination->name }}";

            // 2. Posisi Terkini (Event Terbaru yang memiliki koordinat)
            @php
                $latestCoordEvent = $shipment->trackingEvents->filter(fn($e) => !empty($e->latitude) && !empty($e->longitude))->last();
                $currLat = $latestCoordEvent ? $latestCoordEvent->latitude : $shipment->origin->latitude;
                $currLng = $latestCoordEvent ? $latestCoordEvent->longitude : $shipment->origin->longitude;
                $currLocName = $latestCoordEvent ? $latestCoordEvent->location_name : $shipment->origin->name;
            @endphp
            const currLat = {{ $currLat }};
            const currLng = {{ $currLng }};
            const currLocName = "{{ $currLocName }}";

            // Inisialisasi Map
            const map = L.map('map').setView([currLat, currLng], 6);

            // Layer Peta OpenStreetMap (Slide 9: dengan attribution resmi)
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Marker 1: Pelabuhan Asal (Origin)
            const originMarker = L.marker([originLat, originLng]).addTo(map)
                .bindPopup("<b>📍 Pelabuhan Asal (Origin)</b><br>" + originName + "<br><small class='text-slate-500'>Keberangkatan: {{ $shipment->expected_departure->format('d M Y') }}</small>");

            // Marker 2: Pelabuhan Tujuan (Destination)
            const destMarker = L.marker([destLat, destLng]).addTo(map)
                .bindPopup("<b>🏁 Pelabuhan Tujuan (Destination)</b><br>" + destName + "<br><small class='text-slate-500'>Estimasi Tiba: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M Y, H:i') : '-' }}</small>");

            // Marker 3: Posisi Kapal Terkini (Current Location)
            const shipIcon = L.divIcon({
                className: 'custom-ship-marker',
                html: '<div style="background-color: #2563eb; color: white; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">🚢</div>',
                iconSize: [34, 34],
                iconAnchor: [17, 17]
            });

            const currentMarker = L.marker([currLat, currLng], { icon: shipIcon }).addTo(map)
                .bindPopup("<b>🚢 Posisi Terkini Kargo: {{ $shipment->shipment_number }}</b><br>Lokasi: " + currLocName + "<br>Status: <span style='color: #2563eb; font-weight: bold;'>{{ $shipment->status }}</span>")
                .openPopup();

            // Rute Garis Perjalanan (Polyline)
            const routePoints = [
                [originLat, originLng],
                [currLat, currLng],
                [destLat, destLng]
            ];

            const polyline = L.polyline(routePoints, {
                color: '#2563eb',
                weight: 3,
                opacity: 0.8,
                dashArray: '8, 8'
            }).addTo(map);

            // Auto fit kamera peta ke seluruh titik rute
            map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
        });
    </script>
</x-app-layout>
