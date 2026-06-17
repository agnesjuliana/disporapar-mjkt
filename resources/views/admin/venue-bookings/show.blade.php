<x-layouts.app title="Detail Booking Venue" current-page="venue-bookings" role="ADMIN">
    <div class="mb-5">
        <a href="{{ route('admin.venue-bookings.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Booking Venue
        </a>
        <div class="flex flex-wrap items-center justify-between gap-3 mt-2">
            <div>
                <h2 class="page-title">Detail Booking Venue</h2>
                <p class="page-subtitle">{{ $booking->venue?->name ?? '-' }}</p>
            </div>
            <x-ui.status-badge :status="$booking->status" />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Venue</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $booking->venue?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Organizer</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $booking->organizer?->organization_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Event</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $booking->event?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Requested At</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $booking->requested_at?->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booking Start</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $booking->booking_start?->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booking End</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $booking->booking_end?->format('d M Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Harga Final</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">
                        {{ $booking->final_price ? 'Rp ' . number_format((float) $booking->final_price, 0, ',', '.') : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Disetujui/Diproses Oleh</dt>
                    <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $booking->approver?->name ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        @if ($booking->status === 'PENDING')
            <div class="card h-fit">
                <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Aksi Approval</h3>
                <form method="POST" action="{{ route('admin.venue-bookings.approve', $booking) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label" for="final_price">Harga Final</label>
                        <input type="number" name="final_price" id="final_price" class="form-input" min="0" step="0.01" placeholder="0">
                    </div>
                    <button type="submit" class="btn btn-success w-full justify-center">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Setujui Booking
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.venue-bookings.reject', $booking) }}" class="mt-3" onsubmit="return confirm('Tolak booking venue ini?')">
                    @csrf
                    <button type="submit" class="btn btn-danger w-full justify-center">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                        Tolak Booking
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-layouts.app>
