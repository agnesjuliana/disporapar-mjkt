<x-layouts.app :title="'Tenant - '.$registration->tenant?->organization_name" current-page="event-tenants" role="EVENT_ORGANIZER" :active-event-id="$event->id">
    <x-ui.flash-banner />

    @php
        $requestedCount = max((int) ($registration->requested_slot_count ?? 1), 1);
        $oldSlotIds = old('slot_ids', $assignedSlotIds);
        $preferredSlotIds = collect($preferredSlotIds);
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('eo.events.tenant-registrations.index', $event) }}" class="text-sm text-slate-400 hover:text-emerald-500 flex items-center gap-1 w-fit">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Manajemen Tenant
            </a>
            <h2 class="page-title mt-1">{{ $registration->tenant?->organization_name ?? 'Tenant' }}</h2>
            <p class="page-subtitle">{{ $event->name }} · {{ $requestedCount }} slot diminta</p>
        </div>
        <x-ui.status-badge :status="$registration->registration_status" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-4">
            <div class="card">
                <h3 class="font-semibold text-sm mb-3">Informasi Tenant</h3>
                <div class="space-y-2.5">
                    @foreach ([
                        ['icon' => 'store', 'label' => 'Organisasi', 'value' => $registration->tenant?->organization_name],
                        ['icon' => 'user', 'label' => 'PIC', 'value' => $registration->tenant?->contact_person ?? $registration->tenant?->user?->name],
                        ['icon' => 'phone', 'label' => 'Telepon', 'value' => $registration->tenant?->contact_phone],
                        ['icon' => 'mail', 'label' => 'Email', 'value' => $registration->tenant?->user?->email],
                        ['icon' => 'clock', 'label' => 'Tanggal Daftar', 'value' => $registration->registered_at?->format('d M Y H:i')],
                    ] as $item)
                        <div class="flex items-start gap-2.5">
                            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                            <div>
                                <p class="text-[11px] text-slate-400">{{ $item['label'] }}</p>
                                <p class="text-sm font-medium">{{ $item['value'] ?: '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($registration->notes)
                    <div class="mt-4 p-3 rounded-lg bg-slate-50 dark:bg-slate-700/60">
                        <p class="text-xs text-slate-400 mb-1">Catatan Tenant</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line">{{ $registration->notes }}</p>
                    </div>
                @endif

                @if ($registration->rejection_reason)
                    <div class="mt-3 p-3 rounded-lg bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-900">
                        <p class="text-xs font-medium text-red-700 dark:text-red-300 mb-1">Alasan Penolakan</p>
                        <p class="text-sm text-red-600 dark:text-red-300">{{ $registration->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            <div class="card">
                <h3 class="font-semibold text-sm mb-3">Preferensi Slot Tenant</h3>
                @if ($preferredSlotIds->isEmpty())
                    <p class="text-sm text-slate-500">Tenant tidak memilih nomor slot khusus.</p>
                @else
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($slots->whereIn('id', $preferredSlotIds) as $slot)
                            <span class="badge badge-blue">#{{ $slot->slot_number }} {{ $slot->slot_label }}</span>
                        @endforeach
                    </div>
                @endif
                <p class="text-xs text-slate-400 mt-3">EO dapat mengikuti preferensi ini atau memilih ulang slot final.</p>
            </div>

            @if ($registration->registration_status !== 'REJECTED')
                <div class="card">
                    <h3 class="font-semibold text-sm mb-3">Tolak Pendaftaran</h3>
                    <form method="POST" action="{{ route('eo.events.tenant-registrations.reject', [$event, $registration]) }}" class="space-y-3">
                        @csrf
                        <textarea name="reason" rows="3" class="form-textarea text-sm" placeholder="Alasan penolakan" required>{{ old('reason') }}</textarea>
                        <button type="submit" class="btn btn-danger btn-sm w-full justify-center" onclick="return confirm('Tolak pendaftaran tenant ini?')">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            Tolak Pendaftaran
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <form method="POST" action="{{ $registration->registration_status === 'PENDING' ? route('eo.events.tenant-registrations.approve', [$event, $registration]) : route('eo.events.tenant-registrations.assign', [$event, $registration]) }}">
                @csrf
                <div class="card">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                        <div>
                            <h3 class="font-semibold">Penugasan Slot Final</h3>
                            <p class="text-xs text-slate-400 mt-1">Pilih minimal {{ $requestedCount }} slot. Slot merah sudah dipakai tenant lain.</p>
                        </div>
                        <div class="flex flex-wrap gap-3 text-xs text-slate-400">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-200 inline-block"></span>Dipilih</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-100 inline-block"></span>Preferensi</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-red-100 inline-block"></span>Terpakai</span>
                        </div>
                    </div>

                    @if ($slots->isEmpty())
                        <div class="text-center py-10 text-slate-500">
                            <i data-lucide="grid" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                            <p class="font-medium">Event belum memiliki slot</p>
                            <a href="{{ route('eo.events.slots.index', $event) }}" class="btn btn-primary btn-sm mt-3">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Buat Slot
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-2">
                            @foreach ($slots as $slot)
                                @php
                                    $isChecked = in_array($slot->id, $oldSlotIds, true);
                                    $isPreferred = $preferredSlotIds->contains($slot->id);
                                    $takenByOther = $slot->is_booked && ! in_array($slot->id, $assignedSlotIds, true);
                                    $assignedTo = $slot->registrationSlots
                                        ->first(fn ($item) => $item->event_registration_id !== $registration->id && $item->status === 'ASSIGNED')
                                        ?->eventRegistration?->tenant?->organization_name;
                                @endphp
                                <label class="{{ $takenByOther ? 'cursor-not-allowed' : 'cursor-pointer' }}" title="{{ $takenByOther ? 'Dipakai: '.$assignedTo : ($slot->slot_label ?: 'Slot #'.$slot->slot_number) }}">
                                    <input type="checkbox" name="slot_ids[]" value="{{ $slot->id }}" class="sr-only peer" @checked($isChecked) @disabled($takenByOther)>
                                    <div class="aspect-square rounded-lg border flex flex-col items-center justify-center gap-1 text-xs font-bold transition-all
                                        {{ $takenByOther ? 'bg-red-50 dark:bg-red-950/30 border-red-100 text-red-400' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-emerald-300 peer-checked:bg-emerald-100 dark:peer-checked:bg-emerald-900/40 peer-checked:border-emerald-400 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-300' }}
                                        {{ $isPreferred && ! $takenByOther ? 'ring-2 ring-blue-200 dark:ring-blue-900' : '' }}">
                                        <span>#{{ $slot->slot_number }}</span>
                                        @if ($isPreferred)
                                            <span class="text-[10px] font-medium text-blue-500">Preferensi</span>
                                        @elseif ($takenByOther)
                                            <span class="text-[10px] font-medium">Terpakai</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-700 flex flex-wrap gap-3">
                            @if ($registration->registration_status === 'PENDING')
                                <button type="submit" class="btn btn-success">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Setujui & Tetapkan Slot
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="save" class="w-4 h-4"></i>
                                    Simpan Penugasan
                                </button>
                            @endif
                            <a href="{{ route('eo.events.tenant-registrations.index', $event) }}" class="btn btn-secondary">Batal</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
