<x-layouts.auth title="Pilih Role">
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image:linear-gradient(#c2410c 1px,transparent 1px),linear-gradient(90deg,#c2410c 1px,transparent 1px);background-size:40px 40px"></div>

    <div class="relative z-10 w-full max-w-4xl fade-in">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/95 mb-4 shadow-lg shadow-orange-700/30 p-2">
                <img src="{{ asset('images/brand-colored.png') }}" alt="{{ config('app.name', 'WARTA WILWATIKTA') }}" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-bold text-white">{{ config('app.name', 'WARTA WILWATIKTA') }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ config('app.description') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-3">
            <a href="{{ route('register.masyarakat') }}" class="group rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-2xl hover:border-indigo-500 hover:bg-slate-800">
                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 text-white">
                    <i data-lucide="users" class="h-6 w-6"></i>
                </div>
                <h2 class="text-lg font-semibold text-white">Masyarakat</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-300">Akun pengguna umum untuk melakukan pendaftaran peserta event.</p>
                <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-300 group-hover:text-indigo-200">
                    Pilih role
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </span>
            </a>

            <a href="{{ route('register.event-organizer') }}" class="group rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-2xl hover:border-teal-500 hover:bg-slate-800">
                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-teal-600 text-white">
                    <i data-lucide="calendar-days" class="h-6 w-6"></i>
                </div>
                <h2 class="text-lg font-semibold text-white">Event Organizer</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-300">Akun penyelenggara event dengan data organisasi dan kontak penanggung jawab.</p>
                <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-teal-300 group-hover:text-teal-200">
                    Pilih role
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </span>
            </a>

            <a href="{{ route('register.tenant') }}" class="group rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-2xl hover:border-orange-500 hover:bg-slate-800">
                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-500 text-white">
                    <i data-lucide="store" class="h-6 w-6"></i>
                </div>
                <h2 class="text-lg font-semibold text-white">Tenant UMKM</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-300">Akun tenant atau UMKM untuk mendaftar stan pada event yang tersedia.</p>
                <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-orange-300 group-hover:text-orange-200">
                    Pilih role
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </span>
            </a>
        </div>

        <p class="text-center text-slate-500 text-sm mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Masuk di sini</a>
        </p>
    </div>
</x-layouts.auth>
