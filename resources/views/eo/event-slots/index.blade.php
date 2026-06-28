<x-layouts.app :title="'Slot - '.$event->name" current-page="event-slots" role="EVENT_ORGANIZER" :active-event-id="$event->id">
    <x-ui.flash-banner />

    <style>
        .slot-modal-overlay {
            background: rgba(15, 23, 42, .42);
            backdrop-filter: none;
            position: fixed;
            top: -4rem;
            bottom: 0;
            height: calc(100vh + 4rem);
            align-items: stretch;
            justify-content: flex-end;
            padding: 0;
        }
        .slot-modal {
            width: min(760px, 100vw);
            max-width: 760px;
            height: 100vh;
            max-height: 100vh;
            border-radius: 0;
            overflow: hidden;
            border: 0;
            border-left: 1px solid #e2e8f0;
            box-shadow: -18px 0 48px rgba(15, 23, 42, .22);
            transform: translateX(24px);
            display: flex;
            flex-direction: column;
        }
        .modal-overlay.open .slot-modal {
            transform: translateX(0);
        }
        .slot-modal-body {
            flex: 1;
            overflow: hidden;
            min-height: 0;
            display: flex;
            flex-direction: column;
        }
        .slot-form {
            min-height: 0;
            height: 100%;
            display: flex;
            flex: 1;
            flex-direction: column;
        }
        #form-multi,
        #form-single {
            flex: 1;
            min-height: 0;
        }
        .slot-form-content {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 1.5rem;
        }
        .slot-form-footer {
            flex-shrink: 0;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e2e8f0;
            background: white;
        }
        .dark .slot-modal {
            border-color: #334155;
        }
        .dark .slot-form-footer {
            border-color: #334155;
            background: #1e293b;
        }
    </style>

    @php
        $totalSlots = $slots->count();
        $bookedSlots = $slots->where('is_booked', true)->count();
        $availableSlots = $totalSlots - $bookedSlots;
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('eo.events.show', $event) }}" class="text-sm text-slate-400 hover:text-emerald-500 flex items-center gap-1 w-fit">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Event
            </a>
            <h2 class="page-title mt-1">Slot - {{ $event->name }}</h2>
            <p class="page-subtitle">Atur slot tenant untuk event yang sudah disetujui.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('eo.events.tenant-registrations.index', $event) }}" class="btn btn-secondary btn-sm">
                <i data-lucide="store" class="w-4 h-4"></i>
                Manajemen Tenant
            </a>
            <button type="button" onclick="openSlotModal()" class="btn btn-primary btn-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Slot
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        @foreach ([
            ['label' => 'Total Slot', 'value' => $totalSlots, 'color' => 'indigo', 'icon' => 'grid'],
            ['label' => 'Terisi', 'value' => $bookedSlots, 'color' => 'emerald', 'icon' => 'check-square'],
            ['label' => 'Tersedia', 'value' => $availableSlots, 'color' => 'blue', 'icon' => 'square'],
        ] as $stat)
            <div class="card p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-lg bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30 flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-slate-500 font-medium">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 leading-tight">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    @if ($slots->isNotEmpty())
        <div class="card mb-5">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <h3 class="font-semibold text-sm">Peta Slot</h3>
                <div class="flex gap-3 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-100 dark:bg-emerald-900/40 inline-block"></span>Terisi</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-slate-100 dark:bg-slate-700 inline-block"></span>Tersedia</span>
                </div>
            </div>
            <div class="grid grid-cols-6 sm:grid-cols-10 lg:grid-cols-12 gap-1.5">
                @foreach ($slots as $slot)
                    <div title="Slot #{{ $slot->slot_number }} - {{ $slot->slot_label ?: '-' }} {{ $slot->is_booked ? '(Terisi)' : '(Tersedia)' }}"
                        class="aspect-square rounded-lg flex items-center justify-center text-xs font-bold transition-all {{ $slot->is_booked ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 cursor-not-allowed' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 hover:text-indigo-600' }}">
                        {{ $slot->slot_number }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card p-0 overflow-hidden">
        @if ($slots->isEmpty())
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="grid" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="font-medium">Belum ada slot dibuat</p>
                <p class="text-xs text-slate-400 mt-1">Buat slot satu per satu atau sekaligus berdasarkan tipe.</p>
                <button type="button" onclick="openSlotModal()" class="btn btn-primary btn-sm mt-4">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Slot
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No. Slot</th>
                            <th>Label</th>
                            <th>Ukuran</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slots as $slot)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $slot->is_booked ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }} font-bold text-sm">
                                        {{ $slot->slot_number }}
                                    </span>
                                </td>
                                <td class="text-sm">{{ $slot->slot_label ?: '-' }}</td>
                                <td class="text-xs text-slate-500">
                                    @if ($slot->slot_width && $slot->slot_long)
                                        {{ rtrim(rtrim(number_format($slot->slot_width, 2, ',', '.'), '0'), ',') }} x {{ rtrim(rtrim(number_format($slot->slot_long, 2, ',', '.'), '0'), ',') }} m
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-sm font-medium">
                                    {{ (float) $slot->price > 0 ? 'Rp '.number_format((float) $slot->price, 0, ',', '.') : 'Gratis' }}
                                </td>
                                <td>
                                    <span class="badge {{ $slot->is_booked ? 'badge-green' : 'badge-gray' }}">
                                        {{ $slot->is_booked ? 'Terisi' : 'Tersedia' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        @if (! $slot->is_booked)
                                            <button type="button" onclick="openModal('edit-slot-{{ $slot->id }}')" class="btn btn-ghost btn-sm btn-icon" title="Edit">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <div id="edit-slot-{{ $slot->id }}" class="modal-overlay">
                                                <div class="modal-box p-6">
                                                    <h3 class="font-semibold mb-3">Edit Slot #{{ $slot->slot_number }}</h3>
                                                    <form method="POST" action="{{ route('eo.events.slots.update', [$event, $slot]) }}" class="space-y-3">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div>
                                                            <label class="form-label text-xs" for="slot_label_{{ $slot->id }}">Label Slot</label>
                                                            <input type="text" name="slot_label" id="slot_label_{{ $slot->id }}" class="form-input text-sm py-1.5" value="{{ old('slot_label', $slot->slot_label) }}">
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div>
                                                                <label class="form-label text-xs" for="slot_width_{{ $slot->id }}">Lebar (m)</label>
                                                                <input type="number" name="slot_width" id="slot_width_{{ $slot->id }}" step="0.1" min="0" class="form-input text-sm py-1.5" value="{{ old('slot_width', $slot->slot_width) }}">
                                                            </div>
                                                            <div>
                                                                <label class="form-label text-xs" for="slot_long_{{ $slot->id }}">Panjang (m)</label>
                                                                <input type="number" name="slot_long" id="slot_long_{{ $slot->id }}" step="0.1" min="0" class="form-input text-sm py-1.5" value="{{ old('slot_long', $slot->slot_long) }}">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="form-label text-xs" for="price_{{ $slot->id }}">Harga (Rp)</label>
                                                            <input type="number" name="price" id="price_{{ $slot->id }}" min="0" class="form-input text-sm py-1.5" value="{{ old('price', (float) $slot->price) }}">
                                                        </div>
                                                        <div class="flex gap-2 justify-end pt-1">
                                                            <button type="button" onclick="closeModal('edit-slot-{{ $slot->id }}')" class="btn btn-secondary btn-sm">Batal</button>
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i data-lucide="save" class="w-4 h-4"></i>
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <form method="POST" action="{{ route('eo.events.slots.destroy', [$event, $slot]) }}" onsubmit="return confirm('Hapus slot #{{ $slot->slot_number }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-sm btn-icon text-red-400" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Tidak dapat diubah</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div id="modal-add-slot" class="modal-overlay slot-modal-overlay">
        <div class="modal-box slot-modal">
            <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 flex items-center justify-center shrink-0">
                        <i data-lucide="grid-2x2" class="w-5 h-5"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-lg leading-tight text-slate-900 dark:text-white">Tambah Slot</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $event->name }}</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modal-add-slot')" class="btn btn-ghost btn-sm btn-icon" title="Tutup">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="slot-modal-body">
            <div class="flex gap-1 bg-slate-100 dark:bg-slate-700 rounded-lg p-1 m-6 mb-0 shrink-0">
                <button type="button" onclick="switchSlotTab('multi')" id="tab-multi" class="flex-1 text-sm py-1.5 rounded-md font-medium transition-all bg-white dark:bg-slate-800 shadow text-slate-700 dark:text-slate-200">
                    Multi-Type Bulk
                </button>
                <button type="button" onclick="switchSlotTab('single')" id="tab-single" class="flex-1 text-sm py-1.5 rounded-md font-medium transition-all text-slate-400">
                    Satu per Satu
                </button>
            </div>

            <div id="form-multi">
                <form method="POST" action="{{ route('eo.events.slots.store', $event) }}" class="slot-form">
                    @csrf
                    <div class="slot-form-content">
                    <div id="slot-types-container" class="space-y-3">
                        <div class="grid grid-cols-12 gap-2 items-end bg-slate-50 dark:bg-slate-800 p-3 rounded-lg border border-slate-100 dark:border-slate-700">
                            <div class="col-span-12 sm:col-span-3">
                                <label class="form-label text-[10px] mb-1">Prefix Label</label>
                                <input type="text" name="types[0][prefix]" class="form-input text-xs py-1.5" placeholder="cth: VIP">
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <label class="form-label text-[10px] mb-1">Jumlah</label>
                                <input type="number" name="types[0][qty]" min="1" class="form-input text-xs py-1.5" placeholder="Qty" required>
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <label class="form-label text-[10px] mb-1">Lebar (m)</label>
                                <input type="number" step="0.1" min="0" name="types[0][width]" class="form-input text-xs py-1.5" placeholder="Lbr">
                            </div>
                            <div class="col-span-6 sm:col-span-2">
                                <label class="form-label text-[10px] mb-1">Panjang (m)</label>
                                <input type="number" step="0.1" min="0" name="types[0][long]" class="form-input text-xs py-1.5" placeholder="Pjg">
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label class="form-label text-[10px] mb-1">Harga (Rp)</label>
                                <div class="flex items-center gap-1">
                                    <input type="number" name="types[0][price]" min="0" class="form-input text-xs py-1.5 w-full" placeholder="0">
                                    <button type="button" class="btn btn-ghost btn-icon text-red-400 opacity-50 cursor-not-allowed" disabled>
                                        <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addSlotTypeRow()" class="text-xs text-indigo-500 font-medium flex items-center gap-1 hover:text-indigo-600 mt-2">
                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                        Tambah Tipe Slot Lain
                    </button>
                    </div>

                    <div class="slot-form-footer flex gap-2 justify-end">
                        <button type="button" onclick="closeModal('modal-add-slot')" class="btn btn-secondary btn-sm">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan Slot
                        </button>
                    </div>
                </form>
            </div>

            <div id="form-single" class="hidden">
                <form method="POST" action="{{ route('eo.events.slots.store', $event) }}" class="slot-form">
                    @csrf
                    <div class="slot-form-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="form-label text-xs" for="slot_number">No. Slot <span class="text-red-500">*</span></label>
                            <input type="number" name="slot_number" id="slot_number" required min="1" class="form-input text-sm" value="{{ $totalSlots + 1 }}">
                        </div>
                        <div>
                            <label class="form-label text-xs" for="slot_label">Label</label>
                            <input type="text" name="slot_label" id="slot_label" class="form-input text-sm" placeholder="cth. A-01">
                        </div>
                        <div>
                            <label class="form-label text-xs" for="slot_width">Lebar (m)</label>
                            <input type="number" name="slot_width" id="slot_width" step="0.1" min="0" class="form-input text-sm" placeholder="3">
                        </div>
                        <div>
                            <label class="form-label text-xs" for="slot_long">Panjang (m)</label>
                            <input type="number" name="slot_long" id="slot_long" step="0.1" min="0" class="form-input text-sm" placeholder="3">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label text-xs" for="price">Harga (Rp)</label>
                            <input type="number" name="price" id="price" min="0" class="form-input text-sm" placeholder="0">
                        </div>
                    </div>
                    </div>
                    <div class="slot-form-footer flex gap-2 justify-end">
                        <button type="button" onclick="closeModal('modal-add-slot')" class="btn btn-secondary btn-sm">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Tambah Slot
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openSlotModal() {
                const modal = document.getElementById('modal-add-slot');
                if (modal && modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }

                openModal('modal-add-slot');
                document.querySelectorAll('#modal-add-slot .slot-form-content').forEach(element => {
                    element.scrollTop = 0;
                });
            }

            function switchSlotTab(tab) {
                const isMulti = tab === 'multi';
                document.getElementById('form-multi').classList.toggle('hidden', !isMulti);
                document.getElementById('form-single').classList.toggle('hidden', isMulti);
                document.getElementById('tab-multi').className = `flex-1 text-sm py-1.5 rounded-md font-medium transition-all ${isMulti ? 'bg-white dark:bg-slate-800 shadow text-slate-700 dark:text-slate-200' : 'text-slate-400'}`;
                document.getElementById('tab-single').className = `flex-1 text-sm py-1.5 rounded-md font-medium transition-all ${!isMulti ? 'bg-white dark:bg-slate-800 shadow text-slate-700 dark:text-slate-200' : 'text-slate-400'}`;
            }

            let typeIndex = 1;
            function addSlotTypeRow() {
                const container = document.getElementById('slot-types-container');
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-2 items-center bg-slate-50 dark:bg-slate-800 p-3 rounded-lg border border-slate-100 dark:border-slate-700 mt-2';
                row.innerHTML = `
                    <div class="col-span-12 sm:col-span-3">
                        <input type="text" name="types[${typeIndex}][prefix]" class="form-input text-xs py-1.5" placeholder="Prefix">
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <input type="number" name="types[${typeIndex}][qty]" min="1" class="form-input text-xs py-1.5" placeholder="Qty" required>
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <input type="number" step="0.1" min="0" name="types[${typeIndex}][width]" class="form-input text-xs py-1.5" placeholder="Lbr">
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <input type="number" step="0.1" min="0" name="types[${typeIndex}][long]" class="form-input text-xs py-1.5" placeholder="Pjg">
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <div class="flex items-center gap-1">
                            <input type="number" min="0" name="types[${typeIndex}][price]" class="form-input text-xs py-1.5 w-full" placeholder="Harga">
                            <button type="button" onclick="this.closest('.grid').remove()" class="btn btn-ghost btn-icon text-red-500 hover:bg-red-50">
                                <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(row);
                if (typeof lucide !== 'undefined') lucide.createIcons();
                typeIndex++;
            }
        </script>
    @endpush
</x-layouts.app>
