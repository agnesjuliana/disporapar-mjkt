<x-layouts.auth title="Masuk">
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image: linear-gradient(#6366f1 1px,transparent 1px),linear-gradient(90deg,#6366f1 1px,transparent 1px);background-size:40px 40px"></div>

    <div class="relative z-10 w-full max-w-md fade-in">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 mb-4 shadow-lg shadow-indigo-500/30">
                <i data-lucide="zap" class="w-7 h-7 text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">{{ config('app.name', 'WARTA WILWATIKTA') }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ config('app.description') }}</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-semibold text-white mb-1">Selamat Datang</h2>
            <p class="text-slate-400 text-sm mb-6">Masuk ke akun Anda untuk melanjutkan</p>

            @if (session('status'))
                <div class="flex items-start gap-3 p-3.5 rounded-xl mb-5 bg-green-950 border border-green-800 text-green-300">
                    <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span class="text-sm">{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="flex items-start gap-3 p-3.5 rounded-xl mb-5 bg-red-950 border border-red-800 text-red-300">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span class="text-sm">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4" id="login-form">
                @csrf

                <div>
                    <label class="form-label text-slate-300" for="email">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="email" name="email" id="email" required placeholder="nama@email.com"
                               class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-indigo-500"
                               value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label class="form-label text-slate-300" for="password">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                               class="form-input pl-9 pr-10 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-indigo-500">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-3 flex items-center text-slate-500 hover:text-slate-300">
                            <i data-lucide="eye" class="w-4 h-4" id="password-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="login-btn"
                        class="w-full btn btn-primary py-2.5 justify-center text-sm font-semibold mt-2"
                        style="background:linear-gradient(135deg,#6366f1,#4f46e5);box-shadow:0 4px 15px rgba(99,102,241,.3)">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Masuk ke Sistem
                </button>
            </form>

            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-slate-800"></div>
                <span class="text-slate-600 text-xs">belum punya akun?</span>
                <div class="flex-1 h-px bg-slate-800"></div>
            </div>

            <a href="{{ route('register') }}"
               class="w-full btn justify-center text-sm border border-slate-700 bg-slate-900 text-white hover:bg-slate-800">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Daftar Akun
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            function togglePassword() {
                const input = document.getElementById('password');
                const icon = document.getElementById('password-eye');
                input.type = input.type === 'password' ? 'text' : 'password';
                icon.setAttribute('data-lucide', input.type === 'password' ? 'eye' : 'eye-off');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }

            document.getElementById('login-form').addEventListener('submit', function () {
                const btn = document.getElementById('login-btn');
                btn.disabled = true;
                btn.innerHTML = 'Memproses...';
            });
        </script>
    @endpush
</x-layouts.auth>
