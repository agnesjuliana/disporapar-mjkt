<x-layouts.app title="Venue Booking" current-page="venue-bookings" role="EVENT_ORGANIZER">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Venue Booking</h2>
            <p class="page-subtitle">Pantau request booking venue yang Anda ajukan</p>
        </div>
        <a href="{{ route('eo.daftar-venue') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Ajukan Booking
        </a>
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('eo.venue-booking') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="form-label text-xs" for="status">Status</label>
                <select name="status" id="status" class="form-select text-sm py-1.5">
                    <option value="">Semua</option>
                    @foreach (['PENDING' => 'Menunggu', 'APPROVED' => 'Disetujui', 'REJECTED' => 'Ditolak', 'CANCELLED' => 'Dibatalkan'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('eo.venue-booking') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($bookings->isEmpty())
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="calendar-off" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Belum ada request booking venue</p>
                <a href="{{ route('eo.daftar-venue') }}" class="btn btn-primary btn-sm mt-4">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                    Cari Venue
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Venue</th>
                            <th>Event</th>
                            <th>Tanggal Booking</th>
                            <th>Requested</th>
                            <th>Harga Final</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $bookings->firstItem() + $loop->index }}</td>
                                <td>
                                    <p class="font-medium text-sm text-slate-800 dark:text-slate-100">{{ $booking->venue?->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->venue?->address ?? '-' }}</p>
                                </td>
                                <td class="text-sm text-slate-500">{{ $booking->event?->name ?? '-' }}</td>
                                <td class="text-xs text-slate-500 whitespace-nowrap">
                                    <p>{{ $booking->booking_start?->format('d M Y H:i') }}</p>
                                    <p class="text-slate-400">s/d {{ $booking->booking_end?->format('d M Y H:i') }}</p>
                                </td>
                                <td class="text-xs text-slate-500 whitespace-nowrap">{{ $booking->requested_at?->format('d M Y H:i') }}</td>
                                <td class="text-sm text-slate-500">
                                    {{ $booking->final_price ? 'Rp ' . number_format((float) $booking->final_price, 0, ',', '.') : '-' }}
                                </td>
                                <td><x-ui.status-badge :status="$booking->status" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
