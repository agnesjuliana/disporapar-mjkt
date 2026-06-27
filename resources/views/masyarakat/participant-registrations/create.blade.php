<x-layouts.app :title="'Daftar Peserta - '.$event->name" current-page="calendar" role="MASYARAKAT">
    <x-ui.flash-banner />

    @php
        $venueName = $event->venue_type === 'EXTERNAL'
            ? $event->external_venue_name
            : $event->venue?->name;
    @endphp

    <div class="mb-5">
        <a href="{{ route('event.calendar', ['date' => $event->event_start?->format('Y-m-d'), 'month' => $event->event_start?->format('Y-m')]) }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Kalender
        </a>
        <h2 class="page-title mt-2">Konfirmasi Pendaftaran Peserta</h2>
        <p class="page-subtitle">Periksa detail event dan setujui ketentuan sebelum mendaftar.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="card p-0 overflow-hidden">
                <div class="h-44 bg-indigo-600 relative">
                    @if ($event->banner_url)
                        <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/35"></div>
                    @endif
                    <div class="absolute left-5 bottom-5 right-5">
                        <span class="badge badge-green mb-2">{{ $event->status === 'ONGOING' ? 'Berlangsung' : 'Terjadwal' }}</span>
                        <h3 class="text-white text-2xl font-bold leading-tight">{{ $event->name }}</h3>
                        <p class="text-white/75 text-sm mt-1">{{ $event->organizer?->organization_name ?? 'Disporapar' }}</p>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([
                        ['icon' => 'calendar', 'label' => 'Jadwal Event', 'value' => $event->event_start?->format('d M Y H:i').' - '.$event->event_end?->format('d M Y H:i')],
                        ['icon' => 'map-pin', 'label' => 'Lokasi', 'value' => $venueName ?? 'Venue TBD'],
                        ['icon' => 'building-2', 'label' => 'Organizer', 'value' => $event->organizer?->organization_name ?? 'Disporapar'],
                        ['icon' => 'user', 'label' => 'Nama Peserta', 'value' => $user->name],
                    ] as $item)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 flex items-center justify-center flex-shrink-0">
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

            @if ($event->description)
                <div class="card">
                    <h3 class="font-semibold mb-3">Tentang Event</h3>
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $event->description }}</div>
                </div>
            @endif
        </div>

        <div>
            <form method="POST" action="{{ route('participant-registrations.store') }}" class="card space-y-4">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div>
                    <h3 class="font-semibold text-sm mb-2">Persetujuan Peserta</h3>
                    <p class="text-xs text-slate-500">Dengan mendaftar, data akun Anda akan digunakan sebagai data peserta dan bukti kehadiran event.</p>
                </div>

                <label class="flex gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 cursor-pointer">
                    <input type="checkbox" name="consent" value="1" class="mt-1 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" required @checked(old('consent'))>
                    <span class="text-sm text-slate-600 dark:text-slate-300">
                        Saya menyetujui pendaftaran sebagai peserta event ini dan memahami bahwa kode pendaftaran akan digunakan untuk proses check-in.
                    </span>
                </label>
                @error('consent')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn btn-primary w-full justify-center">
                    <i data-lucide="ticket-check" class="w-4 h-4"></i>
                    Setuju & Daftar
                </button>
                <a href="{{ route('event.calendar', ['date' => $event->event_start?->format('Y-m-d'), 'month' => $event->event_start?->format('Y-m')]) }}" class="btn btn-secondary w-full justify-center">Batal</a>
            </form>
        </div>
    </div>
</x-layouts.app>
