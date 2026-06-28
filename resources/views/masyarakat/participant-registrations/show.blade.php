<x-layouts.app :title="$registration->event?->name ?? 'Detail Pendaftaran'" current-page="history" role="MASYARAKAT">
    <x-ui.flash-banner />

    @php
        $event = $registration->event;
        $venueName = $event?->venue_type === 'EXTERNAL'
            ? $event?->external_venue_name
            : $event?->venue?->name;
    @endphp

    <div class="mb-5">
        <a href="{{ route('participant-registrations.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Event History
        </a>
        <h2 class="page-title mt-2">Tiket Peserta</h2>
        <p class="page-subtitle">Bukti pendaftaran sebagai peserta event.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card p-0 overflow-hidden">
                <div class="h-44 bg-indigo-600 relative">
                    @if ($event?->banner_url)
                        <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/35"></div>
                    @endif
                    <div class="absolute left-5 bottom-5 right-5">
                        <span class="badge badge-green mb-2">Terdaftar</span>
                        <h3 class="text-white text-2xl font-bold leading-tight">{{ $event?->name ?? 'Event' }}</h3>
                        <p class="text-white/75 text-sm mt-1">{{ $event?->organizer?->organization_name ?? 'Disporapar' }}</p>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([
                        ['icon' => 'user', 'label' => 'Nama Peserta', 'value' => $registration->user?->name],
                        ['icon' => 'mail', 'label' => 'Email', 'value' => $registration->user?->email],
                        ['icon' => 'calendar', 'label' => 'Jadwal Event', 'value' => $event?->event_start?->format('d M Y H:i').' - '.$event?->event_end?->format('d M Y H:i')],
                        ['icon' => 'map-pin', 'label' => 'Lokasi', 'value' => $venueName ?? 'Venue TBD'],
                        ['icon' => 'clock', 'label' => 'Tanggal Daftar', 'value' => $registration->registered_at?->format('d M Y H:i')],
                        ['icon' => 'scan-line', 'label' => 'Status Absensi', 'value' => str_replace('_', ' ', $registration->attendance_status)],
                    ] as $item)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">{{ $item['label'] }}</p>
                                <p class="text-sm font-medium">{{ $item['value'] ?: '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="card text-center">
                <div class="w-16 h-16 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="qr-code" class="w-8 h-8"></i>
                </div>
                <p class="text-xs text-slate-400 mb-1">Kode Pendaftaran</p>
                <p class="font-mono text-sm font-semibold break-all">{{ $registration->id }}</p>
            </div>
            <a href="{{ route('event.calendar', ['date' => $event?->event_start?->format('Y-m-d'), 'month' => $event?->event_start?->format('Y-m')]) }}" class="btn btn-secondary w-full justify-center">
                <i data-lucide="calendar-days" class="w-4 h-4"></i>
                Lihat Kalender
            </a>
        </div>
    </div>
</x-layouts.app>
