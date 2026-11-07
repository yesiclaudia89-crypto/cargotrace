<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    CargoTrace Visibility Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pemantauan kargo global, status perjalanan, dan deteksi keterlambatan real-time.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider
                    {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-800 border border-purple-200' :
                       (Auth::user()->role === 'operator' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200') }}">
                    Role: {{ Auth::user()->role }}
                </span>
            </div>
        </div>
    </x-slot>

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

            <!-- RECENT SHIPMENTS TABLE (Slide 10) -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Daftar Pengiriman Kargo Terkini</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Memantau status pergerakan dan estimasi kedatangan.</p>
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
                                            <span>Lihat Peta</span>
                                            <span>🗺️</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
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
</x-app-layout>