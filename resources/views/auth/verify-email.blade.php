<x-layouts.auth title="Verifikasi Email">
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image: linear-gradient(#c2410c 1px,transparent 1px),linear-gradient(90deg,#c2410c 1px,transparent 1px);background-size:40px 40px"></div>

    <div class="relative z-10 w-full max-w-md fade-in">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 mb-4 shadow-lg shadow-orange-700/30">
                <i data-lucide="mail-check" class="w-7 h-7 text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Verifikasi Email</h1>
            <p class="text-slate-400 text-sm mt-1">Masukkan kode OTP yang dikirim ke email Anda</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8">
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

            <div class="mb-5 p-3.5 rounded-xl bg-slate-800 border border-slate-700">
                <p class="text-xs text-slate-400 mb-1">Email tujuan</p>
                <p class="text-sm font-medium text-white break-all">{{ $email }}</p>
            </div>

            <form method="POST" action="{{ route('verification.verify') }}" class="space-y-4" id="verify-form">
                @csrf
                <input type="hidden" name="email" value="{{ old('email', $email) }}">

                <div>
                    <label class="form-label text-slate-300" for="otp">Kode OTP</label>
                    <input type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" name="otp" id="otp" required
                           class="form-input bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-indigo-500 text-center text-2xl tracking-[0.35em] font-semibold"
                           placeholder="000000" value="{{ old('otp') }}">
                    <p class="text-xs text-slate-500 mt-2">Kode berlaku selama 10 menit.</p>
                </div>

                <button type="submit" id="verify-btn"
                        class="w-full btn btn-primary py-2.5 justify-center text-sm font-semibold mt-2"
                        style="background:linear-gradient(135deg,#c2410c,#9f1239);box-shadow:0 4px 15px rgba(159,18,57,.28)">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Verifikasi Email
                </button>
            </form>

            <form method="POST" action="{{ route('verification.resend') }}" class="mt-3">
                @csrf
                <input type="hidden" name="email" value="{{ old('email', $email) }}">
                <button type="submit" class="w-full btn justify-center text-sm border border-slate-700 bg-slate-900 text-white hover:bg-slate-800">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Kirim Ulang OTP
                </button>
            </form>

            <a href="{{ route('login') }}" class="mt-3 w-full btn justify-center text-sm text-slate-400 hover:text-white">
                Kembali ke Login
            </a>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('verify-form').addEventListener('submit', function () {
                const btn = document.getElementById('verify-btn');
                btn.disabled = true;
                btn.innerHTML = 'Memverifikasi...';
            });
        </script>
    @endpush
</x-layouts.auth>
