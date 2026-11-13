<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 text-slate-800 relative overflow-hidden">

        <!-- Light Ambient Decorative Grid & Soft Glows -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none opacity-60"></div>
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-400/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 right-10 w-[500px] h-[500px] bg-indigo-300/15 rounded-full blur-[140px] pointer-events-none"></div>

        <!-- ==========================================
             LEFT COLUMN: MARITIME OPS SHOWCASE (60%)
             ========================================== -->
        <div class="hidden lg:flex lg:w-[58%] xl:w-[62%] flex-col justify-between p-12 xl:p-16 relative z-10 border-r border-slate-200/80 bg-white/70 backdrop-blur-md">
            
            <!-- Top Navigation Brand -->
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-2xl text-white shadow-lg shadow-blue-500/25 group-hover:scale-105 transition duration-300">
                        🚢
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-2xl tracking-tight text-slate-900">Cargo<span class="text-blue-600">Trace</span></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">
                                v2.4 Enterprise
                            </span>
                        </div>
                        <span class="text-xs text-slate-500">Global Maritime Visibility & Delay Detection Engine</span>
                    </div>
                </a>

                <!-- System Operational Live Badge -->
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bold text-emerald-700">Live Telemetry Active</span>
                </div>
            </div>

            <!-- Main Narrative & Live Telemetry Simulator -->
            <div class="my-auto py-8 max-w-2xl space-y-7">
                
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Visibilitas Rute Pelayaran & Kontainer Internasional</span>
                    </div>
                    <h1 class="text-3xl xl:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Pemantauan Kargo Maritim Real-Time Tanpa Titik Buta.
                    </h1>
                    <p class="text-slate-600 text-sm xl:text-base leading-relaxed">
                        Lacak koordinat GPS kapal secara akurat via Leaflet.js, pantau anomali cuaca ekstrem laut secara otomatis, serta kelola pergerakan muatan dalam satu portal logistik terintegrasi.
                    </p>
                </div>

                <!-- LIVE RADAR / SIMULATED VESSEL TELEMETRY CARD (LIGHT THEME) -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xl shadow-slate-200/60 backdrop-blur-xl relative overflow-hidden group hover:border-slate-300 transition">
                    <!-- Top Gradient Line -->
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500"></div>

                    <!-- Vessel Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-lg text-blue-600">
                                ⚓
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-slate-900 text-sm">MV Samudera Perkasa</span>
                                    <span class="font-mono text-blue-600 font-bold bg-blue-50 px-2 py-0.5 rounded border border-blue-200">(CT-2026-001)</span>
                                </div>
                                <span class="text-slate-500 text-[11px]">Carrier: Samudera Shipping Line • IMO 9821402</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-800 border border-amber-300 flex items-center gap-1.5 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            IN_TRANSIT
                        </span>
                    </div>

                    <!-- Voyage Progress Bar & Ports -->
                    <div class="py-4 space-y-2.5">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <div class="flex items-center gap-2 text-slate-800">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></span>
                                <span>Tanjung Priok (IDJKT)</span>
                            </div>
                            <span class="text-sky-700 font-mono text-[11px] bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-200">
                                Posisi: Selat Karimata (-3.20°, 107.50°)
                            </span>
                            <div class="flex items-center gap-2 text-slate-800">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm shadow-rose-500/50"></span>
                                <span>Port of Singapore (SGSIN)</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden p-0.5 border border-slate-200">
                            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500 h-1.5 rounded-full relative" style="width: 65%">
                                <span class="absolute right-0 -top-1 w-3.5 h-3.5 bg-white border border-blue-600 rounded-full shadow-md"></span>
                            </div>
                        </div>
                        <div class="flex justify-between text-[11px] text-slate-500 font-medium">
                            <span>Berangkat: 14 Nov 08:00</span>
                            <span class="text-amber-700 font-bold">Progres Pelayaran: 65%</span>
                            <span>Estimasi Tiba: 16 Nov 22:00</span>
                        </div>
                    </div>

                    <!-- Real-time Live Sensor Metrics Grid (Light Mode) -->
                    <div class="grid grid-cols-3 gap-2.5 pt-3 border-t border-slate-100 text-xs">
                        <div class="bg-amber-50/70 p-2.5 rounded-xl border border-amber-200">
                            <span class="text-amber-800 text-[10px] block font-bold uppercase tracking-wider">Deviasi Jadwal</span>
                            <span class="font-extrabold text-amber-900 text-xs mt-0.5 block">+8 Jam (Medium Delay)</span>
                        </div>
                        <div class="bg-sky-50/70 p-2.5 rounded-xl border border-sky-200">
                            <span class="text-sky-800 text-[10px] block font-bold uppercase tracking-wider">Cuaca Maritim (API)</span>
                            <span class="font-extrabold text-sky-900 text-xs mt-0.5 block">🌧️ Hujan (28°C, 14 kt)</span>
                        </div>
                        <div class="bg-emerald-50/70 p-2.5 rounded-xl border border-emerald-200">
                            <span class="text-emerald-800 text-[10px] block font-bold uppercase tracking-wider">Kondisi Navigasi</span>
                            <span class="font-extrabold text-emerald-900 text-xs mt-0.5 block">Aman & Terkendali</span>
                        </div>
                    </div>
                </div>

                <!-- 4 Tech Architecture Feature Bullets -->
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 text-xs text-slate-700">
                    <div class="flex items-center gap-2 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-blue-600 font-bold">✓</span> Leaflet Vector Map
                    </div>
                    <div class="flex items-center gap-2 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-blue-600 font-bold">✓</span> Open-Meteo Weather
                    </div>
                    <div class="flex items-center gap-2 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-blue-600 font-bold">✓</span> Midtrans Settlement
                    </div>
                    <div class="flex items-center gap-2 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-blue-600 font-bold">✓</span> Sanctum REST API
                    </div>
                </div>

            </div>

            <!-- Left Footer Attribution -->
            <div class="pt-6 border-t border-slate-200 text-xs text-slate-500 flex items-center justify-between">
                <span>Final Project UAS Web Programming 2 • Individual Work</span>
                <span class="font-mono text-slate-600">Laravel 12 • MariaDB • Leaflet.js</span>
            </div>
        </div>

        <!-- ==========================================
             RIGHT COLUMN: AUTHENTICATION COMMAND (40%)
             ========================================== -->
        <div class="w-full lg:w-[42%] xl:w-[38%] flex flex-col justify-center px-6 py-12 sm:px-10 xl:px-14 relative z-10 bg-white lg:bg-transparent">
            
            <div class="max-w-md w-full mx-auto space-y-6">

                <!-- Mobile Brand Header (Tampil di smartphone / layar kecil) -->
                <div class="lg:hidden text-center space-y-2 mb-2">
                    <div class="inline-flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-xl text-white shadow-lg shadow-blue-500/30">
                            🚢
                        </div>
                        <span class="font-extrabold text-2xl tracking-tight text-slate-900">Cargo<span class="text-blue-600">Trace</span></span>
                    </div>
                    <p class="text-xs text-slate-500">Global Logistics Visibility & Delay Detection Engine</p>
                </div>

                <!-- Form Heading & Home Link -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Autentikasi Portal
                        </span>
                        <a href="/" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition flex items-center gap-1">
                            <span>←</span> Beranda Publik
                        </a>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Masuk ke Akun Anda
                    </h2>
                    <p class="text-xs text-slate-500">
                        Gunakan tombol demo cepat di bawah untuk pengujian instan, atau masukkan email & password Anda.
                    </p>
                </div>

                <!-- 1-CLICK DEMO ROLE SELECTOR CARDS (LIGHT MODE) -->
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
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
                            class="role-card p-3 rounded-xl border border-slate-200 bg-white hover:bg-purple-50/60 hover:border-purple-300 text-left transition relative group shadow-sm focus:outline-none"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-lg">👑</span>
                                <span id="badge-admin" class="w-2 h-2 rounded-full bg-purple-600 opacity-0 group-hover:opacity-100 transition"></span>
                            </div>
                            <div class="font-bold text-xs text-slate-900 mt-1.5">Admin</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Full Control</div>
                        </button>

                        <!-- Role: Operator -->
                        <button 
                            type="button" 
                            onclick="selectRole('operator', 'operator@cargotrace.test')" 
                            id="btn-operator" 
                            class="role-card p-3 rounded-xl border border-slate-200 bg-white hover:bg-blue-50/60 hover:border-blue-300 text-left transition relative group shadow-sm focus:outline-none"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-lg">🛠️</span>
                                <span id="badge-operator" class="w-2 h-2 rounded-full bg-blue-600 opacity-0 group-hover:opacity-100 transition"></span>
                            </div>
                            <div class="font-bold text-xs text-slate-900 mt-1.5">Operator</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Logistik & Rute</div>
                        </button>

                        <!-- Role: Customer -->
                        <button 
                            type="button" 
                            onclick="selectRole('customer', 'customer@cargotrace.test')" 
                            id="btn-customer" 
                            class="role-card p-3 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50/60 hover:border-emerald-300 text-left transition relative group shadow-sm focus:outline-none"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-lg">👤</span>
                                <span id="badge-customer" class="w-2 h-2 rounded-full bg-emerald-600 opacity-0 group-hover:opacity-100 transition"></span>
                            </div>
                            <div class="font-bold text-xs text-slate-900 mt-1.5">Customer</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Lacak & Bayar</div>
                        </button>

                    </div>
                </div>

                <!-- Active Role Info Banner (Light Mode) -->
                <div id="role-description-box" class="p-2.5 rounded-xl bg-blue-50/80 border border-blue-200 text-xs flex items-center justify-between text-slate-700">
                    <div class="flex items-center gap-2">
                        <span id="role-badge-icon">👑</span>
                        <span id="role-description-text" class="font-medium">Mode Admin: Akses penuh dashboard, carrier, user & log sistem.</span>
                    </div>
                </div>

                <!-- Session Status / Errors -->
                <x-auth-session-status class="mb-2" :status="session('status')" />

                <!-- LOGIN FORM -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4 pt-1">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
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
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition shadow-sm"
                                placeholder="name@cargotrace.test"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition hover:underline" href="{{ route('password.request') }}">
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
                                class="w-full pl-10 pr-10 py-3 rounded-xl bg-white border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition font-mono shadow-sm"
                                placeholder="••••••••"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 text-sm focus:outline-none">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" name="remember" checked>
                            <span class="ms-2 text-xs text-slate-600 font-medium">Ingat saya di komputer ini</span>
                        </label>
                    </div>

                    <!-- Submit CTA Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 transition duration-200 flex items-center justify-center gap-2 group">
                            <span>Masuk ke Dashboard Sistem</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <!-- Registration Footer -->
                <div class="pt-6 border-t border-slate-200 text-center text-xs text-slate-500">
                    Belum memiliki akun customer?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline ml-1">
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
                btn.classList.remove('ring-2', 'ring-purple-600', 'ring-blue-600', 'ring-emerald-600', 'bg-purple-50', 'bg-blue-50', 'bg-emerald-50', 'border-purple-300', 'border-blue-300', 'border-emerald-300');
                btn.classList.add('bg-white', 'border-slate-200');
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
            const descBox = document.getElementById('role-description-box');
            const activeCard = document.getElementById('btn-' + role);

            if (role === 'admin') {
                if (activeCard) {
                    activeCard.classList.remove('bg-white', 'border-slate-200');
                    activeCard.classList.add('ring-2', 'ring-purple-600', 'bg-purple-50', 'border-purple-300');
                }
                if (badgeAdmin) badgeAdmin.classList.remove('opacity-0');
                if (descText) descText.textContent = 'Mode Admin: Akses penuh dashboard, carrier, user, port & audit sistem.';
                if (descIcon) descIcon.textContent = '👑';
                if (descBox) descBox.className = 'p-2.5 rounded-xl bg-purple-50 border border-purple-200 text-xs flex items-center justify-between text-purple-900';
            } else if (role === 'operator') {
                if (activeCard) {
                    activeCard.classList.remove('bg-white', 'border-slate-200');
                    activeCard.classList.add('ring-2', 'ring-blue-600', 'bg-blue-50', 'border-blue-300');
                }
                if (badgeOperator) badgeOperator.classList.remove('opacity-0');
                if (descText) descText.textContent = 'Mode Operator: Update status kargo, input event rute, & catat kendala cuaca.';
                if (descIcon) descIcon.textContent = '🛠️';
                if (descBox) descBox.className = 'p-2.5 rounded-xl bg-blue-50 border border-blue-200 text-xs flex items-center justify-between text-blue-900';
            } else if (role === 'customer') {
                if (activeCard) {
                    activeCard.classList.remove('bg-white', 'border-slate-200');
                    activeCard.classList.add('ring-2', 'ring-emerald-600', 'bg-emerald-50', 'border-emerald-300');
                }
                if (badgeCustomer) badgeCustomer.classList.remove('opacity-0');
                if (descText) descText.textContent = 'Mode Customer: Pelacakan kargo pribadi, pantau ETA, & simulasi bayar freight.';
                if (descIcon) descIcon.textContent = '👤';
                if (descBox) descBox.className = 'p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs flex items-center justify-between text-emerald-900';
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
