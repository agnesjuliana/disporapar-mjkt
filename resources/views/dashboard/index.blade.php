<x-layouts.app title="Home" current-page="home" :role="$menu['role']">
    <div class="space-y-6">
        <section class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl {{ $menu['color'] }} flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="{{ $menu['icon'] }}" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Selamat datang,</p>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ config('app.description') }}</p>
                    </div>
                </div>

                <div class="inline-flex items-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-900 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    {{ $menu['label'] }}
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="card lg:col-span-1">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-4">Detail Profil</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Role</dt>
                        <dd class="mt-1"><span class="badge badge-indigo">{{ $menu['label'] }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</dt>
                        <dd class="mt-1"><x-ui.status-badge :status="$user->status" /></dd>
                    </div>
                </dl>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($menu['items'] as $item)
                    @continue($item['page'] === 'home')

                    <a href="{{ url($item['href']) }}" class="card group hover:border-indigo-300 dark:hover:border-indigo-500">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-slate-600 dark:text-slate-300 group-hover:text-indigo-500">
                                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-900 dark:text-white">{{ $item['label'] }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $item['description'] }}</p>
                            </div>
                        </div>
                    </a>

                    @foreach ($item['children'] ?? [] as $child)
                        <a href="{{ url($child['href']) }}" class="card group hover:border-indigo-300 dark:hover:border-indigo-500">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-slate-600 dark:text-slate-300 group-hover:text-indigo-500">
                                    <i data-lucide="{{ $child['icon'] }}" class="w-5 h-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ $child['label'] }}</h3>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ $child['description'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.app>
