<x-layouts.app title="Booking Venue" current-page="venue-bookings" role="ADMIN">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Booking Venue</h2>
            <p class="page-subtitle">Tinjau dan kelola permintaan booking venue dari Event Organizer</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-5">
        @foreach ([
            ['label' => 'Total Booking', 'value' => $stats['total'], 'icon' => 'calendar-check', 'iconClass' => 'bg-orange-50 text-[#9f1239] dark:bg-orange-100 dark:text-[#9f1239]'],
            ['label' => 'Menunggu', 'value' => $stats['pending'], 'icon' => 'clock', 'iconClass' => 'bg-amber-50 text-amber-700 dark:bg-amber-100 dark:text-amber-800'],
            ['label' => 'Disetujui', 'value' => $stats['approved'], 'icon' => 'check-circle', 'iconClass' => 'bg-teal-50 text-teal-700 dark:bg-teal-100 dark:text-teal-800'],
            ['label' => 'Ditolak', 'value' => $stats['rejected'], 'icon' => 'x-circle', 'iconClass' => 'bg-red-50 text-red-700 dark:bg-red-100 dark:text-red-800'],
            ['label' => 'Dibatalkan', 'value' => $stats['cancelled'], 'icon' => 'ban', 'iconClass' => 'bg-stone-100 text-stone-700 dark:bg-stone-100 dark:text-stone-800'],
        ] as $stat)
            <div class="card p-4 min-h-[116px] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl {{ $stat['iconClass'] }} flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-slate-500 dark:text-orange-100/75 font-medium leading-snug">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-orange-50">{{ number_format($stat['value']) }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('admin.venue-bookings.index') }}" class="flex flex-wrap gap-3 items-end">
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
            <a href="{{ route('admin.venue-bookings.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($bookings->isEmpty())
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="calendar-off" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Tidak ada booking venue ditemukan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Venue</th>
                            <th>Organizer</th>
                            <th>Event</th>
                            <th>Tanggal Booking</th>
                            <th>Requested</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $bookings->firstItem() + $loop->index }}</td>
                                <td>
                                    <p class="font-medium text-sm text-slate-800 dark:text-orange-50">{{ $booking->venue?->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-400 dark:text-orange-100/60">{{ $booking->venue?->address ?? '-' }}</p>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-600 dark:text-orange-100/85">{{ $booking->organizer?->organization_name ?? '-' }}</p>
                                    <p class="text-xs text-slate-400 dark:text-orange-100/60">{{ $booking->organizer?->user?->email ?? '' }}</p>
                                </td>
                                <td class="text-sm text-slate-500 dark:text-orange-100/78">{{ $booking->event?->name ?? '-' }}</td>
                                <td class="text-xs text-slate-500 dark:text-orange-100/80 whitespace-nowrap">
                                    <p>{{ $booking->booking_start?->format('d M Y H:i') }}</p>
                                    <p class="text-slate-400 dark:text-orange-100/60">s/d {{ $booking->booking_end?->format('d M Y H:i') }}</p>
                                </td>
                                <td class="text-xs text-slate-500 dark:text-orange-100/78 whitespace-nowrap">{{ $booking->requested_at?->format('d M Y H:i') }}</td>
                                <td><x-ui.status-badge :status="$booking->status" /></td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="{{ route('admin.venue-bookings.show', $booking) }}" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>

                                        @if ($booking->status === 'PENDING')
                                            <button type="button" onclick="openModal('approve-{{ $booking->id }}')" class="btn btn-ghost btn-sm btn-icon text-green-500" title="Setujui">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            </button>

                                            <div id="approve-{{ $booking->id }}" class="modal-overlay">
                                                <div class="modal-box p-6">
                                                    <h3 class="font-semibold mb-3">Setujui Booking Venue</h3>
                                                    <form method="POST" action="{{ route('admin.venue-bookings.approve', $booking) }}">
                                                        @csrf
                                                        <div class="mb-4">
                                                            <label class="form-label" for="final_price_{{ $booking->id }}">Harga Final</label>
                                                            <input type="number" name="final_price" id="final_price_{{ $booking->id }}" class="form-input" min="0" step="0.01" placeholder="0">
                                                            <p class="text-xs text-slate-400 mt-1">Kosongkan jika belum ada biaya final.</p>
                                                        </div>
                                                        <div class="flex gap-2 justify-end">
                                                            <button type="button" onclick="closeModal('approve-{{ $booking->id }}')" class="btn btn-secondary btn-sm">Batal</button>
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                                Setujui
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <form method="POST" action="{{ route('admin.venue-bookings.reject', $booking) }}" onsubmit="return confirm('Tolak booking venue ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-sm btn-icon text-red-500" title="Tolak">
                                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
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
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
