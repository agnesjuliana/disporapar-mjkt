@php
    $isEdit = isset($venue);
@endphp

@csrf

@if ($isEdit)
    @method('PUT')
@endif

<div class="space-y-5">
    <div>
        <label class="form-label" for="name">Nama Venue <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" required class="form-input" placeholder="Nama venue"
               value="{{ old('name', $venue->name ?? '') }}">
        @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="form-label" for="address">Alamat <span class="text-red-500">*</span></label>
        <textarea name="address" id="address" required rows="2" class="form-textarea" placeholder="Alamat lengkap venue">{{ old('address', $venue->address ?? '') }}</textarea>
        @error('address') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="form-label" for="capacity">Kapasitas (orang) <span class="text-red-500">*</span></label>
            <input type="number" name="capacity" id="capacity" required class="form-input" min="0"
                   value="{{ old('capacity', $venue->capacity ?? 0) }}">
            @error('capacity') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="form-label" for="lowest_price">Harga Terendah <span class="text-red-500">*</span></label>
            <input type="number" name="lowest_price" id="lowest_price" required class="form-input" min="0" step="0.01"
                   value="{{ old('lowest_price', $venue->lowest_price ?? 0) }}">
            @error('lowest_price') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="form-label" for="highest_price">Harga Tertinggi <span class="text-red-500">*</span></label>
            <input type="number" name="highest_price" id="highest_price" required class="form-input" min="0" step="0.01"
                   value="{{ old('highest_price', $venue->highest_price ?? 0) }}">
            @error('highest_price') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="form-label" for="available_from">Tersedia Dari <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="available_from" id="available_from" required class="form-input"
                   value="{{ old('available_from', isset($venue) ? $venue->available_from?->format('Y-m-d\TH:i') : '') }}">
            @error('available_from') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="form-label" for="available_to">Tersedia Sampai <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="available_to" id="available_to" required class="form-input"
                   value="{{ old('available_to', isset($venue) ? $venue->available_to?->format('Y-m-d\TH:i') : '') }}">
            @error('available_to') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="form-label" for="description">Deskripsi <span class="text-red-500">*</span></label>
        <textarea name="description" id="description" required rows="3" class="form-textarea" placeholder="Deskripsi venue dan fasilitas utama">{{ old('description', $venue->description ?? '') }}</textarea>
        @error('description') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="btn btn-primary">
            <i data-lucide="save" class="w-4 h-4"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Venue' }}
        </button>
        <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</div>
