<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('shipments.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                ← Kembali ke Daftar Kargo
            </a>
        </div>
        <h2 class="font-extrabold text-2xl text-slate-900 mt-1">
            Buat Data Pengiriman Kargo Baru
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">
            Daftarkan pemesanan kargo baru ke dalam sistem logistik.
        </p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">

                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs mb-6">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('shipments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Customer & Carrier -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Customer (Pemilik Barang):</label>
                            <select name="user_id" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Ekspedisi (Carrier):</label>
                            <select name="carrier_id" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                                <option value="">-- Pilih Carrier --</option>
                                @foreach ($carriers as $cr)
                                    <option value="{{ $cr->id }}">{{ $cr->name }} (Mode: {{ strtoupper($cr->transport_mode) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Origin & Destination -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pelabuhan / Lokasi Asal (Origin):</label>
                            <select name="origin_id" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                                <option value="">-- Pilih Asal --</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }}, {{ $loc->country }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pelabuhan / Lokasi Tujuan (Destination):</label>
                            <select name="destination_id" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                                <option value="">-- Pilih Tujuan --</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }}, {{ $loc->country }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Cargo Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kargo:</label>
                            <input type="text" name="cargo_type" placeholder="Misal: Kontainer FCL 20ft" value="General Cargo (FCL 20ft)" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Total Berat (kg):</label>
                            <input type="number" step="0.01" name="total_weight" placeholder="Contoh: 12500" value="12500" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Biaya Ongkos Kargo (Rp):</label>
                            <input type="number" name="freight_cost" placeholder="Biaya IDR" value="18000000" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                        </div>
                    </div>

                    <!-- Schedules -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rencana Berangkat (Departure):</label>
                            <input type="datetime-local" name="expected_departure" value="{{ now()->addDays(1)->format('Y-m-d\TH:i') }}" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Target Tiba (Expected Arrival):</label>
                            <input type="datetime-local" name="expected_arrival" value="{{ now()->addDays(4)->format('Y-m-d\TH:i') }}" class="w-full p-2.5 border border-slate-300 rounded-xl text-sm" required>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('shipments.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow transition">
                            Simpan & Terbitkan Kargo ➔
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
