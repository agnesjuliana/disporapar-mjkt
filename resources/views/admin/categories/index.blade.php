<x-layouts.app title="Manajemen Kategori" current-page="categories" role="ADMIN">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Manajemen Kategori</h2>
            <p class="page-subtitle">Kelola kategori event untuk EO, dan minat masyarakat/tenant.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Kategori
        </a>
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-60">
                <label class="form-label text-xs" for="search">Cari Kategori</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama kategori..." class="form-input pl-8 text-sm py-1.5">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($categories->isEmpty())
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="tags" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Belum ada kategori</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm mt-4">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Kategori Pertama
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Jumlah Event</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $categories->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 flex items-center justify-center shrink-0">
                                            <i data-lucide="{{ $category->icon ?: 'tag' }}" class="w-4 h-4"></i>
                                        </div>
                                        <p class="font-medium text-sm">{{ $category->name }}</p>
                                    </div>
                                </td>
                                <td class="text-sm text-slate-500 font-mono">{{ $category->slug }}</td>
                                <td class="text-sm text-slate-500">{{ $category->events_count }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </a>
                                        @if ($category->slug !== 'umum')
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-sm btn-icon text-red-400" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
