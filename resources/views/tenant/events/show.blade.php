<x-layouts.app :title="$event->name" current-page="events" role="TENANT">
    <x-ui.flash-banner />

    @php
        $slotTotal = $event->slots_count ?? 0;
        $slotBooked = $event->booked_slots_count ?? 0;
        $availableSlots = max($slotTotal - $slotBooked, 0);
        $slotPercent = $slotTotal > 0 ? round(($slotBooked / $slotTotal) * 100) : 0;
        $deadlinePassed = $event->registration_deadline && $event->registration_deadline->isPast();
        $tenantApproved = $tenant?->registration_status === 'APPROVED';
        $venueName = $event->venue_type === 'EXTERNAL'
            ? $event->external_venue_name
            : $event->venue?->name;
    @endphp

    <div class="relative rounded-xl overflow-hidden mb-6 bg-indigo-600 h-52">
        @if ($event->banner_url)
            <img src="{{ $event->banner_url }}" class="absolute inset-0 w-full h-full object-cover opacity-65" alt="Banner {{ $event->name }}">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/10 to-transparent"></div>
        <div class="absolute bottom-5 left-6 right-6">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-white font-bold text-2xl leading-tight drop-shadow">{{ $event->name }}</h1>
                    <p class="text-white/75 text-sm mt-1">{{ $event->organizer?->organization_name ?? 'Disporapar' }}</p>
                </div>
                <div class="flex-shrink-0">
                    @if ($alreadyRegistered)
                        <span class="badge badge-green shadow-lg text-sm px-3 py-1.5">Sudah Terdaftar</span>
                    @elseif ($deadlinePassed)
                        <span class="badge badge-red shadow-lg text-sm px-3 py-1.5">Pendaftaran Ditutup</span>
                    @else
                        <span class="badge badge-green shadow-lg text-sm px-3 py-1.5">Buka Pendaftaran</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-4">
            <div class="card">
                @if ($alreadyRegistered)
                    <div class="text-center py-3">
                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 mx-auto mb-2">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                        </div>
                        <p class="font-medium text-green-700 dark:text-green-300">Sudah Terdaftar</p>
                        <p class="text-xs text-slate-400 mt-1">Anda telah mendaftar ke event ini</p>
                        <a href="{{ url('/event-registrations') }}" class="btn btn-secondary btn-sm w-full justify-center mt-3">Lihat Pendaftaran</a>
                    </div>
                @elseif (! $tenantApproved)
                    <div class="text-center py-3">
                        <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 mx-auto mb-2">
                            <i data-lucide="lock" class="w-6 h-6"></i>
                        </div>
                        <p class="font-medium text-amber-700 dark:text-amber-300 text-sm">Akun Belum Terverifikasi</p>
                        <p class="text-xs text-slate-400 mt-1">Akun Anda harus diverifikasi Admin sebelum dapat mendaftar</p>
                    </div>
                @elseif ($deadlinePassed)
                    <div class="text-center py-3">
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-500 mx-auto mb-2">
                            <i data-lucide="calendar-x" class="w-6 h-6"></i>
                        </div>
                        <p class="font-medium text-red-700 dark:text-red-300 text-sm">Pendaftaran Ditutup</p>
                        <p class="text-xs text-slate-400 mt-1">Batas waktu pendaftaran sudah lewat</p>
                    </div>
                @elseif ($availableSlots <= 0)
                    <div class="text-center py-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 mx-auto mb-2">
                            <i data-lucide="grid" class="w-6 h-6"></i>
                        </div>
                        <p class="font-medium text-slate-600 dark:text-slate-300 text-sm">Slot Penuh</p>
                        <p class="text-xs text-slate-400 mt-1">Semua slot telah terisi</p>
                    </div>
                @else
                    <a href="{{ route('event-registrations.create', ['event_id' => $event->id]) }}" class="w-full btn btn-primary justify-center text-base py-3">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        Daftar Sekarang
                    </a>
                    <p class="text-xs text-center text-slate-400 mt-2">{{ $availableSlots }} slot masih tersedia</p>
                @endif
            </div>

            <div class="card space-y-3">
                <h3 class="font-semibold text-sm pb-2 border-b border-slate-100 dark:border-slate-700">Detail Event</h3>
                @foreach ([
                    ['icon' => 'calendar', 'label' => 'Tanggal Mulai', 'value' => $event->event_start?->format('d M Y, H:i') ?? '-'],
                    ['icon' => 'calendar-x', 'label' => 'Tanggal Selesai', 'value' => $event->event_end?->format('d M Y, H:i') ?? '-'],
                    ['icon' => 'clock', 'label' => 'Deadline Daftar', 'value' => $event->registration_deadline?->format('d M Y, H:i') ?? 'Tidak dibatasi'],
                    ['icon' => 'map-pin', 'label' => 'Lokasi', 'value' => $venueName ?? 'TBD'],
                    ['icon' => 'users', 'label' => 'Kapasitas', 'value' => number_format($event->capacity ?? 0).' orang'],
                    ['icon' => 'grid', 'label' => 'Slot Tersedia', 'value' => $availableSlots.' dari '.$slotTotal],
                ] as $detail)
                    <div class="flex items-start gap-2.5">
                        <i data-lucide="{{ $detail['icon'] }}" class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-[11px] text-slate-400">{{ $detail['label'] }}</p>
                            <p class="text-sm font-medium">{{ $detail['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            @if ($event->description)
                <div class="card">
                    <h3 class="font-semibold mb-3">Tentang Event</h3>
                    <div class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line">{{ $event->description }}</div>
                </div>
            @endif

            @if ($slotTotal > 0)
                <div class="card">
                    <h3 class="font-semibold mb-3">Ketersediaan Slot</h3>
                    <div class="flex items-center gap-4 mb-3">
                        <div>
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $availableSlots }}</p>
                            <p class="text-xs text-slate-400">slot tersedia</p>
                        </div>
                        <div class="flex-1">
                            <div class="w-full h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $slotPercent >= 80 ? 'bg-red-500' : ($slotPercent >= 50 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $slotPercent }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-slate-400 mt-1">
                                <span>{{ $slotBooked }} terisi</span>
                                <span>{{ $slotTotal }} total</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Daftar Event
            </a>
        </div>
    </div>
</x-layouts.app>
