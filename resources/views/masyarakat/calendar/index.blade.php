<x-layouts.app title="Kalender Acara" current-page="calendar" :role="auth()->user()->role">
    <x-ui.flash-banner />

    @php
        $previousMonth = $month->copy()->subMonth()->format('Y-m');
        $nextMonth = $month->copy()->addMonth()->format('Y-m');
    @endphp

    <div class="mb-5 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h2 class="page-title">Kalender Acara</h2>
            <p class="page-subtitle">Pilih tanggal untuk melihat event yang berlangsung pada hari tersebut.</p>
        </div>
        <form method="GET" action="{{ route('event.calendar') }}" class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
            <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">
            <input type="hidden" name="month" value="{{ $month->format('Y-m') }}">
            <div class="relative flex-1 lg:w-72">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ $search }}" class="form-input pl-9" placeholder="Cari nama event">
            </div>
            <button type="submit" class="btn btn-primary justify-center">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Cari
            </button>
            @if ($search)
                <a href="{{ route('event.calendar', ['date' => $selectedDate->format('Y-m-d'), 'month' => $month->format('Y-m')]) }}" class="btn btn-secondary justify-center">Reset</a>
            @endif
        </form>
    </div>

    <div class="card mb-6 p-0 overflow-hidden">
        <div class="flex items-center justify-between gap-3 p-4 border-b border-slate-100 dark:border-slate-700">
            <a href="{{ route('event.calendar', array_filter(['month' => $previousMonth, 'date' => $month->copy()->subMonth()->startOfMonth()->format('Y-m-d'), 'search' => $search])) }}" class="btn btn-secondary btn-sm btn-icon" title="Bulan sebelumnya">
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
            </a>
            <div class="text-center">
                <h3 class="font-semibold text-lg">{{ $month->translatedFormat('F Y') }}</h3>
                <p class="text-xs text-slate-400">{{ $selectedDate->translatedFormat('d F Y') }}</p>
            </div>
            <a href="{{ route('event.calendar', array_filter(['month' => $nextMonth, 'date' => $month->copy()->addMonth()->startOfMonth()->format('Y-m-d'), 'search' => $search])) }}" class="btn btn-secondary btn-sm btn-icon" title="Bulan berikutnya">
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-7 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-700">
            @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
                <div class="px-2 py-2 text-center text-xs font-semibold text-slate-500">{{ $dayName }}</div>
            @endforeach
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-700">
            @foreach ($weeks as $week)
                <div class="grid grid-cols-7 divide-x divide-slate-100 dark:divide-slate-700">
                    @foreach ($week as $date)
                        @php
                            $dateKey = $date->format('Y-m-d');
                            $count = $eventCounts[$dateKey] ?? 0;
                            $isSelected = $date->isSameDay($selectedDate);
                            $isCurrentMonth = $date->isSameMonth($month);
                        @endphp
                        <a href="{{ route('event.calendar', array_filter(['date' => $dateKey, 'month' => $month->format('Y-m'), 'search' => $search])) }}"
                            class="min-h-20 p-2 transition-colors {{ $isSelected ? 'bg-indigo-50 dark:bg-indigo-950/40' : 'hover:bg-slate-50 dark:hover:bg-slate-800/60' }} {{ $isCurrentMonth ? '' : 'opacity-40' }}">
                            <div class="flex items-start justify-between gap-1">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-sm font-semibold {{ $isSelected ? 'bg-indigo-600 text-white' : 'text-slate-700 dark:text-slate-200' }}">
                                    {{ $date->day }}
                                </span>
                                @if ($count > 0)
                                    <span class="badge badge-green text-[10px] px-1.5 py-0.5">{{ $count }}</span>
                                @endif
                            </div>
                            @if ($count > 0)
                                <div class="mt-2 h-1.5 rounded-full bg-emerald-400"></div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="font-semibold text-slate-900 dark:text-white">Event pada {{ $selectedDate->translatedFormat('d F Y') }}</h3>
            <p class="text-sm text-slate-500">{{ $events->total() }} event ditemukan{{ $search ? ' untuk "'.$search.'"' : '' }}.</p>
        </div>
        <a href="{{ route('event.calendar', ['date' => today()->format('Y-m-d'), 'month' => today()->format('Y-m')]) }}" class="btn btn-secondary btn-sm">
            <i data-lucide="calendar-days" class="w-4 h-4"></i>
            Hari Ini
        </a>
    </div>

    @if ($events->isEmpty())
        <div class="card text-center py-16 text-slate-500">
            <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
            <p class="font-medium">Tidak ada event pada tanggal ini</p>
            <p class="text-xs text-slate-400 mt-1">Pilih tanggal lain atau ubah kata kunci pencarian.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($events as $event)
                @php
                    $venueName = $event->venue_type === 'EXTERNAL'
                        ? $event->external_venue_name
                        : $event->venue?->name;
                    $isRegistered = in_array($event->id, $registeredEventIds ?? [], true);
                    $canRegisterParticipant = auth()->user()->role === 'MASYARAKAT' && (! $event->event_end || $event->event_end->isFuture());
                @endphp
                <article class="card p-0 overflow-hidden">
                    <div class="h-36 bg-indigo-600 relative">
                        @if ($event->banner_url)
                            <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/25"></div>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-emerald-500"></div>
                        @endif
                        <div class="absolute left-4 bottom-4 right-4">
                            <span class="badge badge-green mb-2">{{ $event->status === 'ONGOING' ? 'Berlangsung' : 'Terjadwal' }}</span>
                            <h4 class="font-bold text-white leading-tight line-clamp-2">{{ $event->name }}</h4>
                        </div>
                    </div>
                    <div class="p-4 space-y-3">
                        <p class="text-sm text-slate-500 line-clamp-2">{{ $event->description }}</p>
                        <div class="space-y-2 text-xs text-slate-500">
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $event->event_start?->format('d M Y H:i') }} - {{ $event->event_end?->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span class="truncate">{{ $venueName ?? 'Venue TBD' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span class="truncate">{{ $event->organizer?->organization_name ?? 'Disporapar' }}</span>
                            </div>
                        </div>
                        @if (auth()->user()->role === 'MASYARAKAT')
                            <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                                @if ($isRegistered)
                                    <a href="{{ route('participant-registrations.index') }}" class="btn btn-secondary btn-sm w-full justify-center">
                                        <i data-lucide="ticket-check" class="w-4 h-4"></i>
                                        Sudah Terdaftar
                                    </a>
                                @elseif ($canRegisterParticipant)
                                    <a href="{{ route('participant-registrations.create', ['event_id' => $event->id]) }}" class="btn btn-primary btn-sm w-full justify-center">
                                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                                        Daftar Peserta
                                    </a>
                                @else
                                    <button type="button" class="btn btn-secondary btn-sm w-full justify-center opacity-70 cursor-not-allowed" disabled>
                                        Event Selesai
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $events->links() }}
        </div>
    @endif
</x-layouts.app>
