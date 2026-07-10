<x-layouts.app :title="'Pengunjung - '.$event->name" current-page="event-visitors" role="EVENT_ORGANIZER" :active-event-id="$event->id">
    <x-ui.flash-banner />

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('eo.events.show', $event) }}" class="text-sm text-slate-400 hover:text-emerald-500 flex items-center gap-1 w-fit">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Event
            </a>
            <h2 class="page-title mt-1">Manajemen Pengunjung</h2>
            <p class="page-subtitle">Statistik dan daftar pengunjung untuk {{ $event->name }}.</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="openModal('scan-checkin-modal')" class="btn btn-primary btn-sm">
                <i data-lucide="scan-line" class="w-4 h-4"></i>
                Scan QR Check-in
            </button>
            <a href="{{ route('eo.events.show', $event) }}" class="btn btn-secondary btn-sm">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                Detail Event
            </a>
        </div>
    </div>

    @push('modals')
    <div id="scan-checkin-modal" class="modal-overlay">
        <div class="modal-box" style="max-width:420px;">
            <div class="p-5 pb-4 border-b border-slate-100 dark:border-slate-700 flex items-start justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-base text-slate-800 dark:text-slate-100">Scan QR Check-in</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Arahkan kamera ke QR code milik peserta.</p>
                </div>
                <button type="button" onclick="closeModal('scan-checkin-modal')" class="btn btn-ghost btn-icon shrink-0 -mt-0.5">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-5 space-y-4">
                <div id="qr-scanner-region" class="rounded-xl overflow-hidden bg-slate-900" style="min-height:240px;"></div>
                <p id="qr-scanner-status" class="text-xs text-slate-400 text-center">Menyiapkan kamera...</p>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white dark:bg-(--mojo-dark-card) px-2 text-xs text-slate-400">atau masukkan manual</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('eo.events.visitors.check-in', $event) }}" id="manual-checkin-form" class="flex gap-2">
                    @csrf
                    <input type="text" name="code" id="manual-checkin-code" class="form-input text-sm py-1.5 font-mono" placeholder="Tempel kode pendaftaran">
                    <button type="submit" class="btn btn-primary btn-sm shrink-0">Check-in</button>
                </form>
            </div>
        </div>
    </div>
    @endpush

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-5">
        @foreach ([
            ['label' => 'Total Pengunjung', 'value' => $stats['total'], 'icon' => 'users', 'color' => 'indigo'],
            ['label' => 'Belum Check-in', 'value' => $stats['not_checked_in'], 'icon' => 'clock', 'color' => 'slate'],
            ['label' => 'Hadir', 'value' => $stats['present'], 'icon' => 'check-circle', 'color' => 'emerald'],
            ['label' => 'Tidak Hadir', 'value' => $stats['absent'], 'icon' => 'x-circle', 'color' => 'red'],
            ['label' => 'Tingkat Hadir', 'value' => $stats['attendance_rate'].'%', 'icon' => 'activity', 'color' => 'blue'],
        ] as $stat)
            <div class="card p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-300 flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-slate-500 truncate">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold leading-tight">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-52">
                <label class="form-label text-xs" for="search">Cari Pengunjung</label>
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" id="search" value="{{ $search }}" class="form-input pl-8 text-sm py-1.5" placeholder="Nama atau email">
                </div>
            </div>
            <div>
                <label class="form-label text-xs" for="attendance_status">Status Absensi</label>
                <select name="attendance_status" id="attendance_status" class="form-select text-sm py-1.5">
                    @foreach (['' => 'Semua', 'NOT_CHECKED_IN' => 'Belum Check-in', 'PRESENT' => 'Hadir', 'ABSENT' => 'Tidak Hadir'] as $value => $label)
                        <option value="{{ $value }}" @selected($attendanceStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('eo.events.visitors.index', $event) }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($registrations->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="font-medium">Belum ada pengunjung terdaftar</p>
                <p class="text-xs text-slate-400 mt-1">Pengunjung yang mendaftar dari kalender acara akan muncul di sini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengunjung</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th>Status Absensi</th>
                            <th>Check-in</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $registrations->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($registration->user?->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <p class="font-medium text-sm">{{ $registration->user?->name ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="text-sm text-slate-500">{{ $registration->user?->email ?? '-' }}</td>
                                <td class="text-sm text-slate-500">{{ $registration->registered_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td><x-ui.status-badge :status="$registration->attendance_status" /></td>
                                <td class="text-sm text-slate-500">{{ $registration->checked_in_at?->format('d M Y H:i') ?? '-' }}</td>
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

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        'use strict';

        var modalEl = document.getElementById('scan-checkin-modal');
        var statusEl = document.getElementById('qr-scanner-status');
        var manualForm = document.getElementById('manual-checkin-form');
        var manualInput = document.getElementById('manual-checkin-code');
        var scanner = null;

        function stopScanner() {
            if (!scanner) return;
            var current = scanner;
            scanner = null;
            current.stop().catch(function () {}).finally(function () {
                current.clear();
            });
        }

        function startScanner() {
            if (typeof Html5Qrcode === 'undefined') {
                statusEl.textContent = 'Kamera tidak tersedia. Gunakan input manual di bawah.';
                return;
            }

            scanner = new Html5Qrcode('qr-scanner-region');
            scanner.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: 220 },
                function (decodedText) {
                    if (!manualForm) return;
                    statusEl.textContent = 'QR terdeteksi, memproses check-in...';
                    stopScanner();
                    manualInput.value = decodedText;
                    manualForm.submit();
                },
                function () { /* abaikan kegagalan pemindaian per-frame */ }
            ).then(function () {
                statusEl.textContent = 'Arahkan kamera ke QR code peserta.';
            }).catch(function () {
                statusEl.textContent = 'Tidak bisa mengakses kamera. Gunakan input manual di bawah.';
            });
        }

        if (modalEl) {
            new MutationObserver(function () {
                if (modalEl.classList.contains('open')) {
                    statusEl.textContent = 'Menyiapkan kamera...';
                    startScanner();
                } else {
                    stopScanner();
                }
            }).observe(modalEl, { attributes: true, attributeFilter: ['class'] });
        }
    });
    </script>
    @endpush
</x-layouts.app>
