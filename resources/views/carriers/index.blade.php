<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">
            🏢 Kelola Ekspedisi Logistik (Carriers)
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">
            Daftar perusahaan pelayaran & penerbangan kargo yang terdaftar di CargoTrace.
        </p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- 2 COLUMN: LIST (LEFT) & ADD FORM (RIGHT) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- LIST CARRIERS (2 Cols) -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 font-bold text-sm text-slate-800">
                        Daftar Ekspedisi Aktif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3">Kode & Nama Carrier</th>
                                    <th class="px-6 py-3">Moda Transport</th>
                                    <th class="px-6 py-3">Kontak</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse ($carriers as $c)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-slate-900">{{ $c->name }}</span>
                                            <span class="block text-xs font-mono text-blue-600">{{ $c->code }}</span>
                                        </td>
                                        <td class="px-6 py-4 uppercase text-xs font-semibold">
                                            @if ($c->transport_mode === 'sea')
                                                🚢 Laut (Sea)
                                            @elseif ($c->transport_mode === 'air')
                                                ✈️ Udara (Air)
                                            @else
                                                🚚 Darat
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs">
                                            <div>{{ $c->contact_email ?? '-' }}</div>
                                            <div class="text-slate-400 font-mono">{{ $c->contact_phone ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                Aktif
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada carrier terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($carriers->hasPages())
                        <div class="p-4 border-t border-slate-100">
                            {{ $carriers->links() }}
                        </div>
                    @endif
                </div>

                <!-- ADD FORM (1 Col) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm h-fit">
                    <h3 class="font-bold text-slate-900 text-sm pb-3 border-b border-slate-100 mb-4">
                        + Tambah Carrier Baru
                    </h3>
                    <form action="{{ route('carriers.store') }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Perusahaan Ekspedisi:</label>
                            <input type="text" name="name" placeholder="Misal: Evergreen Marine" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kode Unik Carrier:</label>
                            <input type="text" name="code" placeholder="Misal: EMC" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs font-mono uppercase" required>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Moda Utama:</label>
                            <select name="transport_mode" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs" required>
                                <option value="sea">Laut (Sea Freight)</option>
                                <option value="air">Udara (Air Freight)</option>
                                <option value="land">Darat (Trucking)</option>
                                <option value="multimodal">Multimodal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Email Kontak:</label>
                            <input type="email" name="contact_email" placeholder="cargo@carrier.com" class="w-full p-2.5 border border-slate-300 rounded-lg text-xs">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">No. Telepon Kontak:</label>
                            <input type="text" name="contact_phone" placeholder="+6221..." class="w-full p-2.5 border border-slate-300 rounded-lg text-xs">
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow transition">
                            Simpan Carrier ➔
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
