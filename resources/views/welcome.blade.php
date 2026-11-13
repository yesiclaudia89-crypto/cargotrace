<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CargoTrace — Global Shipment Visibility & Tracking Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN for Reliable Styling -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between">

    <!-- TOP NAVBAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-3xl">🚢</span>
                <div>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900">Cargo<span class="text-blue-600">Trace</span></span>
                    <span class="hidden sm:inline-block ml-2 px-2 py-0.5 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded">UAS Portfolio</span>
                </div>
            </div>

            <nav class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                            Masuk Dashboard ➔
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-blue-600 px-3 py-2 transition">
                            Log In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center">
            
            <!-- Badge pill -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 mb-6">
                <span>🌐</span> Global Logistics Visibility & Delay Detection Engine
            </div>

            <!-- Big Headline -->
            <h1 class="text-4xl sm:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight max-w-4xl mx-auto">
                Visibilitas Kargo Global & Pelacakan Rute Maritim
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Pantau pergerakan kontainer antar-pelabuhan, hitung estimasi tiba (ETA) otomatis, deteksi keterlambatan cuaca, dan kelola rute peta secara real-time.
            </p>

            <!-- TRACKING SEARCH BOX (Slide 3 & 10) -->
            <div class="mt-10 max-w-xl mx-auto bg-white p-3 rounded-2xl shadow-lg border border-slate-200">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Masukkan Nomor Resi Kargo (misal: CT-2026-001)" 
                        value="CT-2026-001"
                        class="flex-1 px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                        required
                    >
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow transition">
                        Lacak Kargo 🔍
                    </button>
                </form>
                <div class="mt-3 text-xs text-slate-500 flex items-center justify-center gap-2">
                    <span>Contoh Cepat:</span>
                    <a href="{{ route('dashboard') }}" class="font-mono text-blue-600 hover:underline">CT-2026-001</a>
                    <span>•</span>
                    <a href="{{ route('dashboard') }}" class="font-mono text-blue-600 hover:underline">CT-2026-002</a>
                    <span>•</span>
                    <a href="{{ route('dashboard') }}" class="font-mono text-blue-600 hover:underline">CT-2026-003</a>
                </div>
            </div>

            <!-- 4 FEATURE HIGHLIGHTS (Slide 3, 6, 8, 9) -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-4 gap-6 text-left">
                
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-3xl mb-3">🗺️</div>
                    <h3 class="font-bold text-slate-900 text-base">Peta Interaktif</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Visualisasi rute maritim OpenStreetMap & Leaflet.js dengan pin asal, tujuan, dan posisi kapal terkini.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-3xl mb-3">⏱️</div>
                    <h3 class="font-bold text-slate-900 text-base">Deteksi Delay & ETA</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Aturan bisnis deterministik membandingkan jadwal rencana vs estimasi nyata untuk klasifikasi keterlambatan.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-3xl mb-3">🚨</div>
                    <h3 class="font-bold text-slate-900 text-base">Manajemen Gangguan</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Pencatatan kendala operasional: cuaca buruk (Weather Delay), kendala bea cukai, dan kepadatan pelabuhan.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <div class="text-3xl mb-3">💳</div>
                    <h3 class="font-bold text-slate-900 text-base">Payment Gateway</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        Integrasi pembayaran freight invoice otomatis untuk mengubah status kargo dari CREATED menjadi BOOKED.
                    </p>
                </div>

            </div>

            <!-- RECRUITER / DOSEN DEMO ACCOUNTS BOX (Slide 18) -->
            <div class="mt-14 max-w-3xl mx-auto bg-slate-900 text-white rounded-2xl p-6 sm:p-8 text-left shadow-xl">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                    <h3 class="font-bold text-base text-blue-400 uppercase tracking-wider flex items-center gap-2">
                        🔑 Akun Uji Coba (Demo Credentials untuk Dosen / Interview)
                    </h3>
                    <span class="text-xs text-slate-400">Password semua: <code class="bg-slate-800 px-2 py-0.5 rounded text-amber-300">password</code></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="bg-slate-800/80 p-3.5 rounded-lg border border-slate-700">
                        <div class="font-bold text-purple-400 mb-1">👑 Administrator</div>
                        <div class="font-mono text-slate-200 select-all">admin@cargotrace.test</div>
                        <div class="text-slate-400 mt-1">Akses penuh dashboard, carrier, user, audit.</div>
                    </div>
                    <div class="bg-slate-800/80 p-3.5 rounded-lg border border-slate-700">
                        <div class="font-bold text-blue-400 mb-1">🛠️ Logistics Operator</div>
                        <div class="font-mono text-slate-200 select-all">operator@cargotrace.test</div>
                        <div class="text-slate-400 mt-1">Update rute, tracking event, dan kendala.</div>
                    </div>
                    <div class="bg-slate-800/80 p-3.5 rounded-lg border border-slate-700">
                        <div class="font-bold text-emerald-400 mb-1">👤 Customer</div>
                        <div class="font-mono text-slate-200 select-all">customer@cargotrace.test</div>
                        <div class="text-slate-400 mt-1">Lacak kargo miliknya & bayar tagihan.</div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4">
            CargoTrace • Final Project UAS Web Programming 2 • Dibangun dengan Laravel 12, MySQL, Leaflet.js & Tailwind CSS.
        </div>
    </footer>

</body>
</html>