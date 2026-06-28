@props([
    'title' => 'Dashboard',
    'role' => null,
    'unreadCount' => 0,
])

@php
    $user = auth()->user();
    $roleValue = $role ?? $user?->role ?? 'TENANT';
    $roleLabels = [
        'ADMIN' => 'Admin',
        'EVENT_ORGANIZER' => 'Event Organizer',
        'TENANT' => 'Tenant',
        'MASYARAKAT' => 'Masyarakat',
    ];
    $rolePrefix = match ($roleValue) {
        'ADMIN' => 'admin',
        'EVENT_ORGANIZER' => 'pic',
        'MASYARAKAT' => 'masyarakat',
        default => 'tenant',
    };
@endphp

<header class="fixed top-0 right-0 left-0 lg:left-64 z-30 bg-white dark:bg-brand-dark-surface border-b border-slate-200 dark:border-brand-dark-border h-16 flex items-center px-4 gap-3 transition-all duration-200" id="main-header">
    <button onclick="document.getElementById('sidebar')?.classList.toggle('collapsed')" class="lg:hidden btn btn-ghost btn-icon mr-1">
        <i data-lucide="menu" class="w-5 h-5"></i>
    </button>

    <button id="sidebar-toggle" class="hidden lg:flex btn btn-ghost btn-icon">
        <i data-lucide="panel-left" class="w-5 h-5"></i>
    </button>

    <div class="flex-1 min-w-0">
        <h1 class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate" id="page-breadcrumb">
            {{ $title }}
        </h1>
    </div>

    <div class="flex items-center gap-2">
        <button onclick="toggleTheme()" class="btn btn-ghost btn-icon" title="Toggle tema">
            <i data-lucide="moon" class="theme-icon-moon w-4 h-4"></i>
            <i data-lucide="sun" class="theme-icon-sun w-4 h-4 hidden"></i>
        </button>

        <a href="{{ url($rolePrefix . '/notifications') }}" class="btn btn-ghost btn-icon relative" title="Notifikasi">
            <i data-lucide="bell" class="w-4 h-4"></i>
            @if ($unreadCount > 0)
                <span id="notif-count" class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </a>

        <div class="relative">
            <button type="button"
                    onclick="this.parentElement.querySelector('[data-dropdown]').classList.toggle('hidden')"
                    class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-brand-dark-card transition-colors">
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm shrink-0">
                    {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <div class="text-sm font-semibold text-slate-700 dark:text-orange-50 leading-tight max-w-[140px] truncate">
                        {{ $user?->name ?? 'User' }}
                    </div>
                    <div class="text-xs text-slate-400 dark:text-orange-200/60">{{ $roleLabels[$roleValue] ?? $roleValue }}</div>
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 ml-0.5"></i>
            </button>

            <div data-dropdown class="hidden absolute right-0 top-full mt-1 w-48 bg-white dark:bg-brand-dark-surface border border-slate-200 dark:border-brand-dark-border rounded-xl shadow-xl py-1 z-50">
                <a href="{{ route('profile') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-slate-600 dark:text-orange-100 hover:bg-slate-50 dark:hover:bg-brand-dark-card">
                    <i data-lucide="user" class="w-4 h-4"></i> Profil
                </a>

                <hr class="my-1 border-slate-100 dark:border-brand-dark-border">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
