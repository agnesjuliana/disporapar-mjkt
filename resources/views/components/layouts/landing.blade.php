@props(['title' => null])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.description') }}">
    <title>{{ $title ? $title.' | ' : '' }}{{ config('app.name', 'WARTA WILWATIKTA') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand-colored.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/brand-colored.png') }}">

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
<body class="bg-white dark:bg-brand-dark-bg text-slate-900 dark:text-orange-50 antialiased">

    <x-landing.nav />

    {{ $slot }}

    <x-landing.footer />

    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        }

        function updateThemeIcon(isDark) {
            document.querySelectorAll('.theme-icon-moon').forEach(el => el.style.display = isDark ? 'none' : 'block');
            document.querySelectorAll('.theme-icon-sun').forEach(el => el.style.display = isDark ? 'block' : 'none');
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon(document.documentElement.classList.contains('dark'));
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
