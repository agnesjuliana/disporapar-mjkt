{{--
    Shared head assets for every layout.
    To change a brand color, edit the tailwind.config colors here (and update
    the matching --mojo-* variable in public/css/app.css section 1).
--}}

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: '#9f1239',
                        50:  '#fff7ed',
                        100: '#ffedd5',
                        500: '#c2410c',
                        600: '#9f1239',
                        700: '#881337',
                        800: '#5f161f',
                    },
                    brand: {
                        DEFAULT:    '#9f1239',
                        red:        '#9f1239',
                        terracotta: '#c2410c',
                        gold:       '#d97706',
                        teal:       '#0f766e',
                        ink:        '#1f2937',
                        surface:    '#fff7ed',
                        'dark-bg':      '#180f12',
                        'dark-surface': '#241417',
                        'dark-card':    '#2b1a1c',
                        'dark-border':  '#56312a',
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
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
