<x-layouts.app title="Daftar Event" current-page="events" role="TENANT">
    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Event Tersedia</h2>
            <p class="page-subtitle">Temukan event yang sesuai dan daftarkan usaha Anda</p>
        </div>
    </div>

    <div class="card mb-5 p-4">
        <form method="GET" action="{{ route('events.index') }}" class="flex flex-col sm:flex-row gap-3 sm:items-end">
            <div class="flex-1">
                <label class="form-label text-xs" for="search">Cari Event</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Cari nama event atau organizer..." class="form-input pl-10 py-2.5">
                </div>
            </div>
            <button type="submit" class="btn btn-primary py-2.5 px-5 justify-center">
                <i data-lucide="search" class="w-4 h-4"></i>
                Cari
            </button>
            @if ($search !== '')
                <a href="{{ route('events.index') }}" class="btn btn-secondary py-2.5 justify-center">Reset</a>
            @endif
        </form>
    </div>

    @if ($events->isEmpty())
        <div class="card">
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="font-medium">Tidak ada event tersedia saat ini</p>
                <p class="text-xs text-slate-400 mt-1">Cek kembali nanti untuk event baru dari Disporapar</p>
            </div>
        </div>
    @else
        <p class="text-sm text-slate-400 mb-4">Menampilkan {{ $events->total() }} event tersedia</p>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($events as $event)
                @php
                    $slotTotal = $event->slots_count ?? 0;
                    $slotBooked = $event->booked_slots_count ?? 0;
                    $availableSlots = max($slotTotal - $slotBooked, 0);
                    $slotPercent = $slotTotal > 0 ? round(($slotBooked / $slotTotal) * 100) : 0;
                    $deadline = $event->registration_deadline;
                    $deadlineStatus = ! $deadline || $deadline->isFuture()
                        ? ($deadline && $deadline->lte(now()->addDays(3)) ? 'SOON' : 'OPEN')
                        : 'CLOSED';
                    $venueName = $event->venue_type === 'EXTERNAL'
                        ? $event->external_venue_name
                        : $event->venue?->name;
                @endphp

                <div class="card group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden p-0">
                    <div class="relative h-36 bg-slate-100 dark:bg-slate-700 overflow-hidden">
                        @if ($event->banner_url)
                            <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-indigo-50 dark:bg-indigo-950/30">
                                <i data-lucide="calendar" class="w-10 h-10 text-indigo-200 dark:text-indigo-800"></i>
                            </div>
                        @endif

                        @if ($deadlineStatus === 'SOON')
                            <div class="absolute top-2 right-2 badge badge-yellow shadow">Deadline Segera</div>
                        @elseif ($deadlineStatus === 'CLOSED')
                            <div class="absolute top-2 right-2 badge badge-red shadow">Pendaftaran Ditutup</div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-semibold text-slate-800 dark:text-slate-100 mb-1 line-clamp-2">{{ $event->name }}</h3>
                        <p class="text-xs text-slate-400 mb-3">{{ $event->organizer?->organization_name ?? 'Disporapar' }}</p>

                        <div class="space-y-1.5 mb-4">
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 shrink-0"></i>
                                <span>{{ $event->event_start?->format('d M Y') }} - {{ $event->event_end?->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 shrink-0"></i>
                                <span class="truncate">{{ $venueName ?? 'Venue TBD' }}</span>
                            </div>
                            @if ($event->registration_deadline)
                                <div class="flex items-center gap-2 text-xs {{ $deadlineStatus === 'SOON' ? 'text-amber-500 font-medium' : 'text-slate-500' }}">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 shrink-0"></i>
                                    <span>Deadline: {{ $event->registration_deadline->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>

                        @if ($slotTotal > 0)
                            <div class="mb-4">
                                <div class="flex justify-between text-[11px] text-slate-400 mb-1">
                                    <span>{{ $availableSlots }} slot tersedia dari {{ $slotTotal }}</span>
                                    <span>{{ $slotPercent }}% terisi</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $slotPercent >= 80 ? 'bg-red-500' : ($slotPercent >= 50 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $slotPercent }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="mt-auto">
                            <a href="{{ route('events.show', $event) }}" class="w-full btn btn-secondary btn-sm justify-center">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @endif
</x-layouts.app>
