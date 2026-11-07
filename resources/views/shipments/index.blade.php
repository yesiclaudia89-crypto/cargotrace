<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Daftar Pengiriman Kargo (Shipments)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Kelola kargo, pantau status pengiriman, dan akses detail pelacakan rute.
                </p>
            </div>
            @if (!Auth::user()->isCustomer())
                <div>
                    <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                        <span>+</span> Buat Kargo Baru
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
                    <span>✓ {{ session('success') }}</span>
                </div>
            @endif

            <!-- FILTER & SEARCH BAR (Slide 10) -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <form action="{{ route('shipments.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    
                    <!-- Search by Number -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Resi / Kargo</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari CT-..." 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                    </div>

                    <!-- Filter by Status -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Status Kargo</label>
                        <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Semua Status</option>
                            @foreach (['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'CANCELLED'] as $st)
                                <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Carrier -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Ekspedisi (Carrier)</label>
                        <select name="carrier_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Semua Carrier</option>
                            @foreach ($carriers as $c)
                                <option value="{{ $c->id }}" {{ request('carrier_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-lg transition shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('shipments.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-lg transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- SHIPMENTS TABLE -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-slate-50 text-slate-600 font-semibold text-xs uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3">No. Resi & Kargo</th>
                                <th class="px-6 py-3">Carrier / Pengirim</th>
                                <th class="px-6 py-3">Rute (Asal ➔ Tujuan)</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Pembayaran</th>
                                <th class="px-6 py-3">Kondisi Jadwal</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($shipments as $shipment)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                            {{ $shipment->shipment_number }}
                                        </a>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $shipment->cargo_type }} • {{ number_format($shipment->total_weight, 0) }} kg</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $shipment->carrier->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500">{{ $shipment->customer->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-800">
                                            {{ $shipment->origin->city ?? '-' }} ➔ {{ $shipment->destination->city ?? '-' }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ $shipment->origin->name ?? '' }}
                                        </div>
                                    </td>
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
                                    <td class="px-6 py-4">
                                        @if ($shipment->is_delayed)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                ⚠️ +{{ $shipment->delay_hours }} Jam ({{ $shipment->delay_severity }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                ✓ On Schedule
                                            </span>
                                        @endif
                                        <div class="text-xs text-slate-400 mt-1">
                                            ETA: {{ $shipment->estimated_arrival ? $shipment->estimated_arrival->format('d M, H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('shipments.show', $shipment) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-xs rounded-lg transition border border-blue-200">
                                            Detail & Peta ➔
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        Tidak ada pengiriman kargo yang cocok dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($shipments->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $shipments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
