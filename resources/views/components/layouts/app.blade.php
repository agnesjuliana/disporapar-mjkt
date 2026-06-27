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
    <script>
        (function () {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#4f46e5',
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                        },
                        brand: { DEFAULT: '#4f46e5' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <style>
        * { font-family: Inter, system-ui, -apple-system, sans-serif; box-sizing: border-box; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        #sidebar { transition: width .25s cubic-bezier(.4,0,.2,1), transform .25s cubic-bezier(.4,0,.2,1); will-change: width; }
        #sidebar.collapsed { width: 4.5rem !important; transform: translateX(0); }
        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .sidebar-logo-text,
        #sidebar.collapsed .sidebar-group-label { opacity: 0; width: 0; overflow: hidden; }
        #sidebar.collapsed .sidebar-logo-icon { margin: 0 auto; }
        body.sidebar-collapsed #main-content { margin-left: 4.5rem; }
        .nav-item { display: flex; align-items: center; gap: .75rem; padding: .55rem .85rem; border-radius: .5rem; font-size: .875rem; font-weight: 500; color: #94a3b8; transition: background .15s, color .15s; white-space: nowrap; cursor: pointer; text-decoration: none; }
        .nav-item:hover { background: rgba(99,102,241,.12); color: #818cf8; }
        .nav-item.active { background: rgba(99,102,241,.18); color: #818cf8; font-weight: 600; }
        .nav-item .nav-icon { flex-shrink: 0; width: 1.1rem; height: 1.1rem; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: .875rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 1px 2px rgba(0,0,0,.03); }
        .dark .card { background: #1e293b; border-color: #334155; }
        .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: .5rem; font-size: .875rem; font-weight: 500; cursor: pointer; border: none; transition: all .15s; text-decoration: none; line-height: 1.4; }
        .btn:disabled { opacity: .5; pointer-events: none; }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-primary:hover { background: #4338ca; }
        .btn-secondary { background: #f1f5f9; color: #374151; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-success { background: #16a34a; color: white; }
        .btn-success:hover { background: #15803d; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-ghost { background: transparent; color: #64748b; }
        .btn-ghost:hover { background: #f1f5f9; color: #374151; }
        .btn-sm { padding: .35rem .7rem; font-size: .8rem; }
        .btn-icon { padding: .45rem; aspect-ratio: 1; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: .55rem .875rem; border: 1px solid #d1d5db; border-radius: .5rem; font-size: .875rem; color: #111827; background: white; outline: none; transition: border-color .15s, box-shadow .15s; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .form-label { display: block; font-size: .875rem; font-weight: 500; color: #374151; margin-bottom: .375rem; }
        .dark .form-input, .dark .form-select, .dark .form-textarea { background: #1e293b; border-color: #334155; color: #f1f5f9; }
        .dark .form-label { color: #cbd5e1; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { padding: .75rem 1rem; text-align: left; font-size: .75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .data-table td { padding: .875rem 1rem; font-size: .875rem; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: #f8fafc; }
        .dark .data-table th { background: #1e293b; color: #94a3b8; border-color: #334155; }
        .dark .data-table td { color: #cbd5e1; border-color: #1e293b; }
        .dark .data-table tbody tr:hover { background: #1e293b; }
        .badge { display: inline-flex; align-items: center; gap: .25rem; padding: .2rem .65rem; border-radius: 9999px; font-size: .75rem; font-weight: 600; line-height: 1.4; white-space: nowrap; }
        .badge-green { background: #dcfce7; color: #16a34a; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-yellow { background: #fef9c3; color: #ca8a04; }
        .badge-blue { background: #dbeafe; color: #2563eb; }
        .badge-indigo { background: #e0e7ff; color: #4f46e5; }
        .badge-gray { background: #f1f5f9; color: #64748b; }
        .badge-orange { background: #ffedd5; color: #ea580c; }
        .modal-overlay { position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 1rem; opacity: 0; pointer-events: none; transition: opacity .2s; }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box { background: white; border-radius: 1rem; max-width: 540px; width: 100%; box-shadow: 0 25px 50px rgba(0,0,0,.2); transform: scale(.95) translateY(8px); transition: transform .25s cubic-bezier(.4,0,.2,1); max-height: 90vh; overflow-y: auto; }
        .modal-overlay.open .modal-box { transform: scale(1) translateY(0); }
        .dark .modal-box { background: #1e293b; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: .75rem; }
        .page-title { font-size: 1.375rem; font-weight: 700; color: #111827; }
        .page-subtitle { font-size: .875rem; color: #6b7280; margin-top: .2rem; }
        .dark .page-title { color: #f1f5f9; }
        .dark .page-subtitle { color: #94a3b8; }
        .fade-in { animation: fadeIn .25s ease-out both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 antialiased">
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
        function applyTheme(theme) {
            document.documentElement.classList.toggle('dark', theme === 'dark');
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        }

        function updateThemeIcon(isDark) {
            document.querySelectorAll('.theme-icon-moon').forEach(icon => icon.style.display = isDark ? 'none' : 'block');
            document.querySelectorAll('.theme-icon-sun').forEach(icon => icon.style.display = isDark ? 'block' : 'none');
        }

        function closeModal(id) {
            const overlay = document.getElementById(id);
            if (!overlay) return;
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        function openModal(id) {
            const overlay = document.getElementById(id);
            if (!overlay) return;
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        document.addEventListener('click', event => {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('open');
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.open').forEach(modal => modal.classList.remove('open'));
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeIcon(isDark);
            if (typeof lucide !== 'undefined') lucide.createIcons();

            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
                sidebar?.classList.toggle('collapsed');
                const collapsed = sidebar?.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
                if (mainContent) mainContent.style.marginLeft = collapsed ? '4.5rem' : '';
            });

            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar?.classList.add('collapsed');
                if (mainContent) mainContent.style.marginLeft = '4.5rem';
            }

            document.querySelectorAll('[data-auto-dismiss]').forEach(element => {
                setTimeout(() => element.remove(), parseInt(element.dataset.autoDismiss) || 4000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
