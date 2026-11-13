<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 text-slate-800 relative overflow-hidden">

        <!-- Light Decorative Grid & Glows -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none opacity-60"></div>
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-400/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 right-10 w-[500px] h-[500px] bg-indigo-300/15 rounded-full blur-[140px] pointer-events-none"></div>

        <!-- LEFT COLUMN: BRAND INFO (50%) -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 xl:p-16 relative z-10 border-r border-slate-200/80 bg-white/70 backdrop-blur-md">
            
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-2xl text-white shadow-lg shadow-blue-500/25 group-hover:scale-105 transition duration-300">
                        🚢
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-2xl tracking-tight text-slate-900">Cargo<span class="text-blue-600">Trace</span></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">
                                Registration
                            </span>
                        </div>
                        <span class="text-xs text-slate-500">Portal Pemilik Muatan Kargo Internasional</span>
                    </div>
                </a>
            </div>

            <div class="my-auto py-8 max-w-xl space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    <span>👤</span> Akun Pelanggan Logistik (Customer)
                </div>
                <h1 class="text-3xl xl:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Mulai Pantau Pengiriman Kargo & Selesaikan Pembayaran Freight.
                </h1>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Daftar akun customer secara gratis untuk memantau status pesanan kontainer Anda, menerima notifikasi anomali rute pelayaran, dan melakukan pembayaran invoice freight secara online.
                </p>

                <div class="space-y-3 pt-2 text-xs text-slate-700">
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Pelacakan real-time GPS kargo via peta interaktif Leaflet.js</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Notifikasi otomatis perubahan estimasi waktu tiba (ETA)</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold">✓</span>
                        <span>Simulasi pembayaran langsung gateway Midtrans</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 text-xs text-slate-500 flex items-center justify-between">
                <span>Final Project UAS Web Programming 2</span>
                <span class="font-mono text-slate-600">Laravel 12 Breeze</span>
            </div>
        </div>

        <!-- RIGHT COLUMN: REGISTER FORM (50%) -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 sm:px-12 xl:px-16 relative z-10 bg-white lg:bg-transparent">
            <div class="max-w-md w-full mx-auto space-y-6">

                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Pendaftaran Akun
                        </span>
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition flex items-center gap-1">
                            <span>←</span> Sudah Punya Akun? Masuk
                        </a>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Buat Akun Customer Baru
                    </h2>
                    <p class="text-xs text-slate-500">
                        Isi form di bawah ini untuk mendaftarkan akun Customer kargo Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
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
                            class="w-full px-4 py-3 rounded-xl bg-white border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition shadow-sm"
                            placeholder="PT Ekspor Impor Sejahtera"
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Alamat Email
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            class="w-full px-4 py-3 rounded-xl bg-white border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition shadow-sm"
                            placeholder="customer@perusahaan.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Password
                        </label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl bg-white border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition font-mono shadow-sm"
                            placeholder="Minimal 8 karakter"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Konfirmasi Password
                        </label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-4 py-3 rounded-xl bg-white border border-slate-300 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition font-mono shadow-sm"
                            placeholder="Ulangi password di atas"
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-600 font-semibold" />
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-blue-500/25 transition duration-200 flex items-center justify-center gap-2">
                            <span>Daftar Akun Customer</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <div class="pt-4 border-t border-slate-200 text-center text-xs text-slate-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline ml-1">
                        Masuk ke Portal
                    </a>
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>
