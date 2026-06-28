<x-layouts.app title="Daftar Venue" current-page="venues" role="EVENT_ORGANIZER">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Daftar Venue</h2>
            <p class="page-subtitle">Cari venue yang tersedia dan ajukan booking untuk event Anda</p>
        </div>
        <a href="{{ route('eo.venue-booking') }}" class="btn btn-secondary btn-sm">
            <i data-lucide="calendar-check" class="w-4 h-4"></i>
            Lihat Booking Saya
        </a>
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('eo.daftar-venue') }}" class="flex flex-wrap gap-3 items-end">
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
            <a href="{{ route('eo.daftar-venue') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    @if ($venues->isEmpty())
        <div class="card">
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="building-2" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Tidak ada venue ditemukan</p>
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
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 shrink-0 mt-0.5"></i>
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

                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-4">{{ $venue->description }}</p>

                    <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" onclick="openModal('book-{{ $venue->id }}')" class="btn btn-primary btn-sm flex-1 justify-center">
                            <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                            Booking Venue
                        </button>
                    </div>
                </div>

                <div id="book-{{ $venue->id }}" class="modal-overlay">
                    <div class="modal-box p-6">
                        <h3 class="font-semibold text-lg mb-1">Booking {{ $venue->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Ajukan permintaan booking venue ke admin.</p>

                        <form method="POST" action="{{ route('eo.venue-booking.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="venue_id" value="{{ $venue->id }}">

                            <div>
                                <label class="form-label" for="event_id_{{ $venue->id }}">Event</label>
                                <select name="event_id" id="event_id_{{ $venue->id }}" class="form-select">
                                    <option value="">Tanpa event / belum ditentukan</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }}{{ $event->event_start ? ' - ' . $event->event_start->format('d M Y') : '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label" for="booking_start_{{ $venue->id }}">Mulai Booking</label>
                                    <input type="datetime-local" name="booking_start" id="booking_start_{{ $venue->id }}" class="form-input" required>
                                </div>
                                <div>
                                    <label class="form-label" for="booking_end_{{ $venue->id }}">Selesai Booking</label>
                                    <input type="datetime-local" name="booking_end" id="booking_end_{{ $venue->id }}" class="form-input" required>
                                </div>
                            </div>

                            <div class="flex gap-2 justify-end">
                                <button type="button" onclick="closeModal('book-{{ $venue->id }}')" class="btn btn-secondary btn-sm">Batal</button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    Kirim Request
                                </button>
                            </div>
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
