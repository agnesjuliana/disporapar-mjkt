@props([
    'role' => 'TENANT',
    'currentPage' => '',
])

@php
    use App\Support\RoleMenu;

    $user = auth()->user();
    $roleValue = $role ?: 'TENANT';
    $config = RoleMenu::for($roleValue);
    $footerRole = $config['label'];
@endphp

<aside id="sidebar" class="fixed left-0 top-0 h-full w-64 z-40 bg-slate-950 flex flex-col overflow-hidden border-r border-slate-800 -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <div class="flex items-center gap-3 px-4 h-16 border-b border-slate-800 flex-shrink-0">
        <div class="sidebar-logo-icon w-9 h-9 rounded-xl {{ $config['color'] }} flex items-center justify-center flex-shrink-0">
            <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5 text-white"></i>
        </div>
        <div class="sidebar-logo-text overflow-hidden">
            <div class="text-white font-bold text-sm leading-tight">{{ config('app.name', 'WARTA WILWATIKTA') }}</div>
            <div class="text-slate-400 text-xs">{{ $config['subtitle'] }}</div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">
        <p class="sidebar-group-label text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-2 py-2">
            Menu {{ $roleValue === 'ADMIN' ? 'Utama' : '' }}
        </p>

        @foreach ($config['items'] as $item)
            <a href="{{ url($item['href']) }}" class="nav-item {{ $currentPage === $item['page'] ? 'active' : '' }}">
                <i data-lucide="{{ $item['icon'] }}" class="nav-icon"></i>
                <span class="nav-label">{{ $item['label'] }}</span>
            </a>

            @foreach ($item['children'] ?? [] as $child)
                <a href="{{ url($child['href']) }}" class="nav-item ml-5 py-2 text-xs {{ $currentPage === $child['page'] ? 'active' : '' }}">
                    <i data-lucide="{{ $child['icon'] }}" class="nav-icon"></i>
                    <span class="nav-label">{{ $child['label'] }}</span>
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="border-t border-slate-800 px-3 py-3 flex-shrink-0">
        <div class="flex items-center gap-3 px-1">
            <div class="w-8 h-8 rounded-full {{ $config['color'] }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr($user?->name ?? $config['fallbackInitial'], 0, 1)) }}
            </div>
            <div class="sidebar-logo-text overflow-hidden">
                <div class="text-white text-sm font-semibold leading-tight truncate">{{ $user?->name ?? 'User' }}</div>
                <div class="text-slate-400 text-xs">{{ $footerRole }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="sidebar-logo-text ml-auto flex-shrink-0">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors" title="Keluar">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 lg:hidden hidden"></div>
