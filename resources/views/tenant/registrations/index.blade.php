<x-layouts.app title="Tenant Booking" current-page="tenant-bookings" role="TENANT">
    <x-ui.flash-banner />

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="page-title">Tenant Booking</h2>
            <p class="text-sm text-slate-500 mt-1">Riwayat booking tenant ke event dan status persetujuan dari Event Organizer.</p>
        </div>
        <a href="{{ route('events.index') }}" class="btn btn-primary btn-sm">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i>
            Cari Event
        </a>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex flex-wrap gap-2">
            @foreach (['' => 'Semua', 'PENDING' => 'Menunggu', 'APPROVED' => 'Disetujui', 'REJECTED' => 'Ditolak'] as $value => $label)
                <a href="{{ route('event-registrations.index', array_filter(['status' => $value])) }}"
                    class="badge {{ $status === $value ? 'badge-indigo' : 'badge-gray' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if ($registrations->isEmpty())
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="clipboard-list" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="font-medium">Belum ada booking tenant</p>
                <p class="text-xs text-slate-400 mt-1">Anda belum mendaftar ke event manapun.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Tanggal Booking</th>
                            <th>Status</th>
                            <th>Slot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            <tr>
                                <td>
                                    <p class="font-medium text-sm text-slate-800 dark:text-slate-200">{{ $registration->event?->name ?? 'Event' }}</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Mulai: {{ $registration->event?->event_start?->format('d M Y H:i') ?? '-' }}</p>
                                </td>
                                <td class="text-sm text-slate-600 dark:text-slate-400">
                                    {{ $registration->registered_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td><x-ui.status-badge :status="$registration->registration_status" /></td>
                                <td class="text-sm text-slate-500">
                                    {{ $registration->slots_count ?? $registration->slots()->count() }} ditetapkan
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="{{ route('event-registrations.show', $registration) }}" class="btn btn-primary btn-sm">Detail</a>
                                        @if ($registration->registration_status === 'PENDING')
                                            <form method="POST" action="{{ route('event-registrations.cancel', $registration) }}" onsubmit="return confirm('Batalkan booking tenant ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-sm text-red-500">Batalkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
