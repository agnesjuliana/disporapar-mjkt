<x-layouts.auth title="Daftar Tenant">
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image:linear-gradient(#c2410c 1px,transparent 1px),linear-gradient(90deg,#c2410c 1px,transparent 1px);background-size:40px 40px"></div>

    <div class="relative z-10 w-full max-w-lg fade-in">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-white/95 mb-3 shadow-lg shadow-orange-700/20 p-1.5">
                <img src="{{ asset('images/brand-colored.png') }}" alt="{{ config('app.name', 'WARTA WILWATIKTA') }}" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-bold text-white">{{ config('app.name', 'WARTA WILWATIKTA') }}</h1>
            <p class="text-slate-400 text-sm">Daftar sebagai Tenant / UMKM</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-7">
            <h2 class="text-lg font-semibold text-white mb-1">Buat Akun Tenant UMKM</h2>
            <p class="text-slate-400 text-sm mb-5">Isi data di bawah untuk mendaftar. Akun akan diverifikasi oleh Admin.</p>

            @if ($errors->any())
                <div class="flex items-start gap-3 p-3.5 rounded-xl mb-5 bg-red-950 border border-red-800 text-red-300">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <span class="text-sm">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register.tenant.store') }}" class="space-y-4" id="tenant-form">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="sm:col-span-2">
                        <label class="form-label text-slate-300" for="name">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="text" name="name" id="name" required placeholder="Nama lengkap penanggung jawab"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-orange-500"
                                   value="{{ old('name') }}">
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-slate-300" for="email">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="email" name="email" id="email" required placeholder="email@contoh.com"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-orange-500"
                                   value="{{ old('email') }}">
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-slate-300" for="phone">No. Telepon</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="phone" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="tel" name="phone" id="phone" required placeholder="08xxxxxxxxx"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-orange-500"
                                   value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label text-slate-300" for="org_name">Nama Usaha / Organisasi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="building" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="text" name="org_name" id="org_name" required placeholder="Nama usaha atau organisasi"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-orange-500"
                                   value="{{ old('org_name') }}">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label text-slate-300" for="address">Alamat</label>
                        <textarea name="address" id="address" required rows="2" placeholder="Alamat lengkap usaha"
                                  class="form-textarea bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-orange-500 resize-none">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label class="form-label text-slate-300" for="password">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="password" name="password" id="password" required placeholder="Min. 8 karakter"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-slate-300" for="password_confirmation">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="lock-keyhole" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-orange-500">
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-2.5 bg-orange-950/50 border border-orange-800/40 rounded-xl p-3 mt-1">
                    <i data-lucide="info" class="w-4 h-4 text-orange-400 shrink-0 mt-0.5"></i>
                    <p class="text-orange-300 text-xs leading-relaxed">
                        Setelah mendaftar, akun Anda akan diverifikasi oleh Admin Disporapar sebelum dapat mengikuti event.
                    </p>
                </div>

                <button type="submit" id="tenant-btn"
                        class="w-full btn py-2.5 justify-center text-sm font-semibold text-white"
                        style="background:linear-gradient(135deg,#f97316,#ea580c);box-shadow:0 4px 15px rgba(249,115,22,.3)">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Buat Akun Tenant
                </button>
            </form>

            <p class="text-center text-slate-500 text-sm mt-5">
                Ingin memilih role lain?
                <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Kembali</a>
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('tenant-form').addEventListener('submit', function () {
                const btn = document.getElementById('tenant-btn');
                btn.disabled = true;
                btn.innerHTML = 'Memproses...';
            });
        </script>
    @endpush
</x-layouts.auth>
