<x-layouts.app title="Detail Booking Tenant" current-page="tenant-bookings" role="TENANT">
    <x-ui.flash-banner />

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('event-registrations.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Tenant Booking
            </a>
            <h2 class="page-title mt-2">Detail Booking Tenant</h2>
            <p class="text-sm text-slate-500 mt-1">Status booking untuk event <strong>{{ $registration->event?->name }}</strong>.</p>
        </div>

        @if ($registration->registration_status === 'PENDING')
            <form method="POST" action="{{ route('event-registrations.cancel', $registration) }}" onsubmit="return confirm('Batalkan booking tenant ini?')">
                @csrf
                <button type="submit" class="btn btn-ghost text-red-500 btn-sm">Batalkan Booking</button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-4">
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-sm">Status Booking</h3>
                    <x-ui.status-badge :status="$registration->registration_status" />
                </div>

                @php
                    $statusInfo = match ($registration->registration_status) {
                        'APPROVED' => ['check-circle-2', 'Disetujui', 'Booking disetujui oleh Event Organizer.', 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border-green-100 dark:border-green-800'],
                        'REJECTED' => ['x-circle', 'Ditolak', 'Booking tidak dapat diterima.', 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border-red-100 dark:border-red-800'],
                        'CANCELLED' => ['slash', 'Dibatalkan', 'Booking telah dibatalkan.', 'bg-slate-50 dark:bg-slate-900/20 text-slate-700 dark:text-slate-400 border-slate-200 dark:border-slate-800'],
                        default => ['clock', 'Menunggu Review', 'Menunggu persetujuan Event Organizer.', 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 border-blue-100 dark:border-blue-800'],
                    };
                @endphp

                <div class="flex items-center gap-3 p-3 rounded-lg border {{ $statusInfo[3] }}">
                    <i data-lucide="{{ $statusInfo[0] }}" class="w-8 h-8"></i>
                    <div>
                        <p class="font-bold">{{ $statusInfo[1] }}</p>
                        <p class="text-xs">{{ $statusInfo[2] }}</p>
                    </div>
                </div>

                @if ($registration->rejection_reason)
                    <div class="mt-3 text-xs bg-red-50 dark:bg-red-900/10 p-3 rounded-lg text-red-600">
                        <span class="font-semibold block mb-1">Alasan Penolakan:</span>
                        {{ $registration->rejection_reason }}
                    </div>
                @endif

                <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 space-y-3">
                    <div class="flex items-start gap-2">
                        <i data-lucide="calendar-clock" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Waktu Booking</p>
                            <p class="text-sm font-medium">{{ $registration->registered_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <i data-lucide="user-check" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Diproses Oleh</p>
                            <p class="text-sm font-medium">{{ $registration->approver?->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Catatan</p>
                            <p class="text-sm font-medium">{{ $registration->notes ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <i data-lucide="grid" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Jumlah Tenant / Slot Diminta</p>
                            <p class="text-sm font-medium">{{ $registration->requested_slot_count ?: 1 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="card p-0 overflow-hidden">
                <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-semibold text-sm">Informasi Event</h3>
                </div>
                <div class="p-5">
                    <p class="text-lg font-bold text-slate-800 dark:text-slate-200">{{ $registration->event?->name }}</p>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500 text-xs">Tanggal Mulai</p>
                            <p class="font-medium">{{ $registration->event?->event_start?->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs">Tanggal Selesai</p>
                            <p class="font-medium">{{ $registration->event?->event_end?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('events.show', $registration->event) }}" class="text-indigo-600 text-sm hover:underline flex items-center gap-1 mt-4">
                        Lihat Detail Event
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            <div class="card p-0 overflow-hidden">
                <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="font-semibold text-sm">Preferensi & Penugasan Slot</h3>
                </div>
                <div>
                    @php
                        $requestedSlotIds = $registration->requested_slot_ids ?? [];
                        $requestedSlots = $registration->event?->slots?->whereIn('id', $requestedSlotIds) ?? collect();
                    @endphp

                    <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <h4 class="font-semibold text-sm">Preferensi Tenant</h4>
                            <span class="text-xs text-slate-400">{{ count($requestedSlotIds) }} slot dipilih</span>
                        </div>
                        @if ($requestedSlots->isEmpty())
                            <p class="text-sm text-slate-500">Tidak memilih nomor slot tertentu. Organizer akan menentukan slot final.</p>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach ($requestedSlots as $slot)
                                    <div class="p-3 rounded-lg border border-indigo-100 dark:border-indigo-900 bg-indigo-50/50 dark:bg-indigo-950/20 flex items-center gap-2">
                                        <span class="w-8 h-8 rounded-lg bg-indigo-500 text-white flex items-center justify-center font-bold text-sm">{{ $slot->slot_number }}</span>
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium text-indigo-700 dark:text-indigo-300 truncate">{{ $slot->slot_label ?: 'Slot #'.$slot->slot_number }}</p>
                                            <p class="text-[11px] text-indigo-400">{{ $slot->slot_width && $slot->slot_long ? $slot->slot_width.' x '.$slot->slot_long.' m' : 'TBD' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($registration->registration_status !== 'APPROVED')
                        <div class="p-6 text-center text-slate-500 text-sm flex flex-col items-center">
                            <i data-lucide="lock" class="w-8 h-8 text-slate-300 mb-2"></i>
                            Slot akan ditetapkan setelah booking Anda disetujui.
                        </div>
                    @elseif ($registration->slots->isEmpty())
                        <div class="p-6 text-center text-slate-500 text-sm flex flex-col items-center">
                            <i data-lucide="clock" class="w-8 h-8 text-slate-300 mb-2"></i>
                            Menunggu organizer untuk menetapkan slot Anda.
                        </div>
                    @else
                        <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($registration->slots as $assignedSlot)
                                @php($slot = $assignedSlot->slot)
                                <li class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded flex items-center justify-center">
                                            <span class="font-bold text-emerald-700 dark:text-emerald-400 text-sm">{{ $slot?->slot_number }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                                Slot {{ $slot?->slot_label ?: '#'.$slot?->slot_number }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Dimensi: {{ $slot?->slot_width && $slot?->slot_long ? $slot->slot_width.' x '.$slot->slot_long.' m' : 'TBD' }}
                                            </p>
                                        </div>
                                    </div>
                                    <x-ui.status-badge :status="$assignedSlot->status" />
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
