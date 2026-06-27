<x-layouts.app title="Manajemen Venue" current-page="venues" role="ADMIN">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Manajemen Venue</h2>
            <p class="page-subtitle">Kelola tempat pelaksanaan event daerah Mojokerto</p>
        </div>
        <a href="{{ route('admin.venues.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Venue
        </a>
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('admin.venues.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-60">
                <label class="form-label text-xs" for="search">Cari Venue</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama atau alamat venue..." class="form-input pl-8 text-sm py-1.5">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    @if ($venues->isEmpty())
        <div class="card">
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="building-2" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Belum ada venue terdaftar</p>
                <a href="{{ route('admin.venues.create') }}" class="btn btn-primary btn-sm mt-4">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Venue Pertama
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($venues as $venue)
                @php
                    $approvedBooking = $venue->venueBookings->firstWhere('status', 'APPROVED');
                    $requestedBooking = $venue->venueBookings->firstWhere('status', 'PENDING');

                    if ($approvedBooking) {
                        $availabilityLabel = 'Unavailable until ' . $approvedBooking->booking_end->format('d M Y H:i');
                        $availabilityClass = 'badge-red';
                        $availabilityIcon = 'x-circle';
                    } elseif ($requestedBooking) {
                        $availabilityLabel = 'Requested';
                        $availabilityClass = 'badge-yellow';
                        $availabilityIcon = 'clock';
                    } else {
                        $availabilityLabel = 'Available';
                        $availabilityClass = 'badge-green';
                        $availabilityIcon = 'check-circle';
                    }
                @endphp

                <div class="card group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    <div class="relative w-full h-36 rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        @if ($venue->image_url)
                            <img src="{{ $venue->image_url }}" alt="Foto {{ $venue->name }}" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/10"></div>
                        @else
                            <i data-lucide="building-2" class="w-10 h-10 text-slate-300"></i>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="badge {{ $availabilityClass }} shadow flex items-center gap-1">
                                <i data-lucide="{{ $availabilityIcon }}" class="w-3 h-3"></i>
                                {{ $availabilityLabel }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-semibold text-slate-800 dark:text-slate-100 mb-1">{{ $venue->name }}</h3>
                    <div class="flex items-start gap-1.5 text-slate-400 text-xs mb-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 flex-shrink-0 mt-0.5"></i>
                        <span class="line-clamp-1">{{ $venue->address }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 mb-3">
                        <span class="flex items-center gap-1">
                            <i data-lucide="users" class="w-3 h-3"></i>
                            {{ number_format($venue->capacity) }} orang
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="tag" class="w-3 h-3"></i>
                            Rp {{ number_format((float) $venue->lowest_price, 0, ',', '.') }} - Rp {{ number_format((float) $venue->highest_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="text-xs text-slate-400 mb-4">
                        {{ $venue->available_from?->format('d M Y H:i') }} - {{ $venue->available_to?->format('d M Y H:i') }}
                    </div>

                    <div class="mb-4">
                        <span class="badge {{ $availabilityClass }}">
                            {{ $availabilityLabel }}
                        </span>
                    </div>

                    <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('admin.venues.show', $venue) }}" class="btn btn-secondary btn-sm flex-1 justify-center">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            Detail
                        </a>
                        <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit">
                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.venues.destroy', $venue) }}" class="inline" onsubmit="return confirm('Hapus venue {{ $venue->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm btn-icon text-red-400" title="Hapus">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $venues->links() }}
        </div>
    @endif
</x-layouts.app>
