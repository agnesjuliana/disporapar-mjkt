<x-layouts.app :title="$event->name" current-page="events" role="EVENT_ORGANIZER" :active-event-id="$event->id">
    <x-ui.flash-banner />

    <div class="mb-5 flex items-center gap-2">
        <a href="{{ route('eo.events.index') }}" class="text-sm text-slate-400 hover:text-emerald-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali
        </a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <span class="text-sm text-slate-500 truncate max-w-xs">{{ $event->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-4">
            @if ($event->banner_url)
                <div class="card p-0 overflow-hidden">
                    <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="w-full h-40 object-cover">
                </div>
            @endif

            <div class="card space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-sm">Status</h3>
                    <x-ui.status-badge :status="$event->status" />
                </div>

                @if ($event->status === 'DRAFT')
                    <a href="{{ route('eo.events.edit', $event) }}" class="w-full btn btn-secondary btn-sm justify-center">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        Edit Event
                    </a>
                    <form method="POST" action="{{ route('eo.events.submit', $event) }}" onsubmit="return confirm('Ajukan event ini ke Admin Disporapar untuk persetujuan?')">
                        @csrf
                        <button type="submit" class="w-full btn btn-primary btn-sm justify-center">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Ajukan ke Admin
                        </button>
                    </form>
                @elseif ($event->status === 'REJECTED')
                    <div class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-xl">
                        <p class="text-xs font-medium text-red-700 dark:text-red-300 mb-1">Alasan Penolakan:</p>
                        <p class="text-xs text-red-600 dark:text-red-300">{{ $event->rejection_reason ?: '-' }}</p>
                    </div>
                    <a href="{{ route('eo.events.edit', $event) }}" class="w-full btn btn-primary btn-sm justify-center">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        Edit & Ajukan Ulang
                    </a>
                @elseif ($event->status === 'APPROVED')
                    <a href="{{ route('eo.events.slots.index', $event) }}" class="w-full btn btn-primary btn-sm justify-center">
                        <i data-lucide="grid" class="w-4 h-4"></i>
                        Kelola Slot
                    </a>
                    <a href="{{ route('eo.events.tenant-registrations.index', $event) }}" class="w-full btn btn-secondary btn-sm justify-center">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        Lihat Pendaftaran
                    </a>
                @endif

                @if (in_array($event->status, ['DRAFT', 'PENDING_APPROVAL'], true))
                    <form method="POST" action="{{ route('eo.events.cancel', $event) }}" onsubmit="return confirm('Batalkan event ini?')">
                        @csrf
                        <button type="submit" class="w-full btn btn-danger btn-sm justify-center">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            Batalkan
                        </button>
                    </form>
                @endif
            </div>

            <div class="card space-y-3">
                <h3 class="font-semibold text-sm border-b border-slate-100 dark:border-slate-700 pb-2">Detail Event</h3>
                @php
                    $details = [
                        ['icon' => 'map-pin', 'label' => 'Venue', 'value' => $event->venue?->name ?? $event->external_venue_name ?? 'Venue Eksternal'],
                        ['icon' => 'calendar', 'label' => 'Mulai', 'value' => $event->event_start?->format('d M Y H:i') ?? '-'],
                        ['icon' => 'calendar-x', 'label' => 'Selesai', 'value' => $event->event_end?->format('d M Y H:i') ?? '-'],
                        ['icon' => 'clock', 'label' => 'Deadline Daftar', 'value' => $event->registration_deadline?->format('d M Y H:i') ?? '-'],
                        ['icon' => 'users', 'label' => 'Kapasitas', 'value' => number_format($event->capacity ?? 0).' orang'],
                    ];
                @endphp
                @foreach ($details as $detail)
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="{{ $detail['icon'] }}" class="w-4 h-4 text-slate-400 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-[11px] text-slate-400">{{ $detail['label'] }}</p>
                            <p class="text-sm font-medium">{{ $detail['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-sm">Slot</h3>
                    @if ($event->status === 'APPROVED')
                        <a href="{{ route('eo.events.slots.index', $event) }}" class="text-xs text-emerald-500 hover:underline">Kelola</a>
                    @endif
                </div>
                <div class="flex items-end gap-4 mb-2">
                    <div>
                        <p class="text-3xl font-bold text-emerald-600">{{ $event->booked_slots_count }}</p>
                        <p class="text-xs text-slate-400">Terisi</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-300 dark:text-slate-600">{{ $event->slots_count }}</p>
                        <p class="text-xs text-slate-400">Total</p>
                    </div>
                </div>
                @if ($event->slots_count > 0)
                    @php($percentage = round($event->booked_slots_count / $event->slots_count * 100))
                    <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">{{ $percentage }}% slot terisi</p>
                @elseif ($event->status === 'APPROVED')
                    <p class="text-xs text-slate-400">Belum ada slot.</p>
                @else
                    <p class="text-xs text-slate-400">Slot dapat ditambahkan setelah event disetujui.</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            @if ($event->description)
                <div class="card">
                    <h3 class="font-semibold mb-3">Deskripsi</h3>
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $event->description }}</div>
                </div>
            @endif

            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold">Pendaftaran Masuk</h3>
                    <a href="{{ url('/event-registrations?event_id='.$event->id) }}" class="text-xs text-emerald-500 hover:underline">Lihat semua</a>
                </div>
                @if ($event->registrations->isEmpty())
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                        <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                        <p class="text-sm">Belum ada pendaftaran masuk</p>
                    </div>
                @else
                    <div class="overflow-x-auto -mx-6">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Status</th>
                                    <th>Tgl Daftar</th>
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
                                        <td class="text-xs text-slate-400">{{ $registration->registered_at?->format('d M Y H:i') }}</td>
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
                        <h3 class="font-semibold">Peta Slot</h3>
                        <a href="{{ route('eo.events.slots.index', $event) }}" class="btn btn-secondary btn-sm">
                            <i data-lucide="grid" class="w-4 h-4"></i>
                            Kelola Slot
                        </a>
                    </div>
                    <div class="grid grid-cols-6 sm:grid-cols-8 lg:grid-cols-10 gap-1.5">
                        @foreach ($event->slots as $slot)
                            <div title="Slot #{{ $slot->slot_number }} {{ $slot->is_booked ? '(Terisi)' : '(Tersedia)' }}"
                                class="aspect-square rounded-lg flex items-center justify-center text-xs font-bold {{ $slot->is_booked ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700' : 'bg-slate-100 dark:bg-slate-700 text-slate-400' }}">
                                {{ $slot->slot_number }}
                            </div>
                        @endforeach
                    </div>
                    <div class="flex gap-3 mt-2 text-[11px] text-slate-400">
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-emerald-100 inline-block"></span>Terisi</span>
                        <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded bg-slate-100 inline-block"></span>Tersedia</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
