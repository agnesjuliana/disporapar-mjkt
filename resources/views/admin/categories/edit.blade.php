<x-layouts.app title="Edit Kategori" current-page="categories" role="ADMIN">
    <div class="mb-5">
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Manajemen Kategori
        </a>
        <h2 class="page-title mt-2">Edit Kategori</h2>
        <p class="text-sm text-slate-400">Mengedit: <strong>{{ $category->name }}</strong></p>
    </div>

    <div class="max-w-xl">
        <div class="card">
            @include('admin.categories._form')
        </div>
    </div>
</x-layouts.app>
