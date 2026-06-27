@php
    $landmarkImage = collect([
        'images/mojokerto-landmark.jpg',
        'images/mojokerto-landmark.jpeg',
        'images/mojokerto-landmark.png',
        'images/auth-landmark.jpg',
        'images/auth-landmark.png',
    ])->first(fn ($path) => file_exists(public_path($path)));
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }} | {{ config('app.name', 'WARTA WILWATIKTA') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand-colored.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/brand-colored.png') }}">

    {{-- Apply saved theme before first paint to avoid flash --}}
    <script>
        (function () {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <x-layouts.head-scripts />
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @stack('styles')
</head>
<body class="min-h-screen bg-brand-dark-bg text-white">
    <button type="button" onclick="toggleAuthTheme()" class="auth-theme-toggle" title="Toggle tema" aria-label="Toggle tema">
        <i data-lucide="moon" class="theme-icon-moon w-4 h-4"></i>
        <i data-lucide="sun"  class="theme-icon-sun  w-4 h-4 hidden"></i>
    </button>

    <main class="min-h-screen grid grid-cols-1 lg:grid-cols-[minmax(420px,46%)_1fr]">
        <section class="relative min-h-screen overflow-y-auto px-5 py-8 sm:px-10 lg:px-14 flex items-center">
            <div class="auth-panel-grid absolute inset-0 opacity-[0.08]"
                 style="background-image:linear-gradient(#fed7aa 1px,transparent 1px),linear-gradient(90deg,#fed7aa 1px,transparent 1px);background-size:42px 42px"></div>
            <div class="auth-panel-bg absolute inset-0"></div>

            <div class="auth-content relative z-10 w-full flex justify-center">
                {{ $slot }}
            </div>
        </section>

        {{-- --landmark-image is the only dynamic value; gradient layers live in public/css/app.css --}}
        <aside class="auth-visual relative hidden lg:block min-h-screen overflow-hidden"
               @if($landmarkImage) style="--landmark-image: url('{{ asset($landmarkImage) }}')" @endif>
            <div class="relative z-10 h-full flex flex-col justify-between p-10 xl:p-14">
                <div class="flex items-center gap-3">
                    <span class="auth-brand-mark">
                        <img src="{{ asset('images/brand-white.png') }}"
                             alt="{{ config('app.name', 'WARTA WILWATIKTA') }}"
                             class="w-full h-full object-contain">
                    </span>
                    <div>
                        <p class="text-sm font-bold text-white">{{ config('app.name', 'WARTA WILWATIKTA') }}</p>
                        <p class="text-xs text-orange-100/70">Mojokerto Event Platform</p>
                    </div>
                </div>

                <div class="max-w-xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-200/80">Disporapar Mojokerto</p>
                    <h1 class="mt-4 text-4xl xl:text-5xl font-extrabold leading-tight text-white">
                        Jelajahi agenda, venue, dan ruang kreatif daerah.
                    </h1>
                    <p class="mt-5 text-sm xl:text-base leading-relaxed text-orange-50/78">
                        Portal terpadu untuk masyarakat, event organizer, tenant UMKM, dan pengelolaan venue.
                    </p>
                </div>

                <div class="flex items-center justify-between text-xs text-orange-100/68">
                    <span>Warta Wilwatikta</span>
                    <span class="inline-flex gap-1">
                        <span class="w-2 h-2 rounded-full bg-orange-200"></span>
                        <span class="w-2 h-2 rounded-full bg-orange-300"></span>
                        <span class="w-2 h-2 rounded-full bg-teal-200"></span>
                    </span>
                </div>
            </div>
        </aside>
    </main>

    <script>
        function updateThemeIcon(isDark) {
            document.querySelectorAll('.theme-icon-moon').forEach(el => el.style.display = isDark ? 'none'  : 'block');
            document.querySelectorAll('.theme-icon-sun') .forEach(el => el.style.display = isDark ? 'block' : 'none');
        }

        function toggleAuthTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon(document.documentElement.classList.contains('dark'));
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
