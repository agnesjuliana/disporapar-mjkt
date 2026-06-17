<x-layouts.app title="Tambah Venue" current-page="venues" role="ADMIN">
    <div class="mb-5">
        <a href="{{ route('admin.venues.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Manajemen Venue
        </a>
        <h2 class="page-title mt-2">Tambah Venue</h2>
        <p class="page-subtitle">Isi data venue sesuai kolom tabel venues.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
        <div class="lg:col-span-2">
            <div class="card">
                <form method="POST" action="{{ route('admin.venues.store') }}">
                    @include('admin.venues._form')
                </form>
            </div>
        </div>

        <div>
            <div class="card bg-indigo-50 dark:bg-indigo-950/30 border-indigo-100 dark:border-indigo-900">
                <h3 class="font-semibold text-indigo-700 dark:text-indigo-300 mb-3 text-sm flex items-center gap-2">
                    <i data-lucide="lightbulb" class="w-4 h-4"></i>
                    Tips Pengisian
                </h3>
                <ul class="space-y-2 text-xs text-indigo-600 dark:text-indigo-400">
                    <li>Gunakan nama venue yang jelas dan mudah dikenali.</li>
                    <li>Isi kapasitas sesuai kemampuan venue.</li>
                    <li>Pastikan harga terendah tidak lebih besar dari harga tertinggi.</li>
                    <li>Tanggal tersedia wajib diisi karena kolom tabel tidak nullable.</li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.app>
