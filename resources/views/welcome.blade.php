<x-layouts.landing>
    {{-- ── Hero ───────────────────────────────────────────── --}}
    <section class="landing-hero relative pt-32 pb-24 px-5 sm:px-8"
             style="--landmark-image: url('{{ asset('images/mojokerto-landmark.jpeg') }}')">
        <div class="relative z-10 max-w-7xl mx-auto">
            <div class="max-w-2xl fade-in">
                <span class="landing-eyebrow">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    Bumi Wilwatikta, Warisan Majapahit
                </span>
                <h1 class="mt-5 text-4xl sm:text-5xl font-extrabold leading-[1.1] text-white">
                    Semarak Event Daerah Mojokerto, dari Satu Portal Terpadu.
                </h1>
                <p class="mt-5 text-base leading-relaxed text-orange-50/80">
                    {{ config('app.description') }}. Temukan festival budaya, konser, bazar UMKM, dan agenda resmi
                    yang telah disetujui Disporapar Kabupaten &amp; Kota Mojokerto — mulai dari pendaftaran event organizer,
                    penempatan slot tenant, hingga registrasi peserta.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary py-3 px-6 text-sm font-semibold">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary py-3 px-6 text-sm font-semibold">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn py-3 px-6 text-sm font-semibold border border-white/25 bg-white/5 text-white hover:bg-white/15">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            Masuk ke Akun
                        </a>
                    @endauth
                    <a href="#event-pilihan" class="btn py-3 px-6 text-sm font-semibold bg-transparent text-orange-100 hover:text-white">
                        Lihat Event
                        <i data-lucide="arrow-down" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <div class="mt-14 grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach ($stats as $stat)
                    <div class="landing-stat-tile">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-orange-300"></i>
                        <p class="mt-2 text-2xl font-extrabold text-white">{{ $stat['value'] }}</p>
                        <p class="text-xs text-orange-100/70">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Tentang / Wilwatikta ──────────────────────────────── --}}
    <section id="tentang" class="landing-batik-band">
        <div class="landing-batik-overlay py-20 px-5 sm:px-8">
            <div class="max-w-4xl mx-auto text-center fade-in">
                <span class="badge badge-orange">Filosofi Nama</span>
                <h2 class="mt-4 text-3xl font-extrabold text-slate-900 dark:text-white">
                    "Wilwatikta" — Nama Kuno Bumi Majapahit
                </h2>
                <p class="mt-4 text-slate-600 dark:text-orange-100/75 leading-relaxed">
                    Wilwatikta adalah nama kawasan yang menjadi pusat kejayaan Kemaharajaan Majapahit, kini dikenal
                    sebagai Mojokerto. Warta Wilwatikta hadir sebagai portal digital Disporapar untuk merawat semangat
                    itu — menghubungkan event organizer, tenant UMKM, dan masyarakat dalam satu ekosistem agenda
                    daerah yang transparan dan mudah diakses.
                </p>
            </div>

            <div class="max-w-5xl mx-auto mt-12 grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="landing-role-card text-center">
                    <div class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-brand-red text-white">
                        <i data-lucide="shield-check" class="h-5 w-5"></i>
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Terverifikasi Admin</h3>
                    <p class="mt-1.5 text-sm text-slate-500 dark:text-orange-100/65">
                        Setiap event disetujui langsung oleh Disporapar sebelum tampil ke publik.
                    </p>
                </div>
                <div class="landing-role-card text-center">
                    <div class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-teal-600 text-white">
                        <i data-lucide="store" class="h-5 w-5"></i>
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Slot Tenant Tertata</h3>
                    <p class="mt-1.5 text-sm text-slate-500 dark:text-orange-100/65">
                        Manajemen slot stan UMKM yang rapi, dari pengajuan hingga penetapan lokasi.
                    </p>
                </div>
                <div class="landing-role-card text-center">
                    <div class="mx-auto mb-3 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-brand-terracotta text-white">
                        <i data-lucide="calendar-range" class="h-5 w-5"></i>
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Kalender Terpadu</h3>
                    <p class="mt-1.5 text-sm text-slate-500 dark:text-orange-100/65">
                        Semua agenda daerah dalam satu kalender, mudah dipantau semua peran.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Event Pilihan ─────────────────────────────────────── --}}
    <section id="event-pilihan" class="py-20 px-5 sm:px-8 bg-white dark:bg-brand-dark-bg">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
                <div>
                    <span class="badge badge-orange">Agenda Terdekat</span>
                    <h2 class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white">Event Aktif Pilihan</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-orange-100/65 max-w-xl">
                        Event yang sudah disetujui Disporapar dan siap kedatangan pengunjung serta tenant UMKM.
                    </p>
                </div>
                <a href="{{ route('landing.events') }}" class="text-sm font-semibold text-brand-red dark:text-orange-300 inline-flex items-center gap-1.5 hover:underline">
                    Lihat semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            @if ($featuredEvents->isEmpty())
                <div class="card text-center py-16">
                    <i data-lucide="calendar-x" class="w-10 h-10 mx-auto text-slate-300"></i>
                    <p class="mt-3 text-sm text-slate-500 dark:text-orange-100/65">Belum ada event aktif saat ini. Nantikan agenda berikutnya.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($featuredEvents as $event)
                        <x-landing.event-card :event="$event" />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ── Untuk Siapa ───────────────────────────────────────── --}}
    <section id="untuk-siapa" class="landing-batik-band">
        <div class="landing-batik-overlay py-20 px-5 sm:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="badge badge-orange">Bergabung</span>
                    <h2 class="mt-3 text-3xl font-extrabold text-slate-900 dark:text-white">Untuk Siapa Warta Wilwatikta?</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-orange-100/65">
                        Tiga peran utama yang bergerak bersama menghidupkan agenda daerah Mojokerto.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <a href="{{ route('register.masyarakat') }}" class="landing-role-card group">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-brand-red text-white">
                            <i data-lucide="users" class="h-6 w-6"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Masyarakat</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-orange-100/65">
                            Jelajahi kalender event daerah dan daftar sebagai peserta di berbagai kegiatan.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-red dark:text-orange-300">
                            Daftar sebagai Masyarakat <i data-lucide="arrow-right" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform"></i>
                        </span>
                    </a>

                    <a href="{{ route('register.event-organizer') }}" class="landing-role-card group">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-teal-600 text-white">
                            <i data-lucide="calendar-days" class="h-6 w-6"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Event Organizer</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-orange-100/65">
                            Ajukan event, kelola venue, slot tenant, dan pendaftaran peserta dalam satu tempat.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-teal-600 dark:text-teal-300">
                            Daftar sebagai Event Organizer <i data-lucide="arrow-right" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform"></i>
                        </span>
                    </a>

                    <a href="{{ route('register.tenant') }}" class="landing-role-card group">
                        <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-brand-terracotta text-white">
                            <i data-lucide="store" class="h-6 w-6"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Tenant UMKM</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-orange-100/65">
                            Daftarkan usahamu dan ajukan slot stan pada event-event pilihan di Mojokerto.
                        </p>
                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-brand-terracotta dark:text-orange-300">
                            Daftar sebagai Tenant <i data-lucide="arrow-right" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA ────────────────────────────────────────────────── --}}
    <section class="py-20 px-5 sm:px-8 bg-white dark:bg-brand-dark-bg">
        <div class="landing-cta max-w-6xl mx-auto px-8 py-14 text-center fade-in">
            <div class="relative z-10">
                <h2 class="text-3xl font-extrabold text-white">Siap Jadi Bagian dari Geliat Event Mojokerto?</h2>
                <p class="mt-3 text-sm text-orange-50/85 max-w-xl mx-auto">
                    Satu akun, satu portal — untuk agenda budaya, ekonomi kreatif, dan pariwisata daerah.
                </p>
                <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn py-3 px-6 text-sm font-semibold bg-white text-brand-red hover:bg-orange-50">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn py-3 px-6 text-sm font-semibold bg-white text-brand-red hover:bg-orange-50">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn py-3 px-6 text-sm font-semibold border border-white/40 bg-transparent text-white hover:bg-white/10">
                            Masuk ke Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</x-layouts.landing>
