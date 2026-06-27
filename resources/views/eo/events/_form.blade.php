@php
    $method = $method ?? 'POST';
    $submitLabel = $submitLabel ?? 'Simpan sebagai Draft';
    $venueType = old('venue_type', $event->venue_type ?: 'INTERNAL');
    $formatDateTime = fn ($value) => $value ? $value->format('Y-m-d\TH:i') : '';
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="event-form">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card mb-4">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 text-slate-400"></i>
            Informasi Dasar
        </h3>
        <div class="space-y-4">
            <div>
                <label class="form-label" for="name">Nama Event <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required class="form-input" placeholder="Judul event yang menarik" value="{{ old('name', $event->name) }}">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label" for="description">Deskripsi</label>
                <textarea name="description" id="description" rows="4" class="form-textarea" placeholder="Jelaskan event, persyaratan tenant, kategori produk yang diperbolehkan, dll.">{{ old('description', $event->description) }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label" for="banner">Banner Event</label>
                @if ($event->banner_url)
                    <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="w-full h-36 object-cover rounded-xl mb-3">
                @endif
                <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-5 text-center cursor-pointer hover:border-emerald-400 transition-colors" onclick="document.getElementById('banner').click()">
                    <i data-lucide="image-plus" class="w-7 h-7 text-slate-300 mx-auto mb-1.5"></i>
                    <p class="text-sm text-slate-400">Klik upload banner event</p>
                    <p class="text-xs text-slate-300">JPG, PNG, WEBP. Maksimal 2MB.</p>
                </div>
                <input type="file" name="banner" id="banner" accept="image/*" class="hidden" onchange="previewBanner(this)">
                <img id="banner-preview" alt="" class="mt-2 w-full h-36 object-cover rounded-xl hidden">
                @error('banner')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
            Venue
        </h3>
        <div class="space-y-4">
            <div>
                <label class="form-label">Tipe Venue <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="venue_type" value="INTERNAL" class="peer sr-only" @checked($venueType === 'INTERNAL') onchange="toggleVenueType()">
                        <div class="p-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-950/30 transition-all">
                            <i data-lucide="building-2" class="w-5 h-5 text-slate-400 mb-1"></i>
                            <p class="text-sm font-medium">Venue Disporapar</p>
                            <p class="text-xs text-slate-400">Venue resmi milik Disporapar</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="venue_type" value="EXTERNAL" class="peer sr-only" @checked($venueType === 'EXTERNAL') onchange="toggleVenueType()">
                        <div class="p-4 rounded-xl border-2 border-slate-200 dark:border-slate-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-950/30 transition-all">
                            <i data-lucide="map" class="w-5 h-5 text-slate-400 mb-1"></i>
                            <p class="text-sm font-medium">Venue Eksternal</p>
                            <p class="text-xs text-slate-400">Venue di luar Disporapar</p>
                        </div>
                    </label>
                </div>
                @error('venue_type')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div id="internal-venue">
                <label class="form-label" for="venue_id">Pilih Venue <span class="text-red-500">*</span></label>
                <select name="venue_id" id="venue_id" class="form-select">
                    <option value="">-- Pilih venue --</option>
                    @foreach ($venues as $venue)
                        <option value="{{ $venue->id }}" @selected(old('venue_id', $event->venue_id) === $venue->id)>
                            {{ $venue->name }} (Kap. {{ number_format($venue->capacity) }} orang)
                        </option>
                    @endforeach
                </select>
                @error('venue_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div id="external-venue" class="space-y-3">
                <div>
                    <label class="form-label" for="external_venue_name">Nama Venue <span class="text-red-500">*</span></label>
                    <input type="text" name="external_venue_name" id="external_venue_name" class="form-input" placeholder="Nama venue eksternal" value="{{ old('external_venue_name', $event->external_venue_name) }}">
                    @error('external_venue_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label" for="external_venue_address">Alamat Venue</label>
                    <textarea name="external_venue_address" id="external_venue_address" rows="2" class="form-textarea" placeholder="Alamat lengkap venue">{{ old('external_venue_address', $event->external_venue_address) }}</textarea>
                    @error('external_venue_address')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label" for="external_venue_capacity">Kapasitas Venue Eksternal</label>
                    <input type="number" name="external_venue_capacity" id="external_venue_capacity" class="form-input" min="0" value="{{ old('external_venue_capacity', $event->external_venue_capacity ?? 0) }}">
                    @error('external_venue_capacity')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
            Jadwal & Kapasitas
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label" for="event_start">Tanggal & Waktu Mulai <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="event_start" id="event_start" required class="form-input" value="{{ old('event_start', $formatDateTime($event->event_start)) }}">
                @error('event_start')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="event_end">Tanggal & Waktu Selesai <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="event_end" id="event_end" required class="form-input" value="{{ old('event_end', $formatDateTime($event->event_end)) }}">
                @error('event_end')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="registration_deadline">Deadline Pendaftaran</label>
                <input type="datetime-local" name="registration_deadline" id="registration_deadline" class="form-input" value="{{ old('registration_deadline', $formatDateTime($event->registration_deadline)) }}">
                @error('registration_deadline')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="slot_size">Ukuran Slot Default (m²)</label>
                <input type="number" name="slot_size" id="slot_size" class="form-input" min="0" value="{{ old('slot_size', $event->slot_size ?? 0) }}">
                @error('slot_size')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="form-label" for="capacity">Kapasitas Peserta</label>
                <input type="number" name="capacity" id="capacity" class="form-input" min="0" value="{{ old('capacity', $event->capacity ?? 0) }}">
                @error('capacity')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn btn-primary">
            <i data-lucide="save" class="w-4 h-4"></i>
            {{ $submitLabel }}
        </button>
        <a href="{{ $event->exists ? route('eo.events.show', $event) : route('eo.events.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>

@push('scripts')
    <script>
        function toggleVenueType() {
            const type = document.querySelector('input[name="venue_type"]:checked')?.value;
            document.getElementById('internal-venue')?.classList.toggle('hidden', type !== 'INTERNAL');
            document.getElementById('external-venue')?.classList.toggle('hidden', type !== 'EXTERNAL');
            const venueInput = document.getElementById('venue_id');
            if (venueInput) venueInput.required = type === 'INTERNAL';
        }

        function previewBanner(input) {
            const preview = document.getElementById('banner-preview');
            if (!preview || !input.files?.[0]) return;
            const reader = new FileReader();
            reader.onload = event => {
                preview.src = event.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }

        document.addEventListener('DOMContentLoaded', toggleVenueType);
    </script>
@endpush
