<x-layouts.app title="Manajemen Event" current-page="events" role="EVENT_ORGANIZER">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Event Saya</h2>
            <p class="page-subtitle">Kelola event yang Anda selenggarakan</p>
        </div>
        <a href="{{ route('eo.events.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Buat Event
        </a>
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('eo.events.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-52">
                <label class="form-label text-xs" for="search">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama event..." class="form-input pl-8 text-sm py-1.5">
                </div>
            </div>
            <div>
                <label class="form-label text-xs" for="status">Status</label>
                <select name="status" id="status" class="form-select text-sm py-1.5">
                    <option value="">Semua</option>
                    @foreach (['DRAFT' => 'Draft', 'PENDING_APPROVAL' => 'Menunggu Persetujuan', 'APPROVED' => 'Disetujui', 'REJECTED' => 'Ditolak', 'ONGOING' => 'Berlangsung', 'COMPLETED' => 'Selesai', 'CANCELLED' => 'Dibatalkan'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('eo.events.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    @if ($events->isEmpty())
        <div class="card">
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Belum ada event</p>
                <a href="{{ route('eo.events.create') }}" class="btn btn-primary btn-sm mt-4">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Buat Event Pertama
                </a>
            </div>
        </div>
    @else
        <div class="card p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Event</th>
                            <th>Tanggal</th>
                            <th>Slot</th>
                            <th>Pendaftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $events->firstItem() + $loop->index }}</td>
                                <td>
                                    <a href="{{ route('eo.events.show', $event) }}" class="font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                                        {{ str($event->name)->limit(40) }}
                                    </a>
                                    <p class="text-xs text-slate-400">{{ $event->venue?->name ?? $event->external_venue_name ?? 'Venue TBD' }}</p>
                                </td>
                                <td class="text-xs text-slate-500 whitespace-nowrap">
                                    <p>{{ $event->event_start?->format('d M Y H:i') }}</p>
                                    <p class="text-slate-400">s/d {{ $event->event_end?->format('d M Y H:i') }}</p>
                                </td>
                                <td>
                                    <span class="text-sm font-medium">{{ $event->booked_slots_count }}/{{ $event->slots_count }}</span>
                                </td>
                                <td class="text-sm font-medium">{{ $event->registrations_count }}</td>
                                <td><x-ui.status-badge :status="$event->status" /></td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="{{ route('eo.events.show', $event) }}" class="btn btn-ghost btn-sm btn-icon" title="Lihat">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>
                                        @if (in_array($event->status, ['APPROVED', 'ONGOING'], true))
                                            <a href="{{ route('eo.events.slots.index', $event) }}" class="btn btn-ghost btn-sm btn-icon text-indigo-500" title="Kelola Slot">
                                                <i data-lucide="grid" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif
                                        @if (in_array($event->status, ['DRAFT', 'REJECTED'], true))
                                            <a href="{{ route('eo.events.edit', $event) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif
                                        @if (in_array($event->status, ['DRAFT', 'REJECTED'], true))
                                            <form method="POST" action="{{ route('eo.events.submit', $event) }}" class="inline" onsubmit="return confirm('Ajukan event ini ke Admin untuk persetujuan?')">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-sm btn-icon text-blue-500" title="Ajukan ke Admin">
                                                    <i data-lucide="send" class="w-3.5 h-3.5"></i>
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
                {{ $events->links() }}
            </div>
        </div>
    @endif
</x-layouts.app>
