@php
    $onHome = request()->routeIs('landing');
    $sectionHref = fn (string $hash) => $onHome ? "#{$hash}" : route('landing')."#{$hash}";
@endphp

<header class="landing-nav fixed inset-x-0 top-0 z-50">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 h-16 flex items-center justify-between">
        <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
            <img src="{{ asset('images/brand-colored.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            <div class="leading-tight">
                <p class="text-sm font-extrabold text-white">{{ config('app.name', 'WARTA WILWATIKTA') }}</p>
                <p class="text-[10px] uppercase tracking-wider text-orange-200/70">Disporapar Mojokerto</p>
            </div>
        </a>

        <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-orange-50/85">
            <a href="{{ route('landing.events') }}" class="hover:text-white">Event</a>
            <a href="{{ $sectionHref('untuk-siapa') }}" class="hover:text-white">Untuk Siapa</a>
            <a href="{{ $sectionHref('tentang') }}" class="hover:text-white">Tentang</a>
        </nav>

        <div class="flex items-center gap-2">
            <button type="button" onclick="toggleTheme()" class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-orange-100/80 hover:text-white hover:bg-white/10" aria-label="Toggle tema">
                <i data-lucide="moon" class="theme-icon-moon w-4 h-4"></i>
                <i data-lucide="sun" class="theme-icon-sun w-4 h-4 hidden"></i>
            </button>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex btn btn-sm border border-white/20 bg-transparent text-white hover:bg-white/10">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</header>
