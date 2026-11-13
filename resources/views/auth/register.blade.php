<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#070D18] text-slate-100 relative overflow-hidden">

        <!-- Background Ambient Glows & Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b12_1px,transparent_1px),linear-gradient(to_bottom,#1e293b12_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none"></div>
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-600/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 right-10 w-[500px] h-[500px] bg-indigo-600/15 rounded-full blur-[140px] pointer-events-none"></div>

        <!-- LEFT COLUMN: BRAND INFO (50%) -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 xl:p-16 relative z-10 border-r border-slate-800/80 bg-gradient-to-br from-[#060D1A]/90 via-[#0B1528]/80 to-[#0A192F]/90 backdrop-blur-md">
            
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-2xl shadow-lg shadow-blue-500/30 group-hover:scale-105 transition duration-300">
                        🚢
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-2xl tracking-tight text-white">Cargo<span class="text-blue-400">Trace</span></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                Registration
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Portal Pemilik Muatan Kargo Internasional</span>
                    </div>
                </a>
            </div>

            <div class="my-auto py-8 max-w-xl space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span>👤</span> Akun Pelanggan Logistik (Customer)
                </div>
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Mulai Pantau Pengiriman Kargo & Selesaikan Pembayaran Freight.
                </h1>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Daftar akun customer secara gratis untuk memantau status pesanan kontainer Anda, menerima notifikasi anomali rute pelayaran, dan melakukan pembayaran invoice freight secara online.
                </p>

                <div class="space-y-3 pt-2 text-xs text-slate-300">
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Pelacakan real-time GPS kargo via peta interaktif Leaflet.js</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Notifikasi otomatis perubahan estimasi waktu tiba (ETA)</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Simulasi pembayaran langsung gateway Midtrans</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800/80 text-xs text-slate-500 flex items-center justify-between">
                <span>Final Project UAS Web Programming 2</span>
                <span class="font-mono text-slate-400">Laravel 12 Breeze</span>
            </div>
        </div>

        <!-- RIGHT COLUMN: REGISTER FORM (50%) -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 sm:px-12 xl:px-16 relative z-10">
            <div class="max-w-md w-full mx-auto space-y-6">

                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Pendaftaran Akun
                        </span>
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-400 hover:text-white transition flex items-center gap-1">
                            <span>←</span> Sudah Punya Akun? Masuk
                        </a>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                        Buat Akun Customer Baru
                    </h2>
                    <p class="text-xs text-slate-400">
                        Isi form di bawah ini untuk mendaftarkan akun Customer kargo Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Nama Lengkap / Perusahaan
                        </label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-inner"
                            placeholder="PT Ekspor Impor Sejahtera"
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-400 font-semibold" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Alamat Email
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-inner"
                            placeholder="customer@perusahaan.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-400 font-semibold" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Password
                        </label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono shadow-inner"
                            placeholder="Minimal 8 karakter"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-400 font-semibold" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Konfirmasi Password
                        </label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/90 border border-slate-700/80 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono shadow-inner"
                            placeholder="Ulangi password di atas"
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-400 font-semibold" />
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 transition duration-200 flex items-center justify-center gap-2">
                            <span>Daftar Akun Customer</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <div class="pt-4 border-t border-slate-800 text-center text-xs text-slate-400">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-blue-400 hover:text-blue-300 hover:underline ml-1">
                        Masuk ke Portal
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>
