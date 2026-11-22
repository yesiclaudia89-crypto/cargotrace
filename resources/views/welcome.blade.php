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

    <!-- Leaflet CSS & JS for Live Interactive Map in Hero -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Scripts & Styles via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between relative selection:bg-blue-600 selection:text-white overflow-x-hidden">

    <!-- Soft Ambient Light Grid & Gradients -->
    <div class="fixed inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:36px_36px] pointer-events-none z-0 opacity-70"></div>
    <div class="fixed -top-40 left-1/4 w-[600px] h-[600px] bg-blue-200/30 rounded-full blur-[140px] pointer-events-none z-0"></div>
    <div class="fixed top-1/2 -right-40 w-[500px] h-[500px] bg-indigo-200/30 rounded-full blur-[140px] pointer-events-none z-0"></div>

    <!-- ==================== TOP NAVIGATION ==================== -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo Brand -->
            <a href="/" class="flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-sky-500 flex items-center justify-center text-2xl text-white shadow-lg shadow-blue-500/25 group-hover:scale-105 transition duration-300">
                    🚢
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-2xl tracking-tight text-slate-900">Cargo<span class="text-blue-600">Trace</span></span>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200 rounded-full">
                            v2.4 Live
                        </span>
                    </div>
                    <span class="hidden sm:block text-[11px] text-slate-500">Global Maritime Visibility & Delay Detection Engine</span>
                </div>
            </a>

            <!-- Center Menu Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="#tracking" class="hover:text-blue-600 transition flex items-center gap-1.5">
                    <span>🗺️</span> Pelacakan Live
                </a>
                <a href="#demo-accounts" class="hover:text-blue-600 transition flex items-center gap-1.5 text-blue-600 font-bold bg-blue-50 px-3 py-1.5 rounded-full border border-blue-200">
                    <span>⚡</span> 1-Klik Akun Dosen
                </a>
                <a href="#fitur" class="hover:text-blue-600 transition">Fitur Arsitektur</a>
                <a href="#state-machine" class="hover:text-blue-600 transition">Siklus Kargo</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-500/20 transition">
                            <span>Masuk Dashboard</span>
                            <span>➔</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-blue-600 px-3 py-2 transition">
                            Log In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- ==================== MAIN HERO SECTION ==================== -->
    <main class="flex-1 relative z-10">

        <!-- HERO CONTAINER (SPLIT 2 COLS ON DESKTOP) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- LEFT COLUMN: VALUE PROPOSITION & QUICK TRACK CONSOLE (7 Cols) -->
                <div class="lg:col-span-6 xl:col-span-7 space-y-6 text-left">
                    
                    <!-- Live Telemetry Pill -->
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-white text-blue-700 border border-blue-200 shadow-sm">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>LIVE AIS MARITIME TRACKING & DELAY ENGINE</span>
                    </div>

                    <!-- Main Catchy Headline -->
                    <h1 class="text-3xl sm:text-5xl xl:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                        Pantau Kargo Lintas Samudra <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-600">
                            Secara Real-Time Tanpa Titik Buta.
                        </span>
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
                        Sistem cerdas pemantauan kontainer maritim: pelacakan GPS Leaflet.js real-time, integrasi cuaca laut Open-Meteo, kalkulasi deviasi jadwal otomatis, serta simulasi invoice Midtrans.
                    </p>

                    <!-- QUICK TRACK SEARCH BOX -->
                    <div id="tracking" class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-xl shadow-slate-200/70 space-y-3">
                        <form action="{{ route('shipments.public-track') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5">
                            <div class="relative flex-1">
                                <input 
                                    id="tracking-input"
                                    type="text" 
                                    name="tracking_number" 
                                    placeholder="Nomor Resi (misal: CT-2026-001)" 
                                    value="CT-2026-001"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-mono text-sm placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition"
                                    required
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-xl shadow-md shadow-blue-500/25 transition flex items-center justify-center gap-2">
                                <span>Lacak Peta</span>
                                <span>➔</span>
                            </button>
                        </form>

                        <!-- Quick Shipment Switcher Chips -->
                        <div class="flex flex-wrap items-center gap-1.5 pt-1 text-xs">
                            <span class="text-slate-500 font-bold mr-1">Uji Cepat:</span>
                            <button type="button" onclick="selectShipmentPreview(0)" id="chip-0" class="chip-btn px-2.5 py-1 rounded-lg bg-blue-600 text-white font-mono text-[11px] font-bold shadow-sm transition">
                                🚢 CT-2026-001 (In Transit • Delay)
                            </button>
                            <button type="button" onclick="selectShipmentPreview(1)" id="chip-1" class="chip-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono text-[11px] font-semibold border border-slate-200 transition">
                                🚢 CT-2026-002 (Delivered)
                            </button>
                            <button type="button" onclick="selectShipmentPreview(2)" id="chip-2" class="chip-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono text-[11px] font-semibold border border-slate-200 transition">
                                🚢 CT-2026-003 (Created)
                            </button>
                        </div>
                    </div>

                    <!-- 4 Trust Bullets -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs text-slate-700 pt-1">
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm font-medium">
                            <span class="text-blue-600 font-bold">✓</span> Leaflet Vector Map
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm font-medium">
                            <span class="text-blue-600 font-bold">✓</span> Open-Meteo Weather
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm font-medium">
                            <span class="text-blue-600 font-bold">✓</span> Midtrans Gateway
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm font-medium">
                            <span class="text-blue-600 font-bold">✓</span> REST API Sanctum
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: INTERACTIVE LIVE MAP & RADAR HERO CARD (5 Cols) -->
                <div class="lg:col-span-6 xl:col-span-5">
                    
                    <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-2xl shadow-slate-200/80 relative overflow-hidden space-y-4">
                        <!-- Top Gradient Accent -->
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500"></div>

                        <!-- Card Header: Vessel Info & Status -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-xl text-blue-600">
                                    ⚓
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-slate-900 text-sm" id="hero-vessel-name">MV Samudera Perkasa</span>
                                        <span class="font-mono text-blue-600 font-bold text-xs" id="hero-shipment-number">(CT-2026-001)</span>
                                    </div>
                                    <span class="text-slate-500 text-[11px]" id="hero-carrier-name">Carrier: Samudera Shipping Line • IMO 9821402</span>
                                </div>
                            </div>
                            <span id="hero-status-badge" class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300 flex items-center gap-1.5 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span id="hero-status-text">IN_TRANSIT</span>
                            </span>
                        </div>

                        <!-- INTERACTIVE LEAFLET MINI-MAP CONTAINER -->
                        <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                            <div id="hero-map" style="height: 280px; width: 100%;"></div>
                            
                            <!-- Floating Map Controls Badge -->
                            <div class="absolute top-2 right-2 z-[400] bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-700 shadow-sm flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Live Leaflet.js Vector</span>
                            </div>
                        </div>

                        <!-- Progress Track & Route Ports -->
                        <div class="space-y-2 pt-1 text-xs">
                            <div class="flex items-center justify-between font-semibold">
                                <div class="flex items-center gap-1.5 text-slate-800">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span id="hero-origin-port">Tanjung Priok (IDJKT)</span>
                                </div>
                                <span class="text-slate-400 font-bold">➔</span>
                                <div class="flex items-center gap-1.5 text-slate-800">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span id="hero-dest-port">Port of Singapore (SGSIN)</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden border border-slate-200">
                                <div id="hero-progress-bar" class="bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500 h-2 rounded-full transition-all duration-500" style="width: 65%"></div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5">
                                <span id="hero-weather-text">Cuaca Laut: 🌧️ 28°C Hujan Ringan (14 kt)</span>
                                <span id="hero-delay-text" class="font-extrabold text-amber-700">Deviasi: +8 Jam</span>
                            </div>
                        </div>

                        <!-- Direct Open Button -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Detail rute pelayaran lengkap</span>
                            <a id="hero-detail-link" href="{{ route('shipments.public-track', 'CT-2026-001') }}" class="font-extrabold text-xs text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                <span>Buka Pelacakan Penuh</span>
                                <span>➔</span>
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- ==============================================
             METRICS COUNTER TICKER BAR (4 STATS)
             ============================================== -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <span class="text-slate-500 text-xs block uppercase tracking-wider font-bold">Maskapai Pelayaran</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1 block font-mono">4 Global</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">Samudera, Meratus, Maersk, Garuda</span>
                </div>
                <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <span class="text-slate-500 text-xs block uppercase tracking-wider font-bold">Pelabuhan Hub</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-blue-600 mt-1 block font-mono">5 Ports</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">Jakarta, Surabaya, Medan, Singapore, Rotterdam</span>
                </div>
                <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <span class="text-slate-500 text-xs block uppercase tracking-wider font-bold">Sensor Cuaca Maritim</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-sky-600 mt-1 block font-mono">Open-Meteo</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">Suhu Laut, Ombak, Kecepatan Angin</span>
                </div>
                <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <span class="text-slate-500 text-xs block uppercase tracking-wider font-bold">Payment Gateway</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1 block font-mono">Midtrans</span>
                    <span class="text-[11px] text-slate-500 mt-1 block">CREATED ➔ BOOKED Auto-Settle</span>
                </div>
            </div>
        </section>


        <!-- ==============================================
             1-CLICK DEMO ACCOUNTS BAR FOR EVALUATOR / DOSEN
             ============================================== -->
        <section id="demo-accounts" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-xl relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 mb-2">
                            <span>⚡</span> AKUN UJI COBA DOSEN & PENGUJI UAS
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900">
                            Akses Cepat Pengujian Role (1-Klik Masuk)
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Klik tombol role di bawah untuk langsung mencoba hak akses masing-masing user tanpa repot mengetik password.
                        </p>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-xs text-slate-500 block">Password seluruh akun:</span>
                        <code class="bg-slate-100 px-3 py-1 rounded-lg text-amber-700 font-mono font-bold text-xs border border-slate-200">password</code>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-8">
                    
                    <!-- Admin Card -->
                    <div class="bg-purple-50/40 border border-purple-200 p-6 rounded-2xl flex flex-col justify-between space-y-4 hover:border-purple-300 transition group shadow-sm">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                                👑
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base">Administrator</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Kontrol penuh sistem: kelola master data Carrier, Pelabuhan, Akun Pengguna, dan Audit Log pengiriman.
                            </p>
                            <div class="font-mono text-xs text-purple-700 font-bold select-all pt-1">
                                admin@cargotrace.test
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="email" value="admin@cargotrace.test">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                                <span>Masuk Sebagai Admin</span>
                                <span>➔</span>
                            </button>
                        </form>
                    </div>

                    <!-- Operator Card -->
                    <div class="bg-blue-50/40 border border-blue-200 p-6 rounded-2xl flex flex-col justify-between space-y-4 hover:border-blue-300 transition group shadow-sm">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                                🛠️
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base">Logistics Operator</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Operasional logistik: input event pelacakan, update koordinat GPS kapal, catat insiden kendala cuaca buruk.
                            </p>
                            <div class="font-mono text-xs text-blue-700 font-bold select-all pt-1">
                                operator@cargotrace.test
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="email" value="operator@cargotrace.test">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                                <span>Masuk Sebagai Operator</span>
                                <span>➔</span>
                            </button>
                        </form>
                    </div>

                    <!-- Customer Card -->
                    <div class="bg-emerald-50/40 border border-emerald-200 p-6 rounded-2xl flex flex-col justify-between space-y-4 hover:border-emerald-300 transition group shadow-sm">
                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                                👤
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-base">Cargo Customer</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Pemilik muatan kargo: pelacakan kargo miliknya saja, cek invoice freight, dan simulasi pembayaran Midtrans.
                            </p>
                            <div class="font-mono text-xs text-emerald-700 font-bold select-all pt-1">
                                customer@cargotrace.test
                            </div>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="email" value="customer@cargotrace.test">
                            <input type="hidden" name="password" value="password">
                            <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
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
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Arsitektur Rekayasa Perangkat Lunak</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-2">
                    Fitur Inti Sesuai Spesifikasi Brief UAS
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-3 leading-relaxed">
                    Dibangun secara terstruktur memenuhi seluruh kriteria penilaian Pemrograman Web 2 (Slide 1–25).
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 1. Interactive Map -->
                <div class="bg-white border border-slate-200 p-7 rounded-2xl shadow-sm hover:shadow-md transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center text-2xl">
                        🗺️
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Peta Interaktif Leaflet.js</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Visualisasi multi-waypoint jalur pelayaran maritim, penanda dinamis posisi kapal dengan interpolasi koordinat GPS, serta kontrol zoom pecahan (<code class="bg-blue-50 text-blue-700 px-1 py-0.5 rounded">zoomSnap: 0.25</code>).
                    </p>
                </div>

                <!-- 2. Deterministic Delay Engine -->
                <div class="bg-white border border-slate-200 p-7 rounded-2xl shadow-sm hover:shadow-md transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-2xl">
                        ⏱️
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Mesin Kalkulasi Keterlambatan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Aturan bisnis deterministik (Slide 9) yang membandingkan jadwal rencana vs estimasi tiba aktual (ETA), mengklasifikasikan status On-Time, Minor, Medium, dan Critical Delay.
                    </p>
                </div>

                <!-- 3. External Weather API -->
                <div class="bg-white border border-slate-200 p-7 rounded-2xl shadow-sm hover:shadow-md transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-2xl">
                        🌦️
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Integrasi Cuaca Maritim Real-Time</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Menggunakan Laravel HTTP Client ke <strong class="text-slate-800">Open-Meteo API</strong> secara otomatis berdasarkan koordinat GPS terkini kapal untuk memantau suhu, kecepatan angin, dan kondisi laut.
                    </p>
                </div>

                <!-- 4. Exception Incident Logging -->
                <div class="bg-white border border-slate-200 p-7 rounded-2xl shadow-sm hover:shadow-md transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center text-2xl">
                        🚨
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Manajemen Gangguan (Exceptions)</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pencatatan insiden operasional seperti <code class="bg-rose-50 text-rose-700 px-1 py-0.5 rounded">WEATHER_DELAY</code>, <code class="bg-rose-50 text-rose-700 px-1 py-0.5 rounded">CUSTOMS_HOLD</code>, dan kepadatan dermaga lengkap dengan deskripsi dampak durasi jam.
                    </p>
                </div>

                <!-- 5. Payment Gateway Simulation -->
                <div class="bg-white border border-slate-200 p-7 rounded-2xl shadow-sm hover:shadow-md transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-2xl">
                        💳
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Simulasi Midtrans Settlement</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Instruksi lisan dosen: simulasi penyelesaian tagihan ongkos angkut freight kargo yang mengubah status order dari <code class="bg-emerald-50 text-emerald-700 px-1 py-0.5 rounded">CREATED/UNPAID</code> menjadi <code class="bg-emerald-50 text-emerald-700 px-1 py-0.5 rounded">BOOKED/PAID</code>.
                    </p>
                </div>

                <!-- 6. REST API & Postman Suite -->
                <div class="bg-white border border-slate-200 p-7 rounded-2xl shadow-sm hover:shadow-md transition space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center text-2xl">
                        🔐
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">REST API & Postman Collection</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Endpoint JSON terproteksi token Laravel Sanctum (Slide 12 & 13) untuk integrasi mitra logistik eksternal, dilengkapi collection siap uji untuk evaluasi dosen.
                    </p>
                </div>

            </div>
        </section>


        <!-- ==============================================
             STATE MACHINE VISUAL LIFECYCLE (SLIDE 6)
             ============================================== -->
        <section id="state-machine" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-lg text-center space-y-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Alur Deterministic State Machine</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">
                        Siklus Hidup Status Pengiriman Kontainer (Slide 6)
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 max-w-xl mx-auto">
                        Status berpindah secara ketat sesuai alur bisnis logistik resmi. Loncatan status tidak sah langsung ditolak sistem.
                    </p>
                </div>

                <!-- Visual Step Pipeline -->
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 pt-4 text-xs font-bold">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-700">
                        <span class="block text-xl mb-1">📝</span>
                        <span class="text-blue-600 block">1. CREATED</span>
                        <span class="text-[10px] text-slate-500 font-normal">Booking Dibuat (Unpaid)</span>
                    </div>
                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900">
                        <span class="block text-xl mb-1">💳</span>
                        <span class="text-blue-700 block">2. BOOKED</span>
                        <span class="text-[10px] text-blue-600 font-normal">Tagihan Lunas (Paid)</span>
                    </div>
                    <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-900">
                        <span class="block text-xl mb-1">📦</span>
                        <span class="text-indigo-700 block">3. PICKED_UP</span>
                        <span class="text-[10px] text-indigo-600 font-normal">Kontainer Dimuat di Truk</span>
                    </div>
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900">
                        <span class="block text-xl mb-1">🚢</span>
                        <span class="text-amber-700 block">4. IN_TRANSIT</span>
                        <span class="text-[10px] text-amber-600 font-normal">Kapal Berlayar di Laut</span>
                    </div>
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900">
                        <span class="block text-xl mb-1">✅</span>
                        <span class="text-emerald-700 block">5. DELIVERED</span>
                        <span class="text-[10px] text-emerald-600 font-normal">Tiba di Pelabuhan Tujuan</span>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ==================== FOOTER ==================== -->
    <footer class="relative z-10 border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🚢</span>
                <span class="font-bold text-slate-800">CargoTrace Enterprise</span>
                <span>— Final Project UAS Pemrograman Web 2</span>
            </div>
            <div class="font-mono text-slate-500 text-[11px]">
                Laravel 12.0 • MariaDB • Leaflet.js • Open-Meteo • Tailwind CSS
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE JAVASCRIPT FOR HERO LEAFLET MAP & LIVE SWITCHER -->
    <script>
        // Sample Shipments Dataset
        const sampleShipments = [
            {
                number: 'CT-2026-001',
                vessel: 'MV Samudera Perkasa',
                carrier: 'Carrier: Samudera Shipping Line • IMO 9821402',
                status: 'IN_TRANSIT',
                statusClass: 'bg-amber-100 text-amber-800 border-amber-300',
                origin: 'Tanjung Priok (IDJKT)',
                dest: 'Port of Singapore (SGSIN)',
                originCoords: [-6.10, 106.88],
                destCoords: [1.29, 103.85],
                vesselCoords: [-3.20, 107.50],
                weather: 'Cuaca Laut: 🌧️ 28°C Hujan Ringan (14 kt)',
                delay: 'Deviasi: +8 Jam (Weather Hold)',
                delayClass: 'text-amber-700',
                progress: '65%'
            },
            {
                number: 'CT-2026-002',
                vessel: 'MV Meratus Java',
                carrier: 'Carrier: Meratus Line • IMO 9718420',
                status: 'DELIVERED',
                statusClass: 'bg-emerald-100 text-emerald-800 border-emerald-300',
                origin: 'Tanjung Perak (IDSBY)',
                dest: 'Port of Rotterdam (NLRTM)',
                originCoords: [-7.20, 112.73],
                destCoords: [51.95, 4.13],
                vesselCoords: [51.95, 4.13],
                weather: 'Cuaca Laut: ☀️ 16°C Cerah Berawan (9 kt)',
                delay: 'Deviasi: 0 Jam (Tepat Waktu)',
                delayClass: 'text-emerald-700',
                progress: '100%'
            },
            {
                number: 'CT-2026-003',
                vessel: 'MV Meratus Spirit',
                carrier: 'Carrier: Meratus Line • IMO 9654109',
                status: 'CREATED',
                statusClass: 'bg-blue-100 text-blue-800 border-blue-300',
                origin: 'Belawan (IDBLW)',
                dest: 'Hamburg Port (DEHAM)',
                originCoords: [3.78, 98.68],
                destCoords: [53.53, 9.98],
                vesselCoords: [3.78, 98.68],
                weather: 'Cuaca Pelabuhan: ⛅ 29°C Berawan (6 kt)',
                delay: 'Menunggu Pembayaran Freight',
                delayClass: 'text-blue-700',
                progress: '5%'
            }
        ];

        // Initialize Hero Map
        let heroMap, routePolyline, vesselMarker, originMarker, destMarker;

        document.addEventListener('DOMContentLoaded', function() {
            // Leaflet initialization
            heroMap = L.map('hero-map', {
                zoomSnap: 0.25,
                zoomDelta: 0.5,
                zoomControl: true,
                attributionControl: false
            }).setView([-3.20, 107.50], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            }).addTo(heroMap);

            // Select initial shipment CT-2026-001
            renderShipmentOnMap(0);
        });

        function renderShipmentOnMap(index) {
            const data = sampleShipments[index];

            // Update Text Elements
            document.getElementById('tracking-input').value = data.number;
            document.getElementById('hero-shipment-number').textContent = '(' + data.number + ')';
            document.getElementById('hero-vessel-name').textContent = data.vessel;
            document.getElementById('hero-carrier-name').textContent = data.carrier;
            document.getElementById('hero-status-text').textContent = data.status;
            document.getElementById('hero-status-badge').className = 'px-2.5 py-1 rounded-full text-[11px] font-extrabold flex items-center gap-1.5 shadow-sm ' + data.statusClass;
            document.getElementById('hero-origin-port').textContent = data.origin;
            document.getElementById('hero-dest-port').textContent = data.dest;
            document.getElementById('hero-weather-text').textContent = data.weather;
            document.getElementById('hero-delay-text').textContent = data.delay;
            document.getElementById('hero-delay-text').className = 'font-extrabold ' + data.delayClass;
            document.getElementById('hero-progress-bar').style.width = data.progress;
            document.getElementById('hero-detail-link').href = '/track/' + data.number;

            // Update Active Chip Styling
            for (let i = 0; i < 3; i++) {
                const chip = document.getElementById('chip-' + i);
                if (i === index) {
                    chip.className = 'chip-btn px-2.5 py-1 rounded-lg bg-blue-600 text-white font-mono text-[11px] font-bold shadow-sm transition';
                } else {
                    chip.className = 'chip-btn px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono text-[11px] font-semibold border border-slate-200 transition';
                }
            }

            // Remove previous map layers if present
            if (originMarker) heroMap.removeLayer(originMarker);
            if (destMarker) heroMap.removeLayer(destMarker);
            if (vesselMarker) heroMap.removeLayer(vesselMarker);
            if (routePolyline) heroMap.removeLayer(routePolyline);

            // Origin Marker
            originMarker = L.marker(data.originCoords).addTo(heroMap).bindPopup('<b>Pelabuhan Asal:</b><br>' + data.origin);

            // Destination Marker
            destMarker = L.marker(data.destCoords).addTo(heroMap).bindPopup('<b>Pelabuhan Tujuan:</b><br>' + data.dest);

            // Custom Vessel Icon
            const vesselIcon = L.divIcon({
                html: '<div style="font-size:26px; filter: drop-shadow(0 2px 5px rgba(0,0,0,0.35)); transform: translate(-13px, -13px);">🚢</div>',
                className: 'hero-vessel-marker',
                iconSize: [26, 26]
            });
            vesselMarker = L.marker(data.vesselCoords, { icon: vesselIcon }).addTo(heroMap).bindPopup('<b>Kapal:</b> ' + data.vessel + '<br><b>Status:</b> ' + data.status);

            // Route Polyline
            const routePoints = [data.originCoords, data.vesselCoords, data.destCoords];
            routePolyline = L.polyline(routePoints, {
                color: '#2563eb',
                weight: 3.5,
                dashArray: '5, 5'
            }).addTo(heroMap);

            // Fit bounds smoothly
            heroMap.fitBounds(routePolyline.getBounds(), { padding: [40, 40] });
        }

        function selectShipmentPreview(index) {
            renderShipmentOnMap(index);
        }
    </script>

</body>
</html>
