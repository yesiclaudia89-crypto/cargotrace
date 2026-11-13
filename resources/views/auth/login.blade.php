<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#070D18] text-slate-100 relative overflow-hidden">

        <!-- Background Ambient Glows & Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b12_1px,transparent_1px),linear-gradient(to_bottom,#1e293b12_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none"></div>
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-600/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 right-10 w-[500px] h-[500px] bg-indigo-600/15 rounded-full blur-[140px] pointer-events-none"></div>

        <!-- ==========================================
             LEFT COLUMN: MARITIME OPS SHOWCASE (60%)
             ========================================== -->
        <div class="hidden lg:flex lg:w-[58%] xl:w-[62%] flex-col justify-between p-12 xl:p-16 relative z-10 border-r border-slate-800/80 bg-gradient-to-br from-[#060D1A]/90 via-[#0B1528]/80 to-[#0A192F]/90 backdrop-blur-md">
            
            <!-- Top Navigation Brand -->
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-2xl shadow-lg shadow-blue-500/30 group-hover:scale-105 transition duration-300">
                        🚢
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-2xl tracking-tight text-white">Cargo<span class="text-blue-400">Trace</span></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                v2.4 Enterprise
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Global Maritime Visibility & Delay Detection Engine</span>
                    </div>
                </a>

                <!-- System Operational Live Badge -->
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/80 border border-slate-700/80 text-xs text-slate-300 shadow-inner">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium text-emerald-300">Live Telemetry Active</span>
                </div>
            </div>

            <!-- Main Narrative & Live Telemetry Simulator -->
            <div class="my-auto py-8 max-w-2xl space-y-7">
                
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Visibilitas Rute Pelayaran & Kontainer Internasional</span>
                    </div>
                    <h1 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Pemantauan Kargo Maritim Real-Time Tanpa Titik Buta.
                    </h1>
                    <p class="text-slate-400 text-sm xl:text-base leading-relaxed">
                        Lacak koordinat GPS kapal secara akurat via Leaflet.js, pantau anomali cuaca ekstrem laut secara otomatis, serta kelola pergerakan muatan dalam satu portal logistik terintegrasi.
                    </p>
                </div>

                <!-- LIVE RADAR / SIMULATED VESSEL TELEMETRY CARD -->
                <div class="bg-slate-900/90 border border-slate-700/70 rounded-2xl p-6 shadow-2xl backdrop-blur-xl relative overflow-hidden group hover:border-slate-600 transition">
                    <!-- Subtle top gradient accent -->
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-400"></div>

                    <!-- Vessel Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-800 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-base">
                                ⚓
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-white text-sm">MV Samudera Perkasa</span>
                                    <span class="font-mono text-blue-400 font-semibold">(CT-2026-001)</span>
                                </div>
                                <span class="text-slate-400 text-[11px]">Carrier: Samudera Shipping Line • IMO 9821402</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center gap-1.5 animate-pulse">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            IN_TRANSIT
                        </span>
                    </div>

                    <!-- Voyage Progress Bar & Ports -->
                    <div class="py-4 space-y-2">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <div class="flex items-center gap-2 text-slate-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-sm shadow-emerald-400/50"></span>
                                <span>Tanjung Priok (IDJKT)</span>
                            </div>
                            <span class="text-cyan-400 font-mono text-[11px] bg-cyan-950/40 px-2 py-0.5 rounded border border-cyan-800/40">
                                Posisi: Selat Karimata (-3.20°, 107.50°)
                            </span>
                            <div class="flex items-center gap-2 text-slate-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-400 shadow-sm shadow-rose-400/50"></span>
                                <span>Port of Singapore (SGSIN)</span>
                            </div>
                        </div>

                        <!-- Animated Progress Track -->
                        <div class="w-full bg-slate-950 h-2.5 rounded-full overflow-hidden p-0.5 border border-slate-800">
                            <div class="bg-gradient-to-r from-blue-500 via-indigo-400 to-cyan-400 h-1.5 rounded-full relative" style="width: 65%">
                                <span class="absolute right-0 -top-1 w-3 h-3 bg-white rounded-full shadow-md shadow-cyan-400"></span>
                            </div>
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-500">
                            <span>Berangkat: 14 Nov 08:00</span>
                            <span class="text-amber-400 font-semibold">Progres Pelayaran: 65%</span>
                            <span>Estimasi Tiba: 16 Nov 22:00</span>
                        </div>
                    </div>

                    <!-- Real-time Live Sensor Metrics Grid -->
                    <div class="grid grid-cols-3 gap-2.5 pt-3 border-t border-slate-800/80 text-xs">
                        <div class="bg-slate-950/60 p-2.5 rounded-xl border border-slate-800/60">
                            <span class="text-slate-400 text-[10px] block font-medium uppercase tracking-wider">Deviasi Jadwal</span>
                            <span class="font-bold text-amber-400 text-xs mt-0.5 block">+8 Jam (Medium Delay)</span>
                        </div>
                        <div class="bg-slate-950/60 p-2.5 rounded-xl border border-slate-800/60">
                            <span class="text-slate-400 text-[10px] block font-medium uppercase tracking-wider">Cuaca Maritim (API)</span>
                            <span class="font-bold text-sky-400 text-xs mt-0.5 block">🌧️ Hujan (28°C, 14 kt)</span>
                        </div>
                        <div class="bg-slate-950/60 p-2.5 rounded-xl border border-slate-800/60">
                            <span class="text-slate-400 text-[10px] block font-medium uppercase tracking-wider">Kondisi Navigasi</span>
                            <span class="font-bold text-emerald-400 text-xs mt-0.5 block">Aman & Terkendali</span>
                        </div>
                    </div>
                </div>

                <!-- 4 Tech Architecture Feature Bullets -->
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 text-xs text-slate-300">
                    <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800/50">
                        <span class="text-blue-400 font-bold">✓</span> Leaflet Vector Map
                    </div>
                    <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800/50">
                        <span class="text-blue-400 font-bold">✓</span> Open-Meteo Weather
                    </div>
                    <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800/50">
                        <span class="text-blue-400 font-bold">✓</span> Midtrans Settlement
                    </div>
                    <div class="flex items-center gap-2 bg-slate-900/50 p-2 rounded-lg border border-slate-800/50">
                        <span class="text-blue-400 font-bold">✓</span> Sanctum REST API
                    </div>
                </div>

            </div>

            <!-- Left Footer Attribution -->
            <div class="pt-6 border-t border-slate-800/80 text-xs text-slate-500 flex items-center justify-between">
                <span>Final Project UAS Web Programming 2 • Individual Work</span>
                <span class="font-mono text-slate-400">Laravel 12 • MariaDB • Leaflet.js</span>
            </div>
        </div>

        <!-- ==========================================
             RIGHT COLUMN: AUTHENTICATION COMMAND (40%)
             ========================================== -->
        <div class="w-full lg:w-[42%] xl:w-[38%] flex flex-col justify-center px-6 py-12 sm:px-10 xl:px-14 relative z-10">
            
            <div class="max-w-md w-full mx-auto space-y-6">

                <!-- Mobile Brand Header (Tampil di smartphone / layar kecil) -->
                <div class="lg:hidden text-center space-y-2 mb-2">
                    <div class="inline-flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-xl shadow-lg shadow-blue-500/30">
                            🚢
                        </div>
                        <span class="font-extrabold text-2xl tracking-tight text-white">Cargo<span class="text-blue-400">Trace</span></span>
                    </div>
                    <p class="text-xs text-slate-400">Global Logistics Visibility & Delay Detection Engine</p>
                </div>

                <!-- Form Heading & Home Link -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Autentikasi Portal
                        </span>
                        <a href="/" class="text-xs font-semibold text-slate-400 hover:text-white transition flex items-center gap-1">
                            <span>←</span> Beranda Publik
                        </a>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-xs text-slate-400">
                        Gunakan tombol demo cepat di bawah untuk pengujian instan, atau masukkan email & password Anda.
                    </p>
                </div>

                <!-- 1-CLICK DEMO ROLE SELECTOR CARDS -->
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                            Pilih Akun Demo (1-Klik Otomatis Isi):
                        </label>
                        <span class="text-[10px] text-slate-500 font-mono">Password: password</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2.5">
                        
                        <!-- Role: Admin -->
                        <button 
                            type="button" 
                            onclick="selectRole('admin', 'admin@cargotrace.test')" 
                            id="btn-admin" 
                            class="role-card p-3 rounded-xl border border-slate-800 bg-slate-900/80 hover:bg-slate-800/90 text-left transition relative group focus:outline-none"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-lg">👑</span>
                                <span id="badge-admin" class="w-2 h-2 rounded-full bg-purple-400 opacity-0 group-hover:opacity-100 transition"></span>
                            </div>
                            <div class="font-bold text-xs text-white mt-1.5">Admin</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 leading-tight">Full Control</div>
                        </button>

                        <!-- Role: Operator -->
                        <button 
                            type="button" 
                            onclick="selectRole('operator', 'operator@cargotrace.test')" 
                            id="btn-operator" 
                            class="role-card p-3 rounded-xl border border-slate-800 bg-slate-900/80 hover:bg-slate-800/90 text-left transition relative group focus:outline-none"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-lg">🛠️</span>
                                <span id="badge-operator" class="w-2 h-2 rounded-full bg-blue-400 opacity-0 group-hover:opacity-100 transition"></span>
                            </div>
                            <div class="font-bold text-xs text-white mt-1.5">Operator</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 leading-tight">Logistik & Rute</div>
                        </button>

                        <!-- Role: Customer -->
                        <button 
                            type="button" 
                            onclick="selectRole('customer', 'customer@cargotrace.test')" 
                            id="btn-customer" 
                            class="role-card p-3 rounded-xl border border-slate-800 bg-slate-900/80 hover:bg-slate-800/90 text-left transition relative group focus:outline-none"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-lg">👤</span>
                                <span id="badge-customer" class="w-2 h-2 rounded-full bg-emerald-400 opacity-0 group-hover:opacity-100 transition"></span>
                            </div>
                            <div class="font-bold text-xs text-white mt-1.5">Customer</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 leading-tight">Lacak & Bayar</div>
                        </button>

                    </div>
                </div>

                <!-- Active Role Info Banner -->
                <div id="role-description-box" class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800 text-xs flex items-center justify-between text-slate-300">
                    <div class="flex items-center gap-2">
                        <span id="role-badge-icon">👑</span>
                        <span id="role-description-text">Mode Admin: Akses penuh dashboard, carrier, user & log sistem.</span>
                    </div>
                </div>

                <!-- Session Status / Errors -->
                <x-auth-session-status class="mb-2" :status="session('status')" />

                <!-- LOGIN FORM -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4 pt-1">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email', 'admin@cargotrace.test') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-inner"
                                placeholder="name@cargotrace.test"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400 font-semibold" />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-blue-400 hover:text-blue-300 transition hover:underline" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                value="password"
                                required 
                                autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono shadow-inner"
                                placeholder="••••••••"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200 text-sm focus:outline-none">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400 font-semibold" />
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-blue-600 focus:ring-blue-500 cursor-pointer" name="remember" checked>
                            <span class="ms-2 text-xs text-slate-300 font-medium">Ingat saya di komputer ini</span>
                        </label>
                    </div>

                    <!-- Submit CTA Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 transition duration-200 flex items-center justify-center gap-2 group">
                            <span>Masuk ke Dashboard Sistem</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <!-- Registration Footer -->
                <div class="pt-6 border-t border-slate-800 text-center text-xs text-slate-400">
                    Belum memiliki akun customer?
                    <a href="{{ route('register') }}" class="font-bold text-blue-400 hover:text-blue-300 hover:underline ml-1">
                        Daftar Sebagai Customer
                    </a>
                </div>

            </div>

        </div>

    </div>

    <!-- Interactive Script for 1-Click Role Switcher & Password Visibility -->
    <script>
        function selectRole(role, email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';

            // Reset all role buttons styling
            document.querySelectorAll('.role-card').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-purple-500', 'ring-blue-500', 'ring-emerald-500', 'bg-slate-800');
                btn.classList.add('bg-slate-900/80');
            });

            // Hide all dots
            const badgeAdmin = document.getElementById('badge-admin');
            const badgeOperator = document.getElementById('badge-operator');
            const badgeCustomer = document.getElementById('badge-customer');
            if (badgeAdmin) badgeAdmin.classList.add('opacity-0');
            if (badgeOperator) badgeOperator.classList.add('opacity-0');
            if (badgeCustomer) badgeCustomer.classList.add('opacity-0');

            const descText = document.getElementById('role-description-text');
            const descIcon = document.getElementById('role-badge-icon');
            const activeCard = document.getElementById('btn-' + role);

            if (role === 'admin') {
                if (activeCard) activeCard.classList.add('ring-2', 'ring-purple-500', 'bg-slate-800');
                if (badgeAdmin) badgeAdmin.classList.remove('opacity-0');
                if (descText) descText.textContent = 'Mode Admin: Akses penuh dashboard, carrier, user, port & audit sistem.';
                if (descIcon) descIcon.textContent = '👑';
            } else if (role === 'operator') {
                if (activeCard) activeCard.classList.add('ring-2', 'ring-blue-500', 'bg-slate-800');
                if (badgeOperator) badgeOperator.classList.remove('opacity-0');
                if (descText) descText.textContent = 'Mode Operator: Update status kargo, input event rute, & catat kendala cuaca.';
                if (descIcon) descIcon.textContent = '🛠️';
            } else if (role === 'customer') {
                if (activeCard) activeCard.classList.add('ring-2', 'ring-emerald-500', 'bg-slate-800');
                if (badgeCustomer) badgeCustomer.classList.remove('opacity-0');
                if (descText) descText.textContent = 'Mode Customer: Pelacakan kargo pribadi, pantau ETA, & simulasi bayar freight.';
                if (descIcon) descIcon.textContent = '👤';
            }
        }

        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
            } else {
                passInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        // Initialize default active role highlight
        document.addEventListener('DOMContentLoaded', () => {
            selectRole('admin', 'admin@cargotrace.test');
        });
    </script>
</x-guest-layout>
