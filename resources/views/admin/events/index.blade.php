<x-layouts.app title="Manajemen Event" current-page="events" role="ADMIN">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Event</h2>
            <p class="page-subtitle">Kelola semua event dari seluruh organizer</p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        @foreach ([
            ['label' => 'Total Event', 'value' => $stats['total'], 'icon' => 'calendar', 'color' => 'indigo'],
            ['label' => 'Menunggu Persetujuan', 'value' => $stats['pending'], 'icon' => 'clock', 'color' => 'yellow'],
            ['label' => 'Disetujui', 'value' => $stats['approved'], 'icon' => 'check-circle', 'color' => 'green'],
            ['label' => 'Sedang Berlangsung', 'value' => $stats['ongoing'], 'icon' => 'zap', 'color' => 'purple'],
        ] as $stat)
            <div class="stat-card">
                <div class="stat-icon bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($stat['value']) }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-60">
                <label class="form-label text-xs" for="search">Cari Event</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama event atau organizer..." class="form-input pl-8 text-sm py-1.5">
                </div>
            </div>
            <div>
                <label class="form-label text-xs" for="status">Status</label>
                <select name="status" id="status" class="form-select text-sm py-1.5">
                    <option value="">Semua</option>
                    @foreach (['DRAFT' => 'Draft', 'PENDING_APPROVAL' => 'Menunggu', 'APPROVED' => 'Disetujui', 'REJECTED' => 'Ditolak', 'ONGOING' => 'Berlangsung', 'COMPLETED' => 'Selesai', 'CANCELLED' => 'Dibatalkan'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($events->isEmpty())
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Tidak ada event ditemukan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Event</th>
                            <th>Organizer</th>
                            <th>Tanggal</th>
                            <th>Venue</th>
                            <th>Slot</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            @php
                                $slotTotal = $event->slots_count ?? 0;
                                $slotBooked = $event->booked_slots_count ?? 0;
                                $slotPercent = $slotTotal > 0 ? round(($slotBooked / $slotTotal) * 100) : 0;
                                $venueName = $event->venue_type === 'EXTERNAL'
                                    ? $event->external_venue_name
                                    : $event->venue?->name;
                            @endphp
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $events->firstItem() + $loop->index }}</td>
                                <td>
                                    <a href="{{ route('admin.events.show', $event) }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ str($event->name)->limit(40) }}
                                    </a>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $event->venue_type === 'EXTERNAL' ? 'Venue Eksternal' : 'Venue Internal' }}</p>
                                </td>
                                <td>
                                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $event->organizer?->organization_name ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $event->organizer?->user?->email ?? '' }}</p>
                                </td>
                                <td class="text-xs text-slate-500 whitespace-nowrap">
                                    <p>{{ $event->event_start?->format('d M Y H:i') }}</p>
                                    <p class="text-slate-400">s/d {{ $event->event_end?->format('d M Y H:i') }}</p>
                                </td>
                                <td class="text-sm text-slate-500 max-w-36 truncate">{{ $venueName ?? '-' }}</td>
                                <td>
                                    <div class="flex items-center gap-1.5">
                                        <div class="text-xs font-medium">{{ $slotBooked }}/{{ $slotTotal }}</div>
                                        @if ($slotTotal > 0)
                                            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $slotPercent }}%"></div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td><x-ui.status-badge :status="$event->status" /></td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="{{ route('admin.events.show', $event) }}" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>
                                        @if ($event->status === 'PENDING_APPROVAL')
                                            <form method="POST" action="{{ route('admin.events.approve', $event) }}" onsubmit="return confirm('Setujui event {{ $event->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-sm btn-icon text-green-500" title="Setujui">
                                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                            <button type="button" onclick="openModal('reject-{{ $event->id }}')" class="btn btn-ghost btn-sm btn-icon text-red-500" title="Tolak">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <x-ui.reject-modal id="reject-{{ $event->id }}" :action="route('admin.events.reject', $event)" label="Alasan Penolakan Event" />
                                        @endif
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event {{ $event->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm btn-icon text-red-400" title="Hapus">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
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
        @endif
    </div>
</x-layouts.app>
