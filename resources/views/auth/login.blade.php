<x-guest-layout>
    <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/80 shadow-2xl p-6 sm:p-8 space-y-6">

        <!-- LOGO & HEADING -->
        <div class="text-center space-y-2">
            <a href="/" class="inline-flex items-center gap-2.5 font-black text-2xl text-slate-900 tracking-tight">
                <span class="text-3xl">🚢</span>
                <span>Cargo<span class="text-blue-600">Trace</span></span>
            </a>
            <p class="text-xs text-slate-500">
                Portal Visibilitas & Pelacakan Kargo Global
            </p>
        </div>

        <!-- QUICK FILL DEMO ACCOUNTS (Super berguna untuk dosen & demo!) -->
        <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl space-y-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block text-center">
                ⚡ Masuk Cepat Akun Demo (Klik untuk Isi):
            </span>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="fillAccount('admin@cargotrace.test')" class="px-2 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg border border-purple-200 transition text-center shadow-xs">
                    👑 Admin
                </button>
                <button type="button" onclick="fillAccount('operator@cargotrace.test')" class="px-2 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition text-center shadow-xs">
                    🛠️ Operator
                </button>
                <button type="button" onclick="fillAccount('customer@cargotrace.test')" class="px-2 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-200 transition text-center shadow-xs">
                    👤 Customer
                </button>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- LOGIN FORM -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Email</label>
                <div class="relative">
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email', 'admin@cargotrace.test') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-xs"
                        placeholder="nama@cargotrace.test"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-600 font-medium" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-blue-600 hover:underline" href="{{ route('password.request') }}">
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
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-xs font-mono"
                        placeholder="••••••••"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-600 font-medium" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" name="remember" checked>
                <label for="remember_me" class="ms-2 text-xs text-slate-600 font-medium">Ingat sesi saya di komputer ini</label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                    <span>Masuk ke Sistem</span>
                    <span>➔</span>
                </button>
            </div>
        </form>

        <!-- FOOTER LINKS -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <a href="/" class="hover:text-blue-600 flex items-center gap-1">
                <span>←</span> Kembali ke Beranda
            </a>
            @if (Route::has('register'))
                <div>
                    Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline">Daftar</a>
                </div>
            @endif
        </div>

    </div>

    <!-- Quick Fill Script -->
    <script>
        function fillAccount(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</x-guest-layout>
