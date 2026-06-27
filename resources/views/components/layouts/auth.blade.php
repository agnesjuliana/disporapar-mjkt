<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }} | {{ config('app.name', 'WARTA WILWATIKTA') }}</title>
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
                        },
                        brand: {
                            DEFAULT: '#9f1239',
                            terracotta: '#c2410c',
                            gold: '#d97706',
                            teal: '#0f766e',
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
    <style>
        @php
            $landmarkImage = collect([
                'images/mojokerto-landmark.jpg',
                'images/mojokerto-landmark.jpeg',
                'images/mojokerto-landmark.png',
                'images/auth-landmark.jpg',
                'images/auth-landmark.png',
            ])->first(fn ($path) => file_exists(public_path($path)));
        @endphp
        :root {
            --mojo-red: #9f1239;
            --mojo-terracotta: #c2410c;
            --mojo-gold: #d97706;
            --mojo-teal: #0f766e;
            --mojo-ink: #1a1114;
        }
        * { font-family: Inter, system-ui, sans-serif; box-sizing: border-box; }
        .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem; border-radius: .5rem; font-size: .875rem; font-weight: 500; cursor: pointer; border: 0; transition: all .15s; text-decoration: none; line-height: 1.4; }
        .btn:disabled { opacity: .5; pointer-events: none; }
        .btn-primary { background: linear-gradient(135deg, var(--mojo-red), var(--mojo-terracotta)); color: white; }
        .btn-secondary { background: #f1f5f9; color: #374151; border: 1px solid #e2e8f0; }
        .form-input, .form-textarea { width: 100%; padding: .55rem .875rem; border: 1px solid #d1d5db; border-radius: .5rem; font-size: .875rem; color: #111827; background: white; outline: none; transition: border-color .15s, box-shadow .15s; }
        .form-input:focus, .form-textarea:focus { border-color: var(--mojo-terracotta); box-shadow: 0 0 0 3px rgba(194,65,12,.14); }
        .form-label { display: block; font-size: .875rem; font-weight: 500; color: #374151; margin-bottom: .375rem; }
        .bg-indigo-600, .bg-indigo-500 { background-color: var(--mojo-red) !important; }
        .text-indigo-300, .text-indigo-400 { color: #fdba74 !important; }
        .hover\:text-indigo-300:hover, .hover\:text-indigo-200:hover { color: #fed7aa !important; }
        .hover\:border-indigo-500:hover { border-color: var(--mojo-gold) !important; }
        .focus\:border-indigo-500:focus { border-color: var(--mojo-terracotta) !important; }
        .auth-content > .absolute.inset-0 { display: none; }
        .auth-content > .relative { width: 100%; max-width: 34rem; }
        .auth-content .bg-slate-900 {
            background: rgba(36, 20, 23, .82) !important;
            border-color: rgba(254, 215, 170, .16) !important;
            box-shadow: 0 28px 70px rgba(0, 0, 0, .28);
            backdrop-filter: blur(18px);
        }
        .auth-content .bg-slate-800 {
            background: rgba(24, 15, 18, .74) !important;
            border-color: rgba(254, 215, 170, .18) !important;
        }
        .auth-visual {
            background:
                linear-gradient(90deg, rgba(24,15,18,.9), rgba(24,15,18,.34)),
                radial-gradient(circle at 28% 18%, rgba(217,119,6,.32), transparent 30%),
                radial-gradient(circle at 84% 74%, rgba(15,118,110,.24), transparent 34%),
                @if ($landmarkImage)
                    url('{{ asset($landmarkImage) }}')
                @else
                    linear-gradient(135deg, #5f161f 0%, #9a3412 48%, #0f766e 100%)
                @endif;
            background-size: cover;
            background-position: center;
        }
        .auth-visual::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(120deg, rgba(24,15,18,.18), rgba(24,15,18,.78)),
                repeating-linear-gradient(90deg, rgba(255,237,213,.08) 0 1px, transparent 1px 42px);
        }
        .auth-brand-mark {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            filter: drop-shadow(0 14px 22px rgba(0,0,0,.28));
        }
        .auth-theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 50;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: .75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffedd5;
            background: rgba(24,15,18,.72);
            border: 1px solid rgba(254,215,170,.22);
            box-shadow: 0 12px 30px rgba(0,0,0,.18);
            backdrop-filter: blur(14px);
            transition: background .15s, color .15s, border-color .15s, transform .15s;
        }
        .auth-theme-toggle:hover {
            color: #ffffff;
            background: rgba(36,20,23,.9);
            border-color: rgba(254,215,170,.38);
            transform: translateY(-1px);
        }
        html:not(.dark) body {
            background: #fff7ed;
            color: #1f2937;
        }
        html:not(.dark) .auth-theme-toggle {
            color: #5f161f;
            background: rgba(255,255,255,.82);
            border-color: rgba(194,65,12,.18);
            box-shadow: 0 12px 30px rgba(159,18,57,.12);
        }
        html:not(.dark) .auth-theme-toggle:hover {
            color: #9f1239;
            background: rgba(255,255,255,.96);
            border-color: rgba(194,65,12,.34);
        }
        html:not(.dark) .auth-panel-bg {
            background: radial-gradient(circle at 0% 0%, rgba(217,119,6,.2), transparent 34%), linear-gradient(135deg, #fff7ed, #ffffff 46%, #f0fdfa);
        }
        html:not(.dark) .auth-panel-grid {
            opacity: .18;
        }
        html:not(.dark) .auth-content .bg-slate-900 {
            background: rgba(255,255,255,.88) !important;
            border-color: rgba(194,65,12,.16) !important;
            box-shadow: 0 24px 60px rgba(159,18,57,.12);
        }
        html:not(.dark) .auth-content .bg-slate-800 {
            background: rgba(255,247,237,.78) !important;
            border-color: rgba(194,65,12,.14) !important;
        }
        html:not(.dark) .auth-content .border-slate-800,
        html:not(.dark) .auth-content .border-slate-700 {
            border-color: rgba(194,65,12,.16) !important;
        }
        html:not(.dark) .auth-content .text-white {
            color: #1f2937 !important;
        }
        html:not(.dark) .auth-content .text-slate-300,
        html:not(.dark) .auth-content .text-slate-400 {
            color: #64748b !important;
        }
        html:not(.dark) .auth-content .text-slate-500,
        html:not(.dark) .auth-content .text-slate-600 {
            color: #6b7280 !important;
        }
        html:not(.dark) .auth-content .form-label {
            color: #374151 !important;
        }
        html:not(.dark) .auth-content .form-input,
        html:not(.dark) .auth-content .form-textarea {
            background: #ffffff !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
        }
        html:not(.dark) .auth-content input::placeholder,
        html:not(.dark) .auth-content textarea::placeholder {
            color: #9ca3af !important;
        }
        html:not(.dark) .auth-content .bg-slate-800.flex-1,
        html:not(.dark) .auth-content .bg-slate-900.flex-1 {
            background: linear-gradient(90deg, transparent, rgba(194,65,12,.24), transparent) !important;
        }
        html:not(.dark) .auth-content a.btn.bg-slate-900 {
            background: #fff7ed !important;
            border-color: rgba(194,65,12,.24) !important;
            color: #9f1239 !important;
            box-shadow: 0 10px 24px rgba(159,18,57,.08);
        }
        html:not(.dark) .auth-content a.btn.bg-slate-900:hover {
            background: #ffedd5 !important;
            border-color: rgba(194,65,12,.42) !important;
            color: #881337 !important;
        }
        html:not(.dark) .auth-visual .text-white {
            color: #ffffff !important;
        }
        html:not(.dark) .auth-visual .text-orange-50\/78 {
            color: rgba(255,247,237,.82) !important;
        }
        html:not(.dark) .auth-visual .text-orange-100\/70,
        html:not(.dark) .auth-visual .text-orange-100\/68 {
            color: rgba(255,237,213,.72) !important;
        }
        html:not(.dark) .auth-visual .text-orange-200\/80 {
            color: rgba(254,215,170,.9) !important;
        }
        .fade-in { animation: fadeIn .35s ease-out both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-[#180f12] text-white">
    <button type="button" onclick="toggleAuthTheme()" class="auth-theme-toggle" title="Toggle tema" aria-label="Toggle tema">
        <i data-lucide="moon" class="theme-icon-moon w-4 h-4"></i>
        <i data-lucide="sun" class="theme-icon-sun w-4 h-4 hidden"></i>
    </button>

    <main class="min-h-screen grid grid-cols-1 lg:grid-cols-[minmax(420px,46%)_1fr]">
        <section class="relative min-h-screen overflow-y-auto px-5 py-8 sm:px-10 lg:px-14 flex items-center">
            <div class="auth-panel-grid absolute inset-0 opacity-[0.08]"
                 style="background-image:linear-gradient(#fed7aa 1px,transparent 1px),linear-gradient(90deg,#fed7aa 1px,transparent 1px);background-size:42px 42px"></div>
            <div class="auth-panel-bg absolute inset-0 bg-[radial-gradient(circle_at_0%_0%,rgba(159,18,57,.42),transparent_34%),linear-gradient(135deg,#180f12,#241417_48%,#3a1816)]"></div>

            <div class="auth-content relative z-10 w-full flex justify-center">
                {{ $slot }}
            </div>
        </section>

        <aside class="auth-visual relative hidden lg:block min-h-screen overflow-hidden">
            <div class="relative z-10 h-full flex flex-col justify-between p-10 xl:p-14">
                <div class="flex items-center gap-3">
                    <span class="auth-brand-mark">
                        <img src="{{ asset('images/brand-white.png') }}" alt="{{ config('app.name', 'WARTA WILWATIKTA') }}" class="w-full h-full object-contain">
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
            document.querySelectorAll('.theme-icon-moon').forEach(icon => icon.style.display = isDark ? 'none' : 'block');
            document.querySelectorAll('.theme-icon-sun').forEach(icon => icon.style.display = isDark ? 'block' : 'none');
        }

        function toggleAuthTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcon(document.documentElement.classList.contains('dark'));

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
