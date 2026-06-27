<x-layouts.app title="Home" current-page="home" :role="$menu['role']">
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-2xl border border-amber-200/70 dark:border-amber-500/20 bg-[#7f1d1d] min-h-[220px] shadow-sm">
            <div class="absolute inset-0 bg-[linear-gradient(120deg,rgba(127,29,29,.98),rgba(180,83,9,.88),rgba(15,118,110,.72))]"></div>
            <div class="absolute inset-x-0 bottom-0 h-24 bg-[linear-gradient(to_top,rgba(15,23,42,.32),transparent)]"></div>
            <div class="absolute -right-10 bottom-0 w-80 h-36 opacity-25">
                <div class="absolute bottom-0 right-8 w-44 h-24 bg-amber-100/80 [clip-path:polygon(0_100%,14%_45%,28%_100%,42%_38%,56%_100%,70%_45%,84%_100%,100%_50%,100%_100%)]"></div>
                <div class="absolute bottom-0 right-44 w-28 h-20 bg-amber-50/70 [clip-path:polygon(0_100%,50%_0,100%_100%)]"></div>
            </div>
            <div class="absolute left-8 bottom-0 right-0 h-16 opacity-30">
                <div class="h-full bg-[repeating-linear-gradient(90deg,rgba(254,243,199,.75)_0_28px,transparent_28px_48px)] [clip-path:polygon(0_100%,0_55%,6%_55%,6%_30%,11%_30%,11%_55%,18%_55%,18%_100%)]"></div>
            </div>
            <div class="relative p-6 sm:p-8 flex min-h-[220px] flex-col justify-end">
                <div class="max-w-3xl">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-50 ring-1 ring-white/20">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        Mojokerto Event Platform
                    </span>
                    <h1 class="mt-4 text-3xl sm:text-4xl font-bold text-white">Warta Wilwatikta</h1>
                    <p class="mt-2 max-w-2xl text-sm sm:text-base leading-relaxed text-amber-50/90">
                        Ruang digital untuk mengelola venue, event daerah, tenant UMKM, dan partisipasi masyarakat.
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl {{ $menu['color'] }} flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="{{ $menu['icon'] }}" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Selamat datang,</p>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Anda masuk sebagai {{ $menu['label'] }}. Pilih menu di bawah untuk melanjutkan pekerjaan.
                        </p>
                    </div>
                </div>

                <div class="inline-flex items-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    {{ $menu['label'] }}
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <div>
                <p class="text-sm font-semibold text-[#9f1239] dark:text-orange-300">Menu Akses</p>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Pilih modul yang tersedia untuk role Anda</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($menu['items'] as $item)
                    @continue($item['page'] === 'home')

                    <a href="{{ url($item['href']) }}" class="card group hover:border-amber-400 dark:hover:border-orange-400 min-h-[150px]">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/30 flex items-center justify-center text-[#9f1239] dark:text-orange-300 group-hover:text-[#c2410c]">
                                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-900 dark:text-white">{{ $item['label'] }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    </a>

                @endforeach
            </div>
        </section>
    </div>
</x-layouts.app>
