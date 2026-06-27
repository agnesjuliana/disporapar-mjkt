<x-layouts.app :title="'Tenant - '.$event->name" current-page="event-tenants" role="EVENT_ORGANIZER" :active-event-id="$event->id">
    <x-ui.flash-banner />

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('eo.events.show', $event) }}" class="text-sm text-slate-400 hover:text-emerald-500 flex items-center gap-1 w-fit">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Event
            </a>
            <h2 class="page-title mt-1">Manajemen Tenant</h2>
            <p class="page-subtitle">Tinjau pendaftaran tenant dan tetapkan slot untuk {{ $event->name }}.</p>
        </div>
        <a href="{{ route('eo.events.slots.index', $event) }}" class="btn btn-secondary btn-sm">
            <i data-lucide="grid-2x2" class="w-4 h-4"></i>
            Kelola Slot
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-5">
        @foreach ([
            ['label' => 'Total Request', 'value' => $stats['total'], 'icon' => 'inbox', 'color' => 'indigo'],
            ['label' => 'Menunggu', 'value' => $stats['pending'], 'icon' => 'clock', 'color' => 'amber'],
            ['label' => 'Disetujui', 'value' => $stats['approved'], 'icon' => 'check-circle', 'color' => 'emerald'],
            ['label' => 'Slot Terpakai', 'value' => $stats['assigned'], 'icon' => 'check-square', 'color' => 'blue'],
        ] as $stat)
            <div class="card p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-300 flex items-center justify-center">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold leading-tight">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-52">
                <label class="form-label text-xs" for="search">Cari Tenant</label>
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" id="search" value="{{ $search }}" class="form-input pl-8 text-sm py-1.5" placeholder="Nama tenant atau PIC">
                </div>
            </div>
            <div>
                <label class="form-label text-xs" for="status">Status</label>
                <select name="status" id="status" class="form-select text-sm py-1.5">
                    @foreach (['' => 'Semua', 'PENDING' => 'Menunggu', 'APPROVED' => 'Disetujui', 'REJECTED' => 'Ditolak'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('eo.events.tenant-registrations.index', $event) }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($registrations->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="font-medium">Belum ada pendaftaran tenant</p>
                <p class="text-xs text-slate-400 mt-1">Tenant yang mengajukan booking ke event ini akan muncul di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tenant</th>
                            <th>Preferensi</th>
                            <th>Slot Final</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            @php
                                $preferred = collect($registration->requested_slot_ids ?? []);
                            @endphp
                            <tr class="{{ $registration->registration_status === 'PENDING' ? 'bg-amber-50/50 dark:bg-amber-950/10' : '' }}">
                                <td class="text-slate-400 text-xs">{{ $registrations->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/40 text-orange-600 font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($registration->tenant?->organization_name ?? 'T', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm">{{ $registration->tenant?->organization_name ?? '-' }}</p>
                                            <p class="text-xs text-slate-400">{{ $registration->tenant?->user?->name ?? $registration->tenant?->contact_person }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs text-slate-500">
                                    <span class="font-semibold">{{ $registration->requested_slot_count ?? 1 }}</span> slot
                                    @if ($preferred->isNotEmpty())
                                        <span class="text-slate-400">· {{ $preferred->count() }} dipilih</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($registration->slots->isEmpty())
                                        <span class="text-xs text-slate-400">Belum ditetapkan</span>
                                    @else
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($registration->slots as $assigned)
                                                <span class="badge badge-green">#{{ $assigned->slot?->slot_number }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td><x-ui.status-badge :status="$registration->registration_status" /></td>
                                <td class="text-xs text-slate-400 whitespace-nowrap">{{ $registration->registered_at?->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('eo.events.tenant-registrations.show', [$event, $registration]) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
