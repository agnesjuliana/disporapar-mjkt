<x-layouts.app title="Event History" current-page="history" role="MASYARAKAT">
    <x-ui.flash-banner />

    <div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="page-title">Event History</h2>
            <p class="page-subtitle">Riwayat pendaftaran Anda sebagai peserta event.</p>
        </div>
        <a href="{{ route('event.calendar') }}" class="btn btn-primary btn-sm">
            <i data-lucide="calendar-days" class="w-4 h-4"></i>
            Cari Event
        </a>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($registrations->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <i data-lucide="ticket" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="font-medium">Belum ada pendaftaran peserta</p>
                <p class="text-xs text-slate-400 mt-1">Daftar event melalui Kalender Acara.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Tanggal Event</th>
                            <th>Tanggal Daftar</th>
                            <th>Absensi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            <tr>
                                <td>
                                    <p class="font-medium text-sm">{{ $registration->event?->name ?? 'Event' }}</p>
                                    <p class="text-xs text-slate-400">{{ $registration->event?->organizer?->organization_name ?? 'Disporapar' }}</p>
                                </td>
                                <td class="text-sm text-slate-500">{{ $registration->event?->event_start?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="text-sm text-slate-500">{{ $registration->registered_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td><x-ui.status-badge :status="$registration->attendance_status" /></td>
                                <td>
                                    <a href="{{ route('participant-registrations.show', $registration) }}" class="btn btn-primary btn-sm">
                                        <i data-lucide="ticket-check" class="w-4 h-4"></i>
                                        Detail
                                    </a>
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
