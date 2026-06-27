<x-layouts.app title="Booking Tenant" current-page="tenant-bookings" role="TENANT">
    <x-ui.flash-banner />

    <div class="mb-5">
        <a href="{{ route('events.show', $event) }}" class="text-sm text-slate-400 hover:text-orange-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Detail Event
        </a>
        <h2 class="page-title mt-2">Booking Tenant</h2>
        <p class="text-sm text-slate-400 mt-0.5">Mendaftar sebagai: <strong>{{ $tenant->organization_name }}</strong></p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('event-registrations.store') }}">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="card mb-4 border border-indigo-100 dark:border-indigo-900 bg-indigo-50/50 dark:bg-indigo-950/20">
                    <h3 class="font-semibold text-sm mb-2 text-indigo-700 dark:text-indigo-300">Event yang Dipilih</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $event->name }}</p>
                            <p class="text-xs text-slate-400">{{ $event->event_start?->format('d M Y') }} · {{ $event->venue?->name ?? $event->external_venue_name ?? 'Venue TBD' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                        <div>
                            <h3 class="font-semibold">Preferensi Slot</h3>
                            <p class="text-xs text-slate-400 mt-1">Pilih jumlah tenant/booth dan nomor slot yang diinginkan. Organizer masih dapat menata ulang slot final.</p>
                        </div>
                        <span class="badge badge-blue">{{ $event->slots->count() }} slot tersedia</span>
                    </div>

                    <div class="mb-4 max-w-xs">
                        <label class="form-label" for="requested_slot_count">Jumlah tenant / slot diminta <span class="text-red-500">*</span></label>
                        <input type="number" name="requested_slot_count" id="requested_slot_count" min="1" max="{{ max($event->slots->count(), 1) }}" value="{{ old('requested_slot_count', 1) }}" class="form-input">
                    </div>

                    @if ($event->slots->isEmpty())
                        <div class="text-sm text-slate-500 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-lg p-4">
                            Event ini belum memiliki slot yang dapat dipilih. Organizer akan menetapkan slot setelah booking disetujui.
                        </div>
                    @else
                        <p class="text-xs text-slate-400 mb-3">Nomor slot pilihan bersifat preferensi. Kosongkan pilihan jika ingin organizer memilihkan slot.</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                            @foreach ($event->slots as $slot)
                                @php($checked = in_array($slot->id, old('requested_slot_ids', []), true))
                                <label class="group cursor-pointer">
                                    <input type="checkbox" name="requested_slot_ids[]" value="{{ $slot->id }}" class="sr-only peer" @checked($checked)>
                                    <div class="h-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-3 transition-all peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-200 dark:peer-checked:ring-indigo-900 hover:border-indigo-300">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-sm peer-checked:bg-indigo-100">
                                                {{ $slot->slot_number }}
                                            </span>
                                            <span class="text-[11px] text-slate-400">{{ (float) $slot->price > 0 ? 'Rp '.number_format((float) $slot->price, 0, ',', '.') : 'Gratis' }}</span>
                                        </div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-200 mt-2 truncate">{{ $slot->slot_label ?: 'Slot #'.$slot->slot_number }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $slot->slot_width && $slot->slot_long ? $slot->slot_width.' x '.$slot->slot_long.' m' : 'Ukuran TBD' }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="card mb-4">
                    <h3 class="font-semibold mb-3">Catatan Tambahan</h3>
                    <label class="form-label" for="notes">Catatan / Keterangan</label>
                    <textarea name="notes" id="notes" rows="4" class="form-textarea" placeholder="Ceritakan produk yang akan Anda tampilkan, kebutuhan khusus venue, atau catatan untuk organizer.">{{ old('notes') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Kirim Booking
                    </button>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            <div class="card bg-orange-50 dark:bg-orange-950/30 border-orange-100 dark:border-orange-900">
                <h3 class="font-semibold text-orange-700 dark:text-orange-300 mb-2 text-sm flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    Informasi
                </h3>
                <ul class="space-y-1.5 text-xs text-orange-600 dark:text-orange-400">
                    <li>Booking Anda akan ditinjau Event Organizer.</li>
                    <li>Status awal booking adalah menunggu persetujuan.</li>
                    <li>Slot akan ditetapkan oleh organizer setelah booking disetujui.</li>
                </ul>
            </div>

            <div class="card">
                <h3 class="font-semibold text-sm mb-3 border-b border-slate-100 dark:border-slate-700 pb-2">Ringkasan Event</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between gap-3"><span class="text-slate-400">Event</span><span class="font-medium text-right truncate">{{ $event->name }}</span></div>
                    <div class="flex justify-between gap-3"><span class="text-slate-400">Mulai</span><span class="font-medium">{{ $event->event_start?->format('d M Y') }}</span></div>
                    <div class="flex justify-between gap-3"><span class="text-slate-400">Selesai</span><span class="font-medium">{{ $event->event_end?->format('d M Y') }}</span></div>
                    @if ($event->registration_deadline)
                        <div class="flex justify-between gap-3"><span class="text-slate-400">Deadline</span><span class="font-medium text-amber-500">{{ $event->registration_deadline->format('d M Y') }}</span></div>
                    @endif
                    <div class="flex justify-between gap-3"><span class="text-slate-400">Organizer</span><span class="font-medium text-right">{{ $event->organizer?->organization_name ?? 'Disporapar' }}</span></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
