<x-layouts.app title="Edit Event" current-page="events" role="EVENT_ORGANIZER">
    <x-ui.flash-banner />

    <div class="mb-5">
        <a href="{{ route('eo.events.show', $event) }}" class="text-sm text-slate-400 hover:text-emerald-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Detail Event
        </a>
        <h2 class="page-title mt-2">Edit Event</h2>
        <p class="text-sm text-slate-400">Mengedit: <strong>{{ $event->name }}</strong></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            @include('eo.events._form', [
                'event' => $event,
                'venues' => $venues,
                'action' => route('eo.events.update', $event),
                'method' => 'PUT',
                'submitLabel' => 'Simpan Perubahan',
            ])
        </div>

        <div>
            <div class="card bg-amber-50 dark:bg-amber-950/30 border-amber-100 dark:border-amber-900">
                <h3 class="font-semibold text-amber-700 dark:text-amber-300 mb-2 text-sm flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    Catatan
                </h3>
                <ul class="space-y-1.5 text-xs text-amber-600 dark:text-amber-400">
                    <li>Hanya event berstatus draft atau ditolak yang dapat diedit.</li>
                    <li>Setelah menyimpan, ajukan ulang ke admin untuk persetujuan.</li>
                    <li>Event yang sudah disetujui tidak dapat diubah dari halaman ini.</li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.app>

