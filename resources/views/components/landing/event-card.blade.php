@props(['event'])

@php
    $slotTotal = $event->slots_count ?? 0;
    $slotBooked = $event->booked_slots_count ?? 0;
    $availableSlots = max($slotTotal - $slotBooked, 0);
    $slotPercent = $slotTotal > 0 ? round(($slotBooked / $slotTotal) * 100) : 0;
    $role = auth()->user()?->role;
@endphp

<article class="landing-event-card fade-in flex flex-col">
    <div class="relative h-40 bg-slate-100 dark:bg-brand-dark-surface shrink-0">
        @if ($event->banner_url)
            <img src="{{ $event->banner_url }}" alt="Banner {{ $event->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <i data-lucide="image" class="w-8 h-8 text-slate-300"></i>
            </div>
        @endif
        <span class="absolute top-3 left-3 badge badge-orange bg-white/95">
            {{ $event->event_start->format('d M Y') }}
        </span>
    </div>

    <div class="p-4 flex flex-col flex-1">
        <h3 class="font-semibold text-slate-900 dark:text-white line-clamp-1">{{ $event->name }}</h3>
        <p class="mt-1 text-xs text-slate-500 dark:text-orange-100/60 inline-flex items-center gap-1.5">
            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
            {{ $event->venue?->name ?? $event->external_venue_name ?? 'Lokasi menyusul' }}
        </p>
        <p class="mt-2 text-xs text-slate-400 dark:text-orange-100/50 inline-flex items-center gap-1.5">
            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
            Diselenggarakan oleh {{ $event->organizer?->organization_name }}
        </p>

        @if ($role === 'TENANT' && $slotTotal > 0)
            <div class="mt-3">
                <div class="flex justify-between text-[11px] text-slate-400 dark:text-orange-100/50 mb-1">
                    <span>Open tenant &middot; {{ $availableSlots }} slot tersisa dari {{ $slotTotal }}</span>
                    <span>{{ $slotPercent }}% terisi</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 dark:bg-brand-dark-surface rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $slotPercent >= 80 ? 'bg-red-500' : ($slotPercent >= 50 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $slotPercent }}%"></div>
                </div>
            </div>
        @endif

        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-brand-dark-border">
            @if ($role === 'MASYARAKAT')
                <a href="{{ route('participant-registrations.create', ['event_id' => $event->id]) }}" class="w-full btn btn-primary btn-sm justify-center">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Daftar Peserta
                </a>
            @elseif ($role === 'TENANT')
                <a href="{{ route('event-registrations.create', ['event_id' => $event->id]) }}" class="w-full btn btn-sm justify-center bg-brand-terracotta text-white hover:opacity-90">
                    <i data-lucide="store" class="w-4 h-4"></i>
                    Booking Tenant
                </a>
            @elseif (in_array($role, ['EVENT_ORGANIZER', 'ADMIN'], true))
                <a href="{{ route('dashboard') }}" class="w-full btn btn-secondary btn-sm justify-center">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full btn btn-secondary btn-sm justify-center">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Masuk untuk Ikut Serta
                </a>
            @endif
        </div>
    </div>
</article>
