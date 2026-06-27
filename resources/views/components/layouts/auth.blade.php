<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }} | {{ config('app.name', 'WARTA WILWATIKTA') }}</title>
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
        :root {
            --mojo-red: #9f1239;
            --mojo-terracotta: #c2410c;
            --mojo-gold: #d97706;
            --mojo-teal: #0f766e;
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
        .fade-in { animation: fadeIn .35s ease-out both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4 relative overflow-hidden">
    {{ $slot }}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
