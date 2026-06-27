<x-layouts.app title="Buat Event" current-page="events" role="EVENT_ORGANIZER">
    <x-ui.flash-banner />

    <div class="mb-5">
        <a href="{{ route('eo.events.index') }}" class="text-sm text-slate-400 hover:text-emerald-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Event
        </a>
        <h2 class="page-title mt-2">Buat Event Baru</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            @include('eo.events._form', [
                'event' => $event,
                'venues' => $venues,
                'action' => route('eo.events.store'),
                'submitLabel' => 'Simpan sebagai Draft',
            ])
        </div>

        <div class="space-y-4">
            <div class="card bg-emerald-50 dark:bg-emerald-950/30 border-emerald-100 dark:border-emerald-900">
                <h3 class="font-semibold text-emerald-700 dark:text-emerald-300 mb-3 text-sm flex items-center gap-2">
                    <i data-lucide="lightbulb" class="w-4 h-4"></i>
                    Alur Pembuatan Event
                </h3>
                <ol class="space-y-2 text-xs text-emerald-600 dark:text-emerald-400">
                    <li class="flex gap-2"><span class="font-bold">1.</span> Isi form dan simpan sebagai draft.</li>
                    <li class="flex gap-2"><span class="font-bold">2.</span> Ajukan event ke admin untuk persetujuan.</li>
                    <li class="flex gap-2"><span class="font-bold">3.</span> Setelah disetujui, slot dan pendaftaran tenant dapat dikelola.</li>
                </ol>
            </div>
            <div class="card bg-amber-50 dark:bg-amber-950/30 border-amber-100 dark:border-amber-900">
                <div class="flex gap-2.5">
                    <i data-lucide="info" class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-600 dark:text-amber-400">Event yang diajukan akan ditinjau oleh Admin Disporapar sebelum dipublikasikan kepada tenant.</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
