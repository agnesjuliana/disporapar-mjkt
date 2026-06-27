<x-layouts.app title="Detail Venue" current-page="venues" role="ADMIN">
    <div class="mb-5">
        <a href="{{ route('admin.venues.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Manajemen Venue
        </a>
        <div class="flex flex-wrap items-center justify-between gap-3 mt-2">
            <div>
                <h2 class="page-title">{{ $venue->name }}</h2>
                <p class="page-subtitle">Detail data venue</p>
            </div>
            <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-primary btn-sm">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Edit Venue
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card">
            <div class="relative w-full h-64 rounded-xl overflow-hidden mb-6 bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                @if ($venue->image_url)
                    <img src="{{ $venue->image_url }}" alt="Foto {{ $venue->name }}" class="absolute inset-0 w-full h-full object-cover">
                @else
                    <div class="text-center text-slate-400">
                        <i data-lucide="building-2" class="w-12 h-12 mx-auto mb-2"></i>
                        <p class="text-sm">Belum ada foto venue</p>
                    </div>
                @endif
            </div>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama Venue</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $venue->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Kapasitas</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ number_format($venue->capacity) }} orang</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Alamat</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $venue->address }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Harga Terendah</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">Rp {{ number_format((float) $venue->lowest_price, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Harga Tertinggi</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">Rp {{ number_format((float) $venue->highest_price, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tersedia Dari</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $venue->available_from?->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tersedia Sampai</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $venue->available_to?->format('d M Y H:i') }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Deskripsi</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $venue->description }}</dd>
                </div>
            </dl>
        </div>

        <div class="card">
            <h3 class="font-semibold text-slate-900 dark:text-white mb-3">Relasi Data</h3>
            <div class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                <div class="flex items-center justify-between">
                    <span>Event Internal</span>
                    <span class="badge badge-indigo">{{ $venue->events()->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Booking Venue</span>
                    <span class="badge badge-blue">{{ $venue->venueBookings()->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
