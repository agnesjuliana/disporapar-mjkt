<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'WARTA WILWATIKTA') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white flex items-center justify-center p-6">
    <main class="text-center space-y-6">
        <div>
            <h1 class="text-3xl font-bold">Welcome to landing page</h1>
            <p class="mt-3 text-sm text-slate-300">{{ config('app.description') }}</p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-slate-200">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                    Login
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg border border-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-900">
                    Register
                </a>
            @endauth
        </div>
    </main>
</body>
</html>
