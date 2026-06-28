<x-layouts.app :title="$event->name" current-page="events" role="ADMIN">
    <x-ui.flash-banner />

    <div class="mb-5">
        <a href="{{ route('admin.events.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Event
        </a>
        <div class="flex flex-wrap items-center justify-between gap-3 mt-2">
            <div>
                <h2 class="page-title">{{ $event->name }}</h2>
                <p class="page-subtitle">{{ $event->organizer?->organization_name ?? '-' }}</p>
            </div>
            <x-ui.status-badge :status="$event->status" />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            @if ($event->banner_url)
                <div class="card p-0 overflow-hidden">
                    <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="w-full h-40 object-cover">
                </div>
            @endif

            <div class="card space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-sm">Status Event</h3>
                    <x-ui.status-badge :status="$event->status" />
                </div>

                @if ($event->status === 'PENDING_APPROVAL')
                    <form method="POST" action="{{ route('admin.events.approve', $event) }}" onsubmit="return confirm('Setujui event ini?')">
                        @csrf
                        <button type="submit" class="w-full btn btn-success btn-sm justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Setujui Event
                        </button>
                    </form>
                    <button type="button" onclick="openModal('modal-reject')" class="w-full btn btn-danger btn-sm justify-center">
                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                        Tolak Event
                    </button>
                    <x-ui.reject-modal id="modal-reject" :action="route('admin.events.reject', $event)" label="Alasan Penolakan Event" />
                @endif

                @if ($event->rejection_reason)
                    <div class="p-3 bg-red-50 dark:bg-red-950/40 rounded-lg border border-red-200 dark:border-red-800">
                        <p class="text-xs font-medium text-red-700 dark:text-red-300 mb-1">Alasan Penolakan:</p>
                        <p class="text-xs text-red-600 dark:text-red-400">{{ $event->rejection_reason }}</p>
                    </div>
                @endif

                <div class="pt-1">
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini? Aksi tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn btn-ghost btn-sm text-red-500 justify-center">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Hapus Event
                        </button>
                    </form>
                </div>
            </div>

            <div class="card space-y-3">
                <h3 class="font-semibold text-sm border-b border-slate-100 dark:border-slate-700 pb-2">Informasi Event</h3>
                @foreach ([
                    ['icon' => 'user-check', 'label' => 'Organizer', 'value' => $event->organizer?->organization_name ?? '-'],
                    ['icon' => 'map-pin', 'label' => 'Venue', 'value' => $event->venue_type === 'EXTERNAL' ? ($event->external_venue_name ?? 'Venue Eksternal') : ($event->venue?->name ?? '-')],
                    ['icon' => 'calendar', 'label' => 'Mulai', 'value' => $event->event_start?->format('d M Y H:i') ?? '-'],
                    ['icon' => 'calendar-x', 'label' => 'Selesai', 'value' => $event->event_end?->format('d M Y H:i') ?? '-'],
                    ['icon' => 'clock', 'label' => 'Deadline Daftar', 'value' => $event->registration_deadline?->format('d M Y H:i') ?? '-'],
                    ['icon' => 'users', 'label' => 'Kapasitas', 'value' => number_format($event->capacity ?? 0) . ' orang'],
                    ['icon' => 'shield-check', 'label' => 'Diproses Oleh', 'value' => $event->approver?->name ?? '-'],
                ] as $detail)
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="{{ $detail['icon'] }}" class="w-4 h-4 text-slate-400 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-[11px] text-slate-400 uppercase tracking-wide">{{ $detail['label'] }}</p>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $detail['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @php
                $slotTotal = $event->slots_count ?? 0;
                $slotBooked = $event->booked_slots_count ?? 0;
                $slotPercent = $slotTotal > 0 ? round(($slotBooked / $slotTotal) * 100) : 0;
            @endphp
            <div class="card">
                <h3 class="font-semibold text-sm mb-3">Slot</h3>
                <div class="flex items-end gap-4 mb-2">
                    <div>
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $slotBooked }}</p>
                        <p class="text-xs text-slate-400">Terisi</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-300 dark:text-slate-600">{{ $slotTotal }}</p>
                        <p class="text-xs text-slate-400">Total</p>
                    </div>
                </div>
                @if ($slotTotal > 0)
                    <div class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full transition-all" style="width: {{ $slotPercent }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">{{ $slotPercent }}% terisi</p>
                @endif
                <a href="{{ url('/event-slots?event_id=' . $event->id) }}" class="btn btn-secondary btn-sm w-full justify-center mt-3">
                    <i data-lucide="grid" class="w-4 h-4"></i>
                    Kelola Slot
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            @if ($event->description)
                <div class="card">
                    <h3 class="font-semibold mb-2">Deskripsi</h3>
                    <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold">Pendaftaran</h3>
                    <a href="{{ url('/event-registrations?event_id=' . $event->id) }}" class="text-xs text-indigo-500 hover:underline">Lihat semua</a>
                </div>
                @if ($event->registrations->isEmpty())
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                        <p class="text-sm">Belum ada pendaftaran</p>
                    </div>
                @else
                    <div class="overflow-x-auto -mx-6">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Status</th>
                                    <th>Tgl Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($event->registrations as $registration)
                                    <tr>
                                        <td>
                                            <p class="font-medium text-sm">{{ $registration->tenant?->organization_name ?? '-' }}</p>
                                            <p class="text-xs text-slate-400">{{ $registration->tenant?->user?->name ?? '' }}</p>
                                        </td>
                                        <td><x-ui.status-badge :status="$registration->registration_status" /></td>
                                        <td class="text-xs text-slate-400 whitespace-nowrap">{{ $registration->registered_at?->format('d M Y H:i') }}</td>
                                        <td>
                                            <a href="{{ url('/event-registrations/' . $registration->id) }}" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if ($event->slots->isNotEmpty())
                <div class="card">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold">Slot Event</h3>
                        <a href="{{ url('/event-slots?event_id=' . $event->id) }}" class="text-xs text-indigo-500 hover:underline">Kelola slot</a>
                    </div>
                    <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-1.5">
                        @foreach ($event->slots as $slot)
                            <div class="aspect-square rounded-lg flex items-center justify-center text-xs font-bold {{ $slot->is_booked ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }}" title="Slot #{{ $slot->slot_number }} {{ $slot->is_booked ? '(Terisi)' : '(Tersedia)' }}">
                                {{ $slot->slot_number }}
                            </div>
                        @endforeach
                        @if (($event->slots_count ?? 0) > $event->slots->count())
                            <div class="aspect-square rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs text-slate-400">
                                +{{ $event->slots_count - $event->slots->count() }}
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-3 mt-3 text-xs text-slate-400">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-indigo-100 dark:bg-indigo-900/40 inline-block"></span>Terisi</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-slate-100 dark:bg-slate-700 inline-block"></span>Tersedia</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
