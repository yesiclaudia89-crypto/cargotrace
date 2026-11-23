<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resi Pengiriman Maritim: {{ $shipment->shipment_number }} — CargoTrace</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .print-card {
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                border-radius: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-sans min-h-screen py-8 px-4 flex flex-col items-center">

    <!-- Action Toolbar (Hidden during print) -->
    <div class="no-print max-w-4xl w-full mb-6 flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-md border border-slate-200">
        <div class="flex items-center gap-2">
            <a href="javascript:history.back()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                ← Kembali
            </a>
            <span class="text-xs text-slate-500 font-medium">Dokumen Bukti Pengiriman Resmi (Bill of Lading)</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl shadow transition flex items-center gap-2">
                <span>🖨️</span>
                <span>Cetak / Simpan PDF (Ctrl+P)</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Maritime Waybill / Bill of Lading Card -->
    <div class="print-card max-w-4xl w-full bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-xl space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between pb-6 border-b-2 border-slate-900 gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xl font-bold">
                        🚢
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Cargo<span class="text-blue-600">Trace</span></h1>
                        <p class="text-[10px] uppercase tracking-wider font-extrabold text-slate-500">Global Maritime Visibility & Freight System</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 pt-2">
                    Dokumen Elektronik Bukti Muat & Pelacakan Kargo Maritim Resmi<br>
                    Sesuai Standar Konvensi Transportasi Kargo Samudra (Bill of Lading)
                </p>
            </div>

            <div class="text-left sm:text-right space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-widest">Nomor Resi / Waybill No.</span>
                <span class="font-mono text-2xl font-black text-blue-600 block">{{ $shipment->shipment_number }}</span>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold {{ $shipment->payment_status === 'PAID' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                    <span>{{ $shipment->payment_status === 'PAID' ? '✓ STATUS: LUNAS (PAID)' : '⚠️ MENUNGGU PEMBAYARAN' }}</span>
                </div>
            </div>
        </div>

        <!-- Shipper, Carrier & Receiver Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs pb-6 border-b border-slate-200">
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Pengirim (Shipper / Customer)</span>
                <strong class="text-sm font-bold text-slate-900 block">{{ $shipment->customer->name }}</strong>
                <span class="text-slate-600 block">{{ $shipment->customer->email }}</span>
                <span class="text-slate-500 block">ID Kustomer: CST-{{ str_pad($shipment->customer->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Maskapai Pelayaran (Carrier)</span>
                <strong class="text-sm font-bold text-slate-900 block">{{ $shipment->carrier->name }}</strong>
                <span class="text-slate-600 block">Kode Maskapai: <span class="font-mono font-bold text-blue-600">{{ $shipment->carrier->code }}</span></span>
                <span class="text-slate-500 block">Kontak: {{ $shipment->carrier->contact_email ?? 'ops@carrier.com' }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Status Operasional Kargo</span>
                <strong class="text-sm font-bold text-slate-900 block">Status: {{ $shipment->status }}</strong>
                <span class="text-slate-600 block">Jadwal: {{ $shipment->is_delayed ? 'Mengalami Deviasi (+'.$shipment->delay_hours.' Jam)' : 'Sesuai Jadwal (On Time)' }}</span>
                <span class="text-slate-500 block">Diterbitkan: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        <!-- Maritime Route Specs -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Lintasan & Pelabuhan Samudra (Voyage Legs)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/50 space-y-1">
                    <span class="text-[10px] font-extrabold uppercase text-emerald-700 block">📍 Pelabuhan Muat Asal (Port of Loading)</span>
                    <strong class="text-base font-extrabold text-slate-900 block">{{ $shipment->origin->name }} ({{ $shipment->origin->code }})</strong>
                    <span class="text-slate-600 block">{{ $shipment->origin->city }}, {{ $shipment->origin->country }}</span>
                    <span class="text-[11px] text-slate-500 font-mono block">Rencana Berangkat: {{ $shipment->expected_departure?->format('d M Y, H:i') ?? '-' }}</span>
                </div>

                <div class="p-4 rounded-2xl border border-rose-200 bg-rose-50/50 space-y-1">
                    <span class="text-[10px] font-extrabold uppercase text-rose-700 block">🏁 Pelabuhan Bongkar Tujuan (Port of Discharge)</span>
                    <strong class="text-base font-extrabold text-slate-900 block">{{ $shipment->destination->name }} ({{ $shipment->destination->code }})</strong>
                    <span class="text-slate-600 block">{{ $shipment->destination->city }}, {{ $shipment->destination->country }}</span>
                    <span class="text-[11px] text-slate-500 font-mono block">Estimasi Tiba (ETA): {{ $shipment->estimated_arrival?->format('d M Y, H:i') ?? ($shipment->expected_arrival?->format('d M Y, H:i') ?? '-') }}</span>
                </div>
            </div>
        </div>

        <!-- Container & Cargo Manifest Table -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Manifest Rincian Kontainer & Muatan</h3>
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-3">Tipe Kontainer</th>
                            <th class="p-3">Muatan Barang</th>
                            <th class="p-3 text-right">Berat (Weight)</th>
                            <th class="p-3 text-right">Volume</th>
                            <th class="p-3 text-right">Biaya Freight</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="p-3 font-semibold text-slate-900">{{ $shipment->cargo_type }}</td>
                            <td class="p-3 text-slate-600">
                                @forelse ($shipment->items as $item)
                                    <div>{{ $item->item_name }} ({{ $item->quantity }} unit)</div>
                                @empty
                                    <div>General Maritime Commodities</div>
                                @endforelse
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-slate-900">{{ number_format($shipment->total_weight ?? $shipment->weight_kg ?? 12000, 0) }} kg</td>
                            <td class="p-3 text-right font-mono text-slate-700">{{ $shipment->total_volume ?? $shipment->volume_cbm ?? 65 }} CBM</td>
                            <td class="p-3 text-right font-mono font-black text-slate-900">Rp {{ number_format($shipment->freight_cost, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
                        <tr>
                            <td colspan="4" class="p-3 text-right text-slate-600">Total Biaya Pengiriman:</td>
                            <td class="p-3 text-right font-mono text-base text-blue-600 font-black">Rp {{ number_format($shipment->freight_cost, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Tracking Milestone Summary -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Riwayat Status Milestone Perjalanan Terkini</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                @forelse ($shipment->trackingEvents->take(4) as $ev)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex justify-between items-center font-bold text-slate-800">
                            <span>{{ $ev->event_type }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $ev->event_time->format('d M H:i') }}</span>
                        </div>
                        <div class="text-[11px] text-blue-600 font-medium mt-0.5">{{ $ev->location_name }}</div>
                        <p class="text-[11px] text-slate-500 mt-1 leading-snug">{{ $ev->description }}</p>
                    </div>
                @empty
                    <div class="col-span-2 p-3 bg-slate-50 rounded-xl text-slate-400 italic">Belum ada riwayat milestone.</div>
                @endforelse
            </div>
        </div>

        <!-- Signatures & Verification Seal -->
        <div class="pt-8 border-t border-slate-200 grid grid-cols-2 sm:grid-cols-3 gap-6 text-xs text-center items-end">
            <div class="space-y-12">
                <span class="text-slate-400 text-[10px] uppercase font-bold block">Pihak Pengirim (Shipper)</span>
                <div>
                    <div class="border-b border-slate-400 w-32 mx-auto"></div>
                    <span class="font-bold text-slate-800 mt-1 block">{{ $shipment->customer->name }}</span>
                </div>
            </div>

            <div class="space-y-2 flex flex-col items-center">
                <div class="w-16 h-16 rounded-full border-2 border-blue-600 border-dashed flex flex-col items-center justify-center text-blue-600">
                    <span class="text-lg">⚓</span>
                    <span class="text-[8px] font-black uppercase tracking-tighter">VERIFIED</span>
                </div>
                <span class="text-[10px] text-slate-400 font-mono">DIGITAL SIGNATURE<br>CARGOTRACE ENGINE</span>
            </div>

            <div class="space-y-12">
                <span class="text-slate-400 text-[10px] uppercase font-bold block">Operator Pelabuhan / Maskapai</span>
                <div>
                    <div class="border-b border-slate-400 w-32 mx-auto"></div>
                    <span class="font-bold text-slate-800 mt-1 block">{{ $shipment->carrier->name }}</span>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
