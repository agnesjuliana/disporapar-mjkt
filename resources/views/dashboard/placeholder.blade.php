<x-layouts.app :title="$item['label']" :current-page="$item['page']" :role="$menu['role']">
    <div class="space-y-6">
        <section class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl {{ $menu['color'] }} flex items-center justify-center text-white">
                    <i data-lucide="{{ $item['icon'] }}" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $item['label'] }}</h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $item['description'] }}</p>
                </div>
            </div>
        </section>

        <div class="card">
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Halaman ini sudah terhubung dari dashboard role {{ $menu['label'] }}. Konten modul dapat dilengkapi pada tahap berikutnya.
            </p>
        </div>
    </div>
</x-layouts.app>
