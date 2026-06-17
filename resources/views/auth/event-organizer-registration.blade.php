<x-layouts.auth title="Daftar Event Organizer">
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image:linear-gradient(#6366f1 1px,transparent 1px),linear-gradient(90deg,#6366f1 1px,transparent 1px);background-size:40px 40px"></div>

    <div class="relative z-10 w-full max-w-lg fade-in">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 mb-3 shadow-lg shadow-blue-500/30">
                <i data-lucide="calendar-days" class="w-6 h-6 text-white"></i>
            </div>
            <h1 class="text-xl font-bold text-white">{{ config('app.name', 'WARTA WILWATIKTA') }}</h1>
            <p class="text-slate-400 text-sm">Daftar sebagai Event Organizer</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-7">
            <h2 class="text-lg font-semibold text-white mb-1">Buat Akun Event Organizer</h2>
            <p class="text-slate-400 text-sm mb-5">Isi data akun dan organisasi penyelenggara event.</p>

            @if ($errors->any())
                <div class="flex items-start gap-3 p-3.5 rounded-xl mb-5 bg-red-950 border border-red-800 text-red-300">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span class="text-sm">{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register.event-organizer.store') }}" class="space-y-4" id="organizer-form">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="sm:col-span-2">
                        <label class="form-label text-slate-300" for="name">Nama Penanggung Jawab</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="text" name="name" id="name" required placeholder="Nama lengkap penanggung jawab"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-blue-500"
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
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-blue-500"
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
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-blue-500"
                                   value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label text-slate-300" for="org_name">Nama Organisasi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="building" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="text" name="org_name" id="org_name" required placeholder="Nama organisasi event organizer"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-blue-500"
                                   value="{{ old('org_name') }}">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label text-slate-300" for="address">Alamat</label>
                        <textarea name="address" id="address" required rows="2" placeholder="Alamat organisasi"
                                  class="form-textarea bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-blue-500 resize-none">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label class="form-label text-slate-300" for="password">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="password" name="password" id="password" required placeholder="Min. 8 karakter"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-slate-300" for="password_confirmation">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="lock-keyhole" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password"
                                   class="form-input pl-9 bg-slate-800 border-slate-700 text-white placeholder-slate-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <button type="submit" id="organizer-btn"
                        class="w-full btn py-2.5 justify-center text-sm font-semibold text-white"
                        style="background:linear-gradient(135deg,#2563eb,#1d4ed8);box-shadow:0 4px 15px rgba(37,99,235,.3)">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Buat Akun Event Organizer
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
            document.getElementById('organizer-form').addEventListener('submit', function () {
                const btn = document.getElementById('organizer-btn');
                btn.disabled = true;
                btn.innerHTML = 'Memproses...';
            });
        </script>
    @endpush
</x-layouts.auth>
