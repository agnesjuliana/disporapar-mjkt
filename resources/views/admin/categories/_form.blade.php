@php
    $action = $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store');
@endphp

<form method="POST" action="{{ $action }}">
    @csrf
    @if ($category->exists)
        @method('PUT')
    @endif

    <div class="space-y-4">
        <div>
            <label class="form-label" for="name">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" required class="form-input" placeholder="Mis. Kuliner" value="{{ old('name', $category->name) }}">
            @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="icon">Ikon (nama ikon Lucide)</label>
            <input type="text" name="icon" id="icon" class="form-input" placeholder="Mis. utensils" value="{{ old('icon', $category->icon) }}">
            <p class="mt-1 text-xs text-slate-400">Opsional. Lihat daftar nama ikon di <a href="https://lucide.dev/icons" target="_blank" rel="noopener" class="underline">lucide.dev/icons</a>.</p>
            @error('icon')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex flex-wrap gap-3 mt-5">
        <button type="submit" class="btn btn-primary">
            <i data-lucide="save" class="w-4 h-4"></i>
            Simpan
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
