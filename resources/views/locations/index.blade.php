<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
            📍 Kelola Lokasi & Pelabuhan (Locations)
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">
            Daftar pelabuhan, bandara, dan koordinat GPS untuk visualisasi peta maritim Leaflet.js.
        </p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LIST LOCATIONS (2 Cols) -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 font-bold text-sm text-slate-800">
                        Daftar Titik Pelabuhan / Terminal
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3">Nama & Kode</th>
                                    <th class="px-6 py-3">Tipe</th>
                                    <th class="px-6 py-3">Kota / Negara</th>
                                    <th class="px-6 py-3">Koordinat GPS (Map)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse ($locations as $loc)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900">{{ $loc->name }}</span>
                                            <span class="block text-xs font-mono text-blue-600">{{ $loc->code }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-semibold uppercase">
                                            @if ($loc->type === 'seaport')
                                                ⚓ Seaport
                                            @elseif ($loc->type === 'airport')
                                                ✈️ Airport
                                            @else
                                                🏢 Hub
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs">
                                            {{ $loc->city }}, <span class="font-semibold text-slate-800">{{ $loc->country }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-mono text-slate-600">
                                            Lat: {{ $loc->latitude }}<br>
                                            Lng: {{ $loc->longitude }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada lokasi terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($locations->hasPages())
                        <div class="p-4 border-t border-slate-100">
                            {{ $locations->links() }}
                        </div>
                    @endif
                </div>

                <!-- ADD LOCATION FORM (1 Col) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm h-fit">
                    <h3 class="font-bold text-slate-900 text-sm pb-3 border-b border-slate-100 mb-4">
                        + Tambah Lokasi / Pelabuhan
                    </h3>
                    <form action="{{ route('locations.store') }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Lokasi/Pelabuhan:</label>
                            <input type="text" name="name" placeholder="Misal: Pelabuhan Tanjung Perak" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kode Pelabuhan:</label>
                                <input type="text" name="code" placeholder="IDSUB" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs font-mono uppercase" required>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tipe Fasilitas:</label>
                                <select name="type" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                                    <option value="seaport">Pelabuhan Laut</option>
                                    <option value="airport">Bandara Udara</option>
                                    <option value="hub">Logistics Hub</option>
                                    <option value="warehouse">Gudang</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kota:</label>
                                <input type="text" name="city" placeholder="Surabaya" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Negara:</label>
                                <input type="text" name="country" placeholder="Indonesia" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Latitude (Lintang):</label>
                                <input type="number" step="0.0000001" name="latitude" placeholder="-7.2000" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Longitude (Bujur):</label>
                                <input type="number" step="0.0000001" name="longitude" placeholder="112.7300" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow transition">
                            Simpan Lokasi ➔
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
