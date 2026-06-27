@props([
    'title' => 'Dashboard',
    'currentPage' => '',
    'role' => null,
    'activeEventId' => null,
])

@php
    $user = auth()->user();
    $resolvedRole = $role ?? $user?->role ?? 'TENANT';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.description') }}">
    <title>{{ $title }} | {{ config('app.name', 'WARTA WILWATIKTA') }}</title>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-brand-dark-bg text-slate-900 dark:text-orange-50 antialiased">
    <div id="toast-container"></div>

    <x-layouts.sidebar :role="$resolvedRole" :current-page="$currentPage" :active-event-id="$activeEventId" />
    <x-layouts.header :title="$title" :role="$resolvedRole" />

    <div class="flex h-screen overflow-hidden">
        <div id="main-content" class="flex-1 flex flex-col ml-0 lg:ml-64 transition-all duration-300 overflow-hidden">
            <main class="flex-1 overflow-y-auto pt-16">
                <div class="p-6 fade-in">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        }

        function updateThemeIcon(isDark) {
            document.querySelectorAll('.theme-icon-moon').forEach(el => el.style.display = isDark ? 'none'  : 'block');
            document.querySelectorAll('.theme-icon-sun') .forEach(el => el.style.display = isDark ? 'block' : 'none');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('open');
            document.body.style.overflow = '';
        }

        function openModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        document.addEventListener('click', e => {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('open');
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeIcon(isDark);
            if (typeof lucide !== 'undefined') lucide.createIcons();

            const sidebar     = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const mainHeader  = document.getElementById('main-header');

            const setSidebarCollapsed = collapsed => {
                sidebar?.classList.toggle('collapsed', collapsed);
                document.body.classList.toggle('sidebar-collapsed', collapsed);
                localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
                if (mainContent) mainContent.style.marginLeft = collapsed ? '4.5rem' : '';
                if (mainHeader)  mainHeader.style.left        = collapsed ? '4.5rem' : '';
            };

            document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
                setSidebarCollapsed(!sidebar?.classList.contains('collapsed'));
            });

            if (localStorage.getItem('sidebarCollapsed') === 'true') setSidebarCollapsed(true);

            document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
                setTimeout(() => el.remove(), parseInt(el.dataset.autoDismiss) || 4000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
