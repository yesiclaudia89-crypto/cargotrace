<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CargoTrace — Global Maritime Shipment Visibility & Delay Engine</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN for 100% Reliable & Beautiful Styles -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#070D18] text-slate-100 font-sans min-h-screen flex flex-col justify-between relative selection:bg-blue-500 selection:text-white overflow-x-hidden">

    <!-- Ambient Glows & Maritime Grid Background -->
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#1e293b12_1px,transparent_1px),linear-gradient(to_bottom,#1e293b12_1px,transparent_1px)] bg-[size:36px_36px] pointer-events-none z-0"></div>
    <div class="fixed -top-40 left-1/4 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="fixed top-1/2 -right-40 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[140px] pointer-events-none z-0"></div>

    <!-- ==================== TOP NAVIGATION ==================== -->
    <header class="sticky top-0 z-50 bg-[#070D18]/85 backdrop-blur-xl border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo Brand -->
            <a href="/" class="flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-2xl shadow-lg shadow-blue-500/25 group-hover:scale-105 transition duration-300">
                    🚢
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-2xl tracking-tight text-white">Cargo<span class="text-blue-400">Trace</span></span>
                        <span class="hidden sm:inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/30 rounded-full">
                            v2.4 Live
                        </span>
                    </div>
                    <span class="hidden sm:block text-[11px] text-slate-400">Global Shipment Visibility & Tracking Platform</span>
                </div>
            </a>

            <!-- Center Menu Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-300">
                <a href="#tracking" class="hover:text-blue-400 transition">Pelacakan Kargo</a>
                <a href="#fitur" class="hover:text-blue-400 transition">Fitur Arsitektur</a>
                <a href="#demo-accounts" class="hover:text-blue-400 transition flex items-center gap-1.5 text-amber-300">
                    <span>⚡</span> Akun Demo Dosen
                </a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/25 transition">
                            <span>Masuk Dashboard</span>
                            <span>➔</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 transition">
                            Log In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/25 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="flex-1 relative z-10">

        <!-- HERO SECTION -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 text-center">
            
            <!-- Live Operational Pill -->
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-slate-900/90 text-blue-300 border border-slate-700/80 shadow-inner mb-8">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>REAL-TIME MARITIME LOGISTICS VISIBILITY & DELAY ENGINE</span>
            </div>

            <!-- Big Main Headline -->
            <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-tight max-w-5xl mx-auto">
                Visibilitas Rute Kargo Maritim & <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-cyan-400">
                    Pelacakan Logistik Global
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-400 max-w-3xl mx-auto leading-relaxed">
                Platform komprehensif pemantauan kontainer lintas samudra: pelacakan koordinat GPS rute kapal via Leaflet.js, integrasi cuaca maritim Open-Meteo, kalkulasi deviasi jadwal deterministik, serta simulasi invoice Midtrans.
            </p>

            <!-- ==============================================
                 INTERACTIVE LIVE TRACKING CONSOLE (HERO BOX)
                 ============================================== -->
            <div id="tracking" class="mt-12 max-w-3xl mx-auto bg-slate-900/90 border border-slate-700/80 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl text-left relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-400"></div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-4 border-b border-slate-800">
                    <div>
                        <h3 class="font-extrabold text-lg text-white flex items-center gap-2">
                            <span>🔍</span> Konsol Pelacakan Kargo Real-Time
                        </h3>
                        <p class="text-xs text-slate-400">Cari nomor resi kargo untuk melihat peta dan estimasi keterlambatan</p>
                    </div>
                    <span class="text-[11px] font-mono text-emerald-400 bg-emerald-950/50 px-2.5 py-1 rounded border border-emerald-800/40">
                        ● AIS Gateway Connected
                    </span>
                </div>

                <!-- Input Search Form -->
                <form action="{{ route('shipments.public-track') }}" method="GET" class="mt-5 flex flex-col sm:flex-row gap-2.5">
                    <div class="relative flex-1">
                        <input 
                            id="tracking-input"
                            type="text" 
                            name="tracking_number" 
                            placeholder="Nomor Resi Kargo (misal: CT-2026-001)" 
                            value="CT-2026-001"
                            class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-950 border border-slate-700 text-white font-mono text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required
                        >
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <button type="submit" class="px-7 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-500/25 transition flex items-center justify-center gap-2">
                        <span>Lacak Sekarang</span>
                        <span>➔</span>
                    </button>
                </form>

                <!-- Quick Samples Pills -->
                <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-slate-400 font-medium">Uji Cepat Kargo:</span>
                    <button type="button" onclick="previewShipment('CT-2026-001', 'MV Samudera Perkasa', 'Tanjung Priok, Jakarta', 'Port of Singapore', 'IN_TRANSIT', '+8 Jam (Weather Hold)', '🌧️ 28°C Hujan Ringan (14 kt)', '65%')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-blue-300 font-mono text-[11px] border border-slate-700 transition">
                        CT-2026-001 (In Transit • Delay)
                    </button>
                    <button type="button" onclick="previewShipment('CT-2026-002', 'MV Meratus Java', 'Tanjung Perak, Surabaya', 'Port of Rotterdam', 'DELIVERED', '0 Jam (Tepat Waktu)', '☀️ 16°C Cerah Berawan', '100%')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-emerald-300 font-mono text-[11px] border border-slate-700 transition">
                        CT-2026-002 (Delivered • On-Time)
                    </button>
                    <button type="button" onclick="previewShipment('CT-2026-003', 'MV Meratus Spirit', 'Belawan, Medan', 'Hamburg Port', 'CREATED', 'Menunggu Pembayaran', '⛅ 29°C Berawan', '0%')" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-amber-300 font-mono text-[11px] border border-slate-700 transition">
                        CT-2026-003 (Created • Unpaid)
                    </button>
                </div>

                <!-- Dynamic Preview Card -->
                <div id="preview-box" class="mt-5 p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-3">
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-800/80">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-blue-400 font-bold" id="prev-number">CT-2026-001</span>
                            <span class="text-slate-400">•</span>
                            <span class="text-white font-medium" id="prev-vessel">MV Samudera Perkasa</span>
                        </div>
                        <span id="prev-status" class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            IN_TRANSIT
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-2 text-slate-300">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span id="prev-origin">Tanjung Priok, Jakarta</span>
                            <span class="text-slate-500">➔</span>
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            <span id="prev-destination">Port of Singapore</span>
                        </div>
                        <div class="text-[11px] text-amber-400 font-semibold" id="prev-delay">
                            Deviasi: +8 Jam (Weather Hold)
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-900 h-2 rounded-full overflow-hidden">
                        <div id="prev-progress" class="bg-gradient-to-r from-blue-500 to-cyan-400 h-2 rounded-full transition-all duration-500" style="width: 65%"></div>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1">
                        <span id="prev-weather">Cuaca Koordinat Kapal: 🌧️ 28°C Hujan Ringan (14 kt)</span>
                        <a id="prev-link" href="{{ route('shipments.public-track', 'CT-2026-001') }}" class="font-bold text-blue-400 hover:text-blue-300 hover:underline">
                            Buka Peta & Tracking Lengkap ➔
                        </a>
                    </div>
                </div>

            </div>

            <!-- FLEET TELEMETRY METRICS TICKER -->
            <div class="mt-14 max-w-5xl mx-auto grid grid-cols-2 sm:grid-cols-4 gap-4 text-left">
                <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl backdrop-blur-sm">
                    <span class="text-slate-400 text-xs block uppercase tracking-wider font-semibold">Armada Kapal</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-white mt-1 block font-mono">4 Global</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">Maersk, Samudera, Meratus, Garuda</span>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl backdrop-blur-sm">
                    <span class="text-slate-400 text-xs block uppercase tracking-wider font-semibold">Pelabuhan Hub</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-blue-400 mt-1 block font-mono">5 Ports</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">JKT, SBY, MDN, SIN, RTM, HAM</span>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl backdrop-blur-sm">
                    <span class="text-slate-400 text-xs block uppercase tracking-wider font-semibold">Sensor Cuaca</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-sky-400 mt-1 block font-mono">Open-Meteo</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">Suhu, Ombak, Angin Maritim</span>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl backdrop-blur-sm">
                    <span class="text-slate-400 text-xs block uppercase tracking-wider font-semibold">Midtrans Ready</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-400 mt-1 block font-mono">Auto-Settle</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">CREATED ➔ BOOKED Instant</span>
                </div>
            </div>

        </section>


        <!-- ==============================================
             1-CLICK DEMO ACCOUNTS BAR FOR EVALUATOR / DOSEN
             ============================================== -->
        <section id="demo-accounts" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-gradient-to-br from-slate-900 via-slate-900/90 to-blue-950/60 border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-800">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-300 border border-amber-500/20 mb-2">
                            <span>⚡</span> AKUN UJI COBA DOSEN & PENGUJI UAS
                        </div>
                        <h2 class="text-2xl font-extrabold text-white">
                            Akses Cepat Pengujian Role (1-Klik Masuk)
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Klik tombol role di bawah untuk langsung mencoba hak akses masing-masing user tanpa repot mengetik password.
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block">Password seluruh akun:</span>
                        <code class="bg-slate-950 px-3 py-1 rounded-lg text-amber-400 font-mono font-bold text-xs border border-slate-800">password</code>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-8">
                    
                    <!-- Admin Card -->
                    <div class="bg-slate-950/70 border border-purple-500/30 p-6 rounded-2xl flex flex-col justify-between space-y-4 hover:border-purple-500/60 transition group">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center text-xl">
                                👑
                            </div>
                            <h3 class="font-bold text-white text-base">Administrator</h3>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Kontrol penuh sistem: kelola master data Carrier, Pelabuhan, Akun Pengguna, dan Audit Log pengiriman.
                            </p>
                            <div class="font-mono text-xs text-purple-300 select-all pt-1">
                                admin@cargotrace.test
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="email" value="admin@cargotrace.test">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                                <span>Masuk Sebagai Admin</span>
                                <span>➔</span>
                            </button>
                        </form>
                    </div>

                    <!-- Operator Card -->
                    <div class="bg-slate-950/70 border border-blue-500/30 p-6 rounded-2xl flex flex-col justify-between space-y-4 hover:border-blue-500/60 transition group">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-300 flex items-center justify-center text-xl">
                                🛠️
                            </div>
                            <h3 class="font-bold text-white text-base">Logistics Operator</h3>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Operasional logistik: input event pelacakan, update koordinat GPS kapal, catat insiden kendala cuaca buruk.
                            </p>
                            <div class="font-mono text-xs text-blue-300 select-all pt-1">
                                operator@cargotrace.test
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="email" value="operator@cargotrace.test">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                                <span>Masuk Sebagai Operator</span>
                                <span>➔</span>
                            </button>
                        </form>
                    </div>

                    <!-- Customer Card -->
                    <div class="bg-slate-950/70 border border-emerald-500/30 p-6 rounded-2xl flex flex-col justify-between space-y-4 hover:border-emerald-500/60 transition group">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-xl">
                                👤
                            </div>
                            <h3 class="font-bold text-white text-base">Cargo Customer</h3>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Pemilik muatan kargo: pelacakan kargo miliknya saja, cek invoice freight, dan simulasi pembayaran Midtrans.
                            </p>
                            <div class="font-mono text-xs text-emerald-300 select-all pt-1">
                                customer@cargotrace.test
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="email" value="customer@cargotrace.test">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                                <span>Masuk Sebagai Customer</span>
                                <span>➔</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </section>


        <!-- ==============================================
             TECHNICAL CAPABILITIES & UAS REQUIREMENTS (6 CARDS)
             ============================================== -->
        <section id="fitur" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-400">Arsitektur Rekayasa Perangkat Lunak</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mt-2">
                    Fitur Inti Sesuai Spesifikasi Brief UAS
                </h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-3 leading-relaxed">
                    Dibangun secara terstruktur memenuhi seluruh kriteria penilaian Pemrograman Web 2 (Slide 1–25).
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 1. Interactive Map -->
                <div class="bg-slate-900/70 border border-slate-800 p-7 rounded-2xl hover:border-slate-700 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 flex items-center justify-center text-2xl">
                        🗺️
                    </div>
                    <h3 class="font-bold text-lg text-white">Peta Interaktif Leaflet.js</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Visualisasi multi-waypoint jalur pelayaran maritim, penanda dinamis posisi kapal dengan interpolasi koordinat GPS, serta kontrol zoom pecahan (<code class="text-blue-300">zoomSnap: 0.25</code>).
                    </p>
                </div>

                <!-- 2. Deterministic Delay Engine -->
                <div class="bg-slate-900/70 border border-slate-800 p-7 rounded-2xl hover:border-slate-700 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center text-2xl">
                        ⏱️
                    </div>
                    <h3 class="font-bold text-lg text-white">Mesin Kalkulasi Keterlambatan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Aturan bisnis deterministik (Slide 9) yang membandingkan jadwal rencana vs estimasi tiba aktual (ETA), mengklasifikasikan status On-Time, Minor, Medium, dan Critical Delay.
                    </p>
                </div>

                <!-- 3. External Weather API -->
                <div class="bg-slate-900/70 border border-slate-800 p-7 rounded-2xl hover:border-slate-700 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center text-2xl">
                        🌦️
                    </div>
                    <h3 class="font-bold text-lg text-white">Integrasi Cuaca Maritim Real-Time</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Menggunakan Laravel HTTP Client ke <strong class="text-slate-200">Open-Meteo API</strong> secara otomatis berdasarkan koordinat GPS terkini kapal untuk memantau suhu, kecepatan angin, dan kondisi laut.
                    </p>
                </div>

                <!-- 4. Exception Incident Logging -->
                <div class="bg-slate-900/70 border border-slate-800 p-7 rounded-2xl hover:border-slate-700 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center text-2xl">
                        🚨
                    </div>
                    <h3 class="font-bold text-lg text-white">Manajemen Gangguan (Exceptions)</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Pencatatan insiden operasional seperti <code class="text-rose-300">WEATHER_DELAY</code>, <code class="text-rose-300">CUSTOMS_HOLD</code>, dan kepadatan dermaga lengkap dengan deskripsi dampak durasi jam.
                    </p>
                </div>

                <!-- 5. Payment Gateway Simulation -->
                <div class="bg-slate-900/70 border border-slate-800 p-7 rounded-2xl hover:border-slate-700 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-2xl">
                        💳
                    </div>
                    <h3 class="font-bold text-lg text-white">Simulasi Midtrans Settlement</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Instruksi lisan dosen: simulasi penyelesaian tagihan ongkos angkut freight kargo yang mengubah status order dari <code class="text-emerald-300">CREATED/UNPAID</code> menjadi <code class="text-emerald-300">BOOKED/PAID</code>.
                    </p>
                </div>

                <!-- 6. REST API & Postman Suite -->
                <div class="bg-slate-900/70 border border-slate-800 p-7 rounded-2xl hover:border-slate-700 transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 flex items-center justify-center text-2xl">
                        🔐
                    </div>
                    <h3 class="font-bold text-lg text-white">REST API & Postman Collection</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Endpoint JSON terproteksi token Laravel Sanctum (Slide 12 & 13) untuk integrasi mitra logistik eksternal, dilengkapi collection siap uji untuk evaluasi dosen.
                    </p>
                </div>

            </div>
        </section>

    </main>

    <!-- ==================== FOOTER ==================== -->
    <footer class="relative z-10 border-t border-slate-800/80 bg-[#060B14] py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🚢</span>
                <span class="font-bold text-slate-300">CargoTrace Enterprise</span>
                <span>— Final Project UAS Pemrograman Web 2</span>
            </div>
            <div class="font-mono text-slate-400 text-[11px]">
                Laravel 12.0 • MariaDB • Leaflet.js • Open-Meteo • Tailwind CSS
            </div>
        </div>
    </footer>

    <!-- Interactive JS for Landing Page Preview -->
    <script>
        function previewShipment(number, vessel, origin, destination, status, delay, weather, progress) {
            document.getElementById('tracking-input').value = number;
            document.getElementById('prev-number').textContent = number;
            document.getElementById('prev-vessel').textContent = vessel;
            document.getElementById('prev-origin').textContent = origin;
            document.getElementById('prev-destination').textContent = destination;
            document.getElementById('prev-status').textContent = status;
            document.getElementById('prev-delay').textContent = 'Deviasi: ' + delay;
            document.getElementById('prev-weather').textContent = 'Cuaca Koordinat Kapal: ' + weather;
            document.getElementById('prev-progress').style.width = progress;
            document.getElementById('prev-link').href = '/track/' + number;

            // Change status badge style
            const statusBadge = document.getElementById('prev-status');
            if (status === 'IN_TRANSIT') {
                statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30';
            } else if (status === 'DELIVERED') {
                statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
            } else {
                statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30';
            }
        }
    </script>

</body>
</html>
