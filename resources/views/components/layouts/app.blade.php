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
                            DEFAULT: '#9f1239',
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#c2410c',
                            600: '#9f1239',
                            700: '#881337',
                            800: '#5f161f',
                        },
                        brand: {
                            DEFAULT: '#9f1239',
                            red: '#9f1239',
                            terracotta: '#c2410c',
                            gold: '#d97706',
                            teal: '#0f766e',
                            ink: '#1f2937',
                        },
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
        :root {
            --mojo-red: #9f1239;
            --mojo-red-dark: #5f161f;
            --mojo-terracotta: #c2410c;
            --mojo-gold: #d97706;
            --mojo-gold-soft: #ffedd5;
            --mojo-teal: #0f766e;
            --mojo-teal-soft: #ccfbf1;
            --mojo-ink: #1f2937;
            --mojo-surface: #fff7ed;
            --mojo-dark-bg: #180f12;
            --mojo-dark-surface: #241417;
            --mojo-dark-card: #2b1a1c;
            --mojo-dark-border: #56312a;
        }
        * { font-family: Inter, system-ui, -apple-system, sans-serif; box-sizing: border-box; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e7c8a0; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #d97706; }
        #sidebar {
            background:
                radial-gradient(circle at 18% 0%, rgba(217,119,6,.42), transparent 30%),
                radial-gradient(circle at 92% 18%, rgba(15,118,110,.24), transparent 34%),
                linear-gradient(160deg, #5f161f 0%, #7f1d1d 45%, #9a3412 100%);
            border-color: rgba(254,215,170,.28);
            box-shadow: 18px 0 45px rgba(95,22,31,.18);
            transition: width .25s cubic-bezier(.4,0,.2,1), transform .25s cubic-bezier(.4,0,.2,1);
            will-change: width;
        }
        .dark #sidebar {
            background:
                radial-gradient(circle at 18% 0%, rgba(194,65,12,.34), transparent 31%),
                radial-gradient(circle at 94% 18%, rgba(15,118,110,.18), transparent 34%),
                linear-gradient(160deg, #2f1218 0%, #4c1020 48%, #5f2810 100%);
            border-color: rgba(251,191,36,.18);
        }
        #sidebar > div:first-child,
        #sidebar > div:last-child {
            border-color: rgba(254,215,170,.22) !important;
            background: rgba(255,247,237,.05);
        }
        .dark #sidebar > div:first-child,
        .dark #sidebar > div:last-child {
            border-color: rgba(251,191,36,.14) !important;
            background: rgba(15,23,42,.16);
        }
        #sidebar .sidebar-group-label { color: rgba(255,237,213,.66) !important; }
        #sidebar .sidebar-logo-text .text-slate-400 { color: rgba(255,237,213,.72) !important; }
        #sidebar .nav-item { color: rgba(255,237,213,.78); }
        #sidebar .nav-item:hover {
            background: linear-gradient(90deg, rgba(255,247,237,.15), rgba(217,119,6,.12));
            color: #fff7ed;
        }
        #sidebar .nav-item.active {
            background: linear-gradient(90deg, rgba(255,247,237,.22), rgba(217,119,6,.2));
            color: #ffffff;
            box-shadow: inset 3px 0 0 #fbbf24;
        }
        #sidebar form button { color: rgba(255,237,213,.6) !important; }
        #sidebar form button:hover { color: #fecaca !important; }
        #sidebar.collapsed { width: 4.5rem !important; transform: translateX(0); }
        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .sidebar-logo-text,
        #sidebar.collapsed .sidebar-group-label { opacity: 0; width: 0; overflow: hidden; }
        #sidebar.collapsed .sidebar-logo-icon { margin: 0 auto; }
        body.sidebar-collapsed #main-content { margin-left: 4.5rem; }
        body.sidebar-collapsed #main-header { left: 4.5rem; }
        .nav-item { display: flex; align-items: center; gap: .75rem; padding: .55rem .85rem; border-radius: .5rem; font-size: .875rem; font-weight: 500; color: #94a3b8; transition: background .15s, color .15s; white-space: nowrap; cursor: pointer; text-decoration: none; }
        .nav-item:hover { background: rgba(217,119,6,.14); color: #fbbf24; }
        .nav-item.active { background: rgba(159,18,57,.28); color: #fed7aa; font-weight: 600; }
        .nav-item .nav-icon { flex-shrink: 0; width: 1.1rem; height: 1.1rem; }
        .card { background: white; border: 1px solid #e2e8f0; border-radius: .875rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 1px 2px rgba(0,0,0,.03); }
        .dark .card { background: var(--mojo-dark-card); border-color: var(--mojo-dark-border); box-shadow: 0 12px 34px rgba(0,0,0,.16); }
        .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: .5rem; font-size: .875rem; font-weight: 500; cursor: pointer; border: none; transition: all .15s; text-decoration: none; line-height: 1.4; }
        .btn:disabled { opacity: .5; pointer-events: none; }
        .btn-primary { background: linear-gradient(135deg, var(--mojo-red), var(--mojo-terracotta)); color: white; box-shadow: 0 8px 18px rgba(159,18,57,.16); }
        .btn-primary:hover { background: linear-gradient(135deg, #881337, #9a3412); }
        .btn-secondary { background: #f1f5f9; color: #374151; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-success { background: var(--mojo-teal); color: white; }
        .btn-success:hover { background: #115e59; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-ghost { background: transparent; color: #64748b; }
        .btn-ghost:hover { background: #f1f5f9; color: #374151; }
        .btn-sm { padding: .35rem .7rem; font-size: .8rem; }
        .btn-icon { padding: .45rem; aspect-ratio: 1; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: .55rem .875rem; border: 1px solid #d1d5db; border-radius: .5rem; font-size: .875rem; color: #111827; background: white; outline: none; transition: border-color .15s, box-shadow .15s; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--mojo-terracotta); box-shadow: 0 0 0 3px rgba(194,65,12,.14); }
        .form-label { display: block; font-size: .875rem; font-weight: 500; color: #374151; margin-bottom: .375rem; }
        .dark .form-input, .dark .form-select, .dark .form-textarea { background: #241417; border-color: #56312a; color: #fff7ed; }
        .dark .form-label { color: #cbd5e1; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { padding: .75rem 1rem; text-align: left; font-size: .75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .data-table td { padding: .875rem 1rem; font-size: .875rem; color: #374151; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: #f8fafc; }
        .dark .data-table th { background: #241417; color: #d6b8a6; border-color: #56312a; }
        .dark .data-table td { color: #eadbd2; border-color: rgba(86,49,42,.68); }
        .dark .data-table tr { background: transparent; }
        .dark .data-table tbody tr:hover { background: #2b1a1c; }
        .badge { display: inline-flex; align-items: center; gap: .25rem; padding: .2rem .65rem; border-radius: 9999px; font-size: .75rem; font-weight: 600; line-height: 1.4; white-space: nowrap; }
        .badge-green { background: #ccfbf1; color: #0f766e; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-yellow { background: #fef9c3; color: #ca8a04; }
        .badge-blue { background: #ccfbf1; color: #0f766e; }
        .badge-indigo { background: #ffedd5; color: #9f1239; }
        .badge-gray { background: #f1f5f9; color: #64748b; }
        .badge-orange { background: #ffedd5; color: #c2410c; }
        .dark .badge-green { background: #ccfbf1; color: #115e59; border: 1px solid rgba(153,246,228,.7); }
        .dark .badge-red { background: #fee2e2; color: #991b1b; border: 1px solid rgba(254,202,202,.72); }
        .dark .badge-yellow { background: #fef3c7; color: #92400e; border: 1px solid rgba(253,230,138,.72); }
        .dark .badge-blue { background: #ccfbf1; color: #115e59; border: 1px solid rgba(153,246,228,.7); }
        .dark .badge-indigo { background: #ffedd5; color: #9f1239; border: 1px solid rgba(254,215,170,.72); }
        .dark .badge-gray { background: #eadbd2; color: #56312a; border: 1px solid rgba(234,219,210,.68); }
        .dark .badge-orange { background: #ffedd5; color: #9a3412; border: 1px solid rgba(254,215,170,.72); }
        .bg-indigo-50 { background-color: #fff7ed !important; }
        .bg-indigo-100 { background-color: #ffedd5 !important; }
        .bg-indigo-500, .bg-indigo-600 { background-color: var(--mojo-red) !important; }
        .bg-emerald-50 { background-color: #f0fdfa !important; }
        .bg-emerald-100 { background-color: #ccfbf1 !important; }
        .bg-emerald-500, .bg-emerald-600 { background-color: var(--mojo-teal) !important; }
        .bg-orange-50 { background-color: #fff7ed !important; }
        .bg-orange-100 { background-color: #ffedd5 !important; }
        .bg-orange-500, .bg-orange-600 { background-color: var(--mojo-terracotta) !important; }
        .bg-blue-50, .bg-blue-100 { background-color: #ccfbf1 !important; }
        .bg-blue-500, .bg-blue-600 { background-color: var(--mojo-teal) !important; }
        .text-indigo-300, .text-indigo-400 { color: #fdba74 !important; }
        .text-indigo-500, .text-indigo-600, .text-indigo-700 { color: var(--mojo-red) !important; }
        .text-emerald-300, .text-emerald-400 { color: #5eead4 !important; }
        .text-emerald-500, .text-emerald-600, .text-emerald-700 { color: var(--mojo-teal) !important; }
        .text-orange-300, .text-orange-400 { color: #fdba74 !important; }
        .text-orange-500, .text-orange-600, .text-orange-700 { color: var(--mojo-terracotta) !important; }
        .text-blue-500, .text-blue-600, .text-blue-700 { color: var(--mojo-teal) !important; }
        .border-indigo-100, .border-indigo-300, .border-indigo-500 { border-color: #fed7aa !important; }
        .border-indigo-900 { border-color: rgba(154,52,18,.55) !important; }
        .border-emerald-100, .border-emerald-300, .border-emerald-400, .border-emerald-500 { border-color: #99f6e4 !important; }
        .border-orange-100, .border-orange-300, .border-orange-500 { border-color: #fed7aa !important; }
        .hover\:border-indigo-300:hover, .hover\:border-indigo-500:hover { border-color: var(--mojo-gold) !important; }
        .hover\:text-indigo-500:hover, .hover\:text-indigo-600:hover { color: var(--mojo-red) !important; }
        .hover\:bg-indigo-100:hover { background-color: #ffedd5 !important; }
        .focus\:border-indigo-500:focus { border-color: var(--mojo-terracotta) !important; }
        .focus\:ring-indigo-200:focus { --tw-ring-color: rgba(194,65,12,.2) !important; }
        .peer:checked ~ .peer-checked\:border-indigo-500 { border-color: var(--mojo-red) !important; }
        .peer:checked ~ .peer-checked\:ring-indigo-200 { --tw-ring-color: rgba(194,65,12,.22) !important; }
        .peer:checked ~ .peer-checked\:bg-indigo-100 { background-color: #ffedd5 !important; }
        .dark .dark\:bg-slate-900 { background-color: var(--mojo-dark-bg) !important; }
        .dark .dark\:bg-slate-800 { background-color: var(--mojo-dark-card) !important; }
        .dark .dark\:bg-slate-700 { background-color: #3a211f !important; }
        .dark .dark\:border-slate-900,
        .dark .dark\:border-slate-800,
        .dark .dark\:border-slate-700 { border-color: var(--mojo-dark-border) !important; }
        .dark .dark\:text-slate-100,
        .dark .dark\:text-slate-200 { color: #fff7ed !important; }
        .dark .dark\:text-slate-300,
        .dark .dark\:text-slate-400 { color: #d6b8a6 !important; }
        .dark .text-slate-400 { color: #d6b8a6 !important; }
        .dark .text-slate-500 { color: #c9a998 !important; }
        .dark .text-slate-600 { color: #e3c8ba !important; }
        .dark .text-slate-700 { color: #f5e8df !important; }
        .dark .dark\:hover\:bg-slate-700:hover,
        .dark .dark\:hover\:bg-slate-800:hover { background-color: #3a211f !important; }
        .modal-overlay { position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 1rem; opacity: 0; pointer-events: none; transition: opacity .2s; }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box { background: white; border-radius: 1rem; max-width: 540px; width: 100%; box-shadow: 0 25px 50px rgba(0,0,0,.2); transform: scale(.95) translateY(8px); transition: transform .25s cubic-bezier(.4,0,.2,1); max-height: 90vh; overflow-y: auto; }
        .modal-overlay.open .modal-box { transform: scale(1) translateY(0); }
        .dark .modal-box { background: var(--mojo-dark-card); }
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
<body class="bg-slate-50 dark:bg-[#180f12] text-slate-900 dark:text-orange-50 antialiased">
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
            const mainHeader = document.getElementById('main-header');
            const setSidebarCollapsed = collapsed => {
                sidebar?.classList.toggle('collapsed', collapsed);
                document.body.classList.toggle('sidebar-collapsed', collapsed);
                localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
                if (mainContent) mainContent.style.marginLeft = collapsed ? '4.5rem' : '';
                if (mainHeader) mainHeader.style.left = collapsed ? '4.5rem' : '';
            };

            document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
                setSidebarCollapsed(! sidebar?.classList.contains('collapsed'));
            });

            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                setSidebarCollapsed(true);
            }

            document.querySelectorAll('[data-auto-dismiss]').forEach(element => {
                setTimeout(() => element.remove(), parseInt(element.dataset.autoDismiss) || 4000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
