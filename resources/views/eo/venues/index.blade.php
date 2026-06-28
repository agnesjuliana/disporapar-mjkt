<x-layouts.app title="Daftar Venue" current-page="venues" role="EVENT_ORGANIZER">

    @push('styles')
    <style>
    /* ──── Venue Date Picker ──── */
    .vdp-type-btn{flex:1;padding:.4rem .75rem;border-radius:.5rem;font-size:.8rem;font-weight:500;border:1px solid #e2e8f0;cursor:pointer;transition:all .15s;background:#f1f5f9;color:#374151;}
    .vdp-type-btn:hover{background:#e2e8f0;}
    .vdp-type-btn.active{background:linear-gradient(135deg,#9f1239,#c2410c);color:#fff;border-color:transparent;box-shadow:0 4px 12px rgba(159,18,57,.2);}
    .dark .vdp-type-btn{background:#3a211f;color:#e3c8ba;border-color:#56312a;}
    .dark .vdp-type-btn:hover{background:#4a2d2b;}
    .dark .vdp-type-btn.active{background:linear-gradient(135deg,#9f1239,#c2410c);color:#fff;border-color:transparent;}

    .vdp-calendar{border:1px solid #e2e8f0;border-radius:.75rem;padding:.75rem;}
    .dark .vdp-calendar{border-color:#56312a;}

    .vdp-nav-btn{background:none;border:1px solid #e2e8f0;border-radius:.4rem;width:1.75rem;height:1.75rem;cursor:pointer;font-size:1.1rem;color:#64748b;display:flex;align-items:center;justify-content:center;flex-shrink:0;line-height:1;transition:background .1s;}
    .vdp-nav-btn:hover{background:#f1f5f9;}
    .dark .vdp-nav-btn{border-color:#56312a;color:#d6b8a6;}
    .dark .vdp-nav-btn:hover{background:#3a211f;}

    .vdp-month-label{font-size:.875rem;font-weight:600;color:#374151;}
    .dark .vdp-month-label{color:#f5e8df;}

    .vdp-day-header{text-align:center;font-size:.7rem;color:#94a3b8;font-weight:600;padding:.2rem 0;}

    .vdp-day-btn{all:unset;cursor:pointer;display:block;width:100%;}
    .vdp-day-btn:disabled{cursor:not-allowed;}

    .vdp-day-inner{position:relative;display:flex;align-items:center;justify-content:center;height:2rem;width:100%;border-radius:.5rem;font-size:.75rem;font-weight:500;color:#374151;transition:background .1s;}
    .dark .vdp-day-inner{color:#e3c8ba;}
    .vdp-day-btn:not(:disabled):hover .vdp-day-inner{background:#f1f5f9;}
    .dark .vdp-day-btn:not(:disabled):hover .vdp-day-inner{background:#3a211f;}

    .vdp-day-inner.vdp-past{color:#cbd5e1;}
    .dark .vdp-day-inner.vdp-past{color:#4a3535;}

    .vdp-day-inner.vdp-blocked{background:rgba(239,68,68,.07);color:#fca5a5;text-decoration:line-through;}
    .dark .vdp-day-inner.vdp-blocked{background:rgba(239,68,68,.1);color:rgba(252,165,165,.4);}

    .vdp-day-inner.vdp-selected{background:#9f1239;color:#fff;font-weight:700;}

    .vdp-day-inner.vdp-in-range{background:rgba(159,18,57,.1);color:#9f1239;border-radius:0;}
    .dark .vdp-day-inner.vdp-in-range{background:rgba(159,18,57,.2);color:#fca5a5;}

    .vdp-day-inner.vdp-range-start{border-radius:.5rem 0 0 .5rem;}
    .vdp-day-inner.vdp-range-end  {border-radius:0 .5rem .5rem 0;}

    .vdp-booking-dot{position:absolute;bottom:2px;left:50%;transform:translateX(-50%);width:4px;height:4px;background:#f59e0b;border-radius:50%;}

    .vdp-hour-btn{padding:.3rem .1rem;border-radius:.4rem;font-size:.68rem;font-weight:500;border:none;cursor:pointer;transition:background .1s;width:100%;text-align:center;background:#f1f5f9;color:#374151;}
    .vdp-hour-btn:hover:not(:disabled){background:#e2e8f0;}
    .dark .vdp-hour-btn{background:#3a211f;color:#e3c8ba;}
    .dark .vdp-hour-btn:hover:not(:disabled){background:#4a2d2b;}
    .vdp-hour-btn.vdp-blocked{background:rgba(239,68,68,.1);color:#fca5a5;cursor:not-allowed;text-decoration:line-through;}
    .dark .vdp-hour-btn.vdp-blocked{background:rgba(239,68,68,.08);color:rgba(252,165,165,.4);}
    .vdp-hour-btn.vdp-selected{background:#9f1239;color:#fff;font-weight:700;}
    .vdp-hour-btn.vdp-in-range{background:rgba(159,18,57,.1);color:#9f1239;}
    .dark .vdp-hour-btn.vdp-in-range{background:rgba(159,18,57,.2);color:#fca5a5;}

    .vdp-summary{background:rgba(159,18,57,.06);border:1px solid rgba(159,18,57,.15);border-radius:.5rem;padding:.5rem .75rem;font-size:.8rem;color:#9f1239;font-weight:500;display:flex;align-items:center;gap:.5rem;}
    .dark .vdp-summary{background:rgba(159,18,57,.12);border-color:rgba(159,18,57,.25);color:#fca5a5;}

    .vdp-error{background:#fef2f2;border:1px solid #fecaca;border-radius:.5rem;padding:.4rem .6rem;font-size:.75rem;color:#dc2626;}
    .dark .vdp-error{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.25);color:#fca5a5;}

    .vdp-legend-dot{width:.75rem;height:.75rem;border-radius:.2rem;display:inline-block;vertical-align:middle;flex-shrink:0;}
    </style>
    @endpush

    <x-ui.flash-banner />

    <div class="page-header">
        <div>
            <h2 class="page-title">Daftar Venue</h2>
            <p class="page-subtitle">Cari venue yang tersedia dan ajukan booking untuk event Anda</p>
        </div>
        <a href="{{ route('eo.venue-booking') }}" class="btn btn-secondary btn-sm">
            <i data-lucide="calendar-check" class="w-4 h-4"></i>
            Lihat Booking Saya
        </a>
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" action="{{ route('eo.daftar-venue') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-60">
                <label class="form-label text-xs" for="search">Cari Venue</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama atau alamat venue..." class="form-input pl-8 text-sm py-1.5">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('eo.daftar-venue') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    @if ($venues->isEmpty())
        <div class="card">
            <div class="text-center py-16 text-slate-500 dark:text-slate-400">
                <i data-lucide="building-2" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p>Tidak ada venue ditemukan</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($venues as $venue)
                @php
                    $approvedBooking  = $venue->venueBookings->firstWhere('status', 'APPROVED');
                    $requestedBooking = $venue->venueBookings->firstWhere('status', 'PENDING');

                    if ($approvedBooking) {
                        $availabilityLabel = 'Unavailable until ' . $approvedBooking->booking_end->format('d M Y H:i');
                        $availabilityClass = 'badge-red';
                        $availabilityIcon  = 'x-circle';
                    } elseif ($requestedBooking) {
                        $availabilityLabel = 'Requested';
                        $availabilityClass = 'badge-yellow';
                        $availabilityIcon  = 'clock';
                    } else {
                        $availabilityLabel = 'Available';
                        $availabilityClass = 'badge-green';
                        $availabilityIcon  = 'check-circle';
                    }
                @endphp

                <div class="card group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    <div class="relative w-full h-36 rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        @if ($venue->image_url)
                            <img src="{{ $venue->image_url }}" alt="Foto {{ $venue->name }}" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/10"></div>
                        @else
                            <i data-lucide="building-2" class="w-10 h-10 text-slate-300"></i>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="badge {{ $availabilityClass }} shadow flex items-center gap-1">
                                <i data-lucide="{{ $availabilityIcon }}" class="w-3 h-3"></i>
                                {{ $availabilityLabel }}
                            </span>
                        </div>
                    </div>

                    <h3 class="font-semibold text-slate-800 dark:text-slate-100 mb-1">{{ $venue->name }}</h3>
                    <div class="flex items-start gap-1.5 text-slate-400 text-xs mb-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 shrink-0 mt-0.5"></i>
                        <span class="line-clamp-1">{{ $venue->address }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 mb-3">
                        <span class="flex items-center gap-1">
                            <i data-lucide="users" class="w-3 h-3"></i>
                            {{ number_format($venue->capacity) }} orang
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="tag" class="w-3 h-3"></i>
                            Rp {{ number_format((float) $venue->lowest_price, 0, ',', '.') }} – Rp {{ number_format((float) $venue->highest_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-4">{{ $venue->description }}</p>

                    <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" onclick="openModal('book-{{ $venue->id }}')" class="btn btn-primary btn-sm flex-1 justify-center">
                            <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                            Booking Venue
                        </button>
                    </div>
                </div>

                {{-- Modal pushed to body-level @stack('modals') to avoid stacking-context issues --}}
                @push('modals')
                <div id="book-{{ $venue->id }}" class="modal-overlay">
                    <div class="modal-box" style="max-width:580px;">
                        <div class="p-5 pb-4 border-b border-slate-100 dark:border-slate-700 flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-base text-slate-800 dark:text-slate-100">Booking {{ $venue->name }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ajukan permintaan booking venue ke admin.</p>
                            </div>
                            <button type="button" onclick="closeModal('book-{{ $venue->id }}')" class="btn btn-ghost btn-icon shrink-0 -mt-0.5">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('eo.venue-booking.store') }}" class="p-5 space-y-4">
                            @csrf
                            <input type="hidden" name="venue_id"      value="{{ $venue->id }}">
                            <input type="hidden" name="booking_type"  id="type-{{ $venue->id }}"  value="HOURLY">
                            <input type="hidden" name="booking_start" id="start-{{ $venue->id }}">
                            <input type="hidden" name="booking_end"   id="end-{{ $venue->id }}">

                            {{-- Event selector --}}
                            <div>
                                <label class="form-label" for="event_id_{{ $venue->id }}">
                                    Event <span class="text-slate-400 font-normal">(opsional)</span>
                                </label>
                                <select name="event_id" id="event_id_{{ $venue->id }}" class="form-select">
                                    <option value="">Tanpa event / belum ditentukan</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->name }}{{ $event->event_start ? ' — ' . $event->event_start->format('d M Y') : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Custom datepicker placeholder (initialised lazily via JS when modal opens) --}}
                            <div>
                                <label class="form-label mb-2">Pilih Waktu Booking</label>
                                <div
                                    class="venue-datepicker"
                                    data-venue="{{ $venue->id }}"
                                    data-bookings="{{ json_encode($bookingsByVenue->get($venue->id, collect())->values()->toArray()) }}"
                                ></div>
                            </div>

                            {{-- Error from datepicker logic --}}
                            <div id="picker-error-{{ $venue->id }}" class="vdp-error hidden"></div>

                            {{-- Server-side validation error --}}
                            @error('booking_start')
                                <div class="vdp-error">{{ $message }}</div>
                            @enderror

                            {{-- Booking summary (filled by JS) --}}
                            <div id="summary-{{ $venue->id }}" class="vdp-summary hidden">
                                <i data-lucide="calendar-check" class="w-4 h-4 shrink-0"></i>
                                <span id="summary-text-{{ $venue->id }}"></span>
                            </div>

                            <div class="flex gap-2 justify-end pt-2 border-t border-slate-100 dark:border-slate-700">
                                <button type="button" onclick="closeModal('book-{{ $venue->id }}')" class="btn btn-secondary btn-sm">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm" id="submit-{{ $venue->id }}" disabled>
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    Kirim Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endpush

            @endforeach
        </div>

        <div class="mt-4">
            {{ $venues->links() }}
        </div>
    @endif

    @push('scripts')
    <script>
    (function () {
        'use strict';

        /* ── locale constants ── */
        var MONTHS_LONG  = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        var MONTHS_SHORT = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        var DAYS_LONG    = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        var DAY_HDR      = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

        /* ════════════════════════════════════════════
           VenueDatePicker class
           ════════════════════════════════════════════ */
        function VenueDatePicker(el) {
            this.el      = el;
            this.venueId = el.dataset.venue;

            try {
                this.bookings = JSON.parse(el.dataset.bookings || '[]').map(function (b) {
                    return { start: new Date(b.start), end: new Date(b.end), status: b.status };
                });
            } catch (_) {
                this.bookings = [];
            }

            this.type = 'HOURLY';

            /* hourly state */
            this.selectedDate = null;
            this.startHour    = null;
            this.endHour      = null;

            /* daily state */
            this.startDate  = null;
            this.endDate    = null;
            this.dateClicks = 0;   /* 0=none, 1=start set, 2=range complete */

            /* calendar view */
            var now = new Date();
            this.viewYear  = now.getFullYear();
            this.viewMonth = now.getMonth();

            this.render();
        }

        /* ─── conflict helpers ─── */

        VenueDatePicker.prototype.isHourBlocked = function (date, h) {
            var s = new Date(date.getFullYear(), date.getMonth(), date.getDate(), h,     0, 0);
            var e = new Date(date.getFullYear(), date.getMonth(), date.getDate(), h + 1, 0, 0);
            return this.bookings.some(function (b) { return b.start < e && b.end > s; });
        };

        VenueDatePicker.prototype.isDayHasAnyBooking = function (date) {
            var s = new Date(date.getFullYear(), date.getMonth(), date.getDate(),     0, 0, 0);
            var e = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1, 0, 0, 0);
            return this.bookings.some(function (b) { return b.start < e && b.end > s; });
        };

        VenueDatePicker.prototype.isDayFullyBlocked = function (date) {
            if (!this.isDayHasAnyBooking(date)) return false;
            for (var h = 0; h < 24; h++) {
                if (!this.isHourBlocked(date, h)) return false;
            }
            return true;
        };

        VenueDatePicker.prototype.isDailyRangeBlocked = function (s, e) {
            var ds = new Date(s.getFullYear(), s.getMonth(), s.getDate());
            var de = new Date(e.getFullYear(), e.getMonth(), e.getDate() + 1);
            return this.bookings.some(function (b) { return b.start < de && b.end > ds; });
        };

        /* ─── state transitions ─── */

        VenueDatePicker.prototype.setType = function (type) {
            this.type = type;
            this._resetSel();
            this.render();
            this._sync();
        };

        VenueDatePicker.prototype._resetSel = function () {
            this.selectedDate = null;
            this.startHour    = null;
            this.endHour      = null;
            this.startDate    = null;
            this.endDate      = null;
            this.dateClicks   = 0;
        };

        VenueDatePicker.prototype.selectDate = function (year, month, day) {
            var date = new Date(year, month, day);

            if (this.type === 'HOURLY') {
                this.selectedDate = date;
                this.startHour    = null;
                this.endHour      = null;

            } else {
                if (this.dateClicks === 0 || this.dateClicks === 2) {
                    this.startDate  = date;
                    this.endDate    = null;
                    this.dateClicks = 1;
                } else {
                    var s = date < this.startDate ? date : this.startDate;
                    var e = date < this.startDate ? this.startDate : date;

                    if (this.isDailyRangeBlocked(s, e)) {
                        this._showError('Rentang tanggal ini memiliki booking yang sudah ada. Pilih rentang lain.');
                        this.startDate  = null;
                        this.endDate    = null;
                        this.dateClicks = 0;
                    } else {
                        this.startDate  = s;
                        this.endDate    = e;
                        this.dateClicks = 2;
                    }
                }
            }

            this.render();
            this._sync();
        };

        VenueDatePicker.prototype.selectHour = function (h) {
            if (this.startHour === null || this.endHour !== null) {
                this.startHour = h;
                this.endHour   = null;
            } else {
                if (h <= this.startHour) {
                    this.startHour = h;
                    this.endHour   = null;
                } else {
                    var conflict = false;
                    for (var i = this.startHour; i < h; i++) {
                        if (this.isHourBlocked(this.selectedDate, i)) { conflict = true; break; }
                    }
                    if (conflict) {
                        this._showError('Terdapat jam yang sudah dipesan dalam rentang ini. Pilih jam lain.');
                        this.startHour = h;
                        this.endHour   = null;
                    } else {
                        this.endHour = h;
                    }
                }
            }
            this.render();
            this._sync();
        };

        VenueDatePicker.prototype._showError = function (msg) {
            var el = document.getElementById('picker-error-' + this.venueId);
            if (!el) return;
            el.textContent = msg;
            el.classList.remove('hidden');
            clearTimeout(this._errTimer);
            this._errTimer = setTimeout(function () { el.classList.add('hidden'); }, 3500);
        };

        /* ─── rendering ─── */

        VenueDatePicker.prototype.render = function () {
            this.el.innerHTML = '';
            this._renderTypeSwitcher();
            this._renderCalendar();
            if (this.type === 'HOURLY' && this.selectedDate) {
                this._renderHourPicker();
            }
        };

        VenueDatePicker.prototype._renderTypeSwitcher = function () {
            var self = this;
            var wrap = this._mk('div', '', 'display:flex;gap:.5rem;margin-bottom:.75rem;');

            ['HOURLY', 'DAILY'].forEach(function (t) {
                var btn = document.createElement('button');
                btn.type      = 'button';
                btn.className = 'vdp-type-btn' + (self.type === t ? ' active' : '');
                btn.textContent = t === 'HOURLY' ? 'Per Jam' : 'Per Hari (24 jam)';
                btn.onclick   = function () { self.setType(t); };
                wrap.appendChild(btn);
            });

            this.el.appendChild(wrap);
        };

        VenueDatePicker.prototype._renderCalendar = function () {
            var self = this;
            var wrap = this._mk('div', 'vdp-calendar');

            /* header */
            var hdr = this._mk('div', '', 'display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem;');
            var prev = this._navBtn('‹', function () {
                self.viewMonth--;
                if (self.viewMonth < 0) { self.viewMonth = 11; self.viewYear--; }
                self.render();
            });
            var next = this._navBtn('›', function () {
                self.viewMonth++;
                if (self.viewMonth > 11) { self.viewMonth = 0; self.viewYear++; }
                self.render();
            });
            var lbl = this._mk('span', 'vdp-month-label');
            lbl.textContent = MONTHS_LONG[this.viewMonth] + ' ' + this.viewYear;
            hdr.append(prev, lbl, next);
            wrap.appendChild(hdr);

            /* day-of-week headers */
            var dhRow = this._mk('div', '', 'display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:.2rem;');
            DAY_HDR.forEach(function (d) {
                var c = document.createElement('div');
                c.className   = 'vdp-day-header';
                c.textContent = d;
                dhRow.appendChild(c);
            });
            wrap.appendChild(dhRow);

            /* days grid */
            var grid     = this._mk('div', '', 'display:grid;grid-template-columns:repeat(7,1fr);gap:2px;');
            var today    = new Date(); today.setHours(0, 0, 0, 0);
            var firstDay = new Date(this.viewYear, this.viewMonth, 1).getDay();
            var lastDay  = new Date(this.viewYear, this.viewMonth + 1, 0).getDate();

            for (var i = 0; i < firstDay; i++) grid.appendChild(document.createElement('div'));

            for (var d = 1; d <= lastDay; d++) {
                var date         = new Date(this.viewYear, this.viewMonth, d);
                var isPast       = date < today;
                var fullyBlocked = !isPast && this.isDayFullyBlocked(date);
                var hasPartial   = !isPast && !fullyBlocked && this.isDayHasAnyBooking(date);

                var isSelected = false, isRangeStart = false, isRangeEnd = false, inRange = false;

                if (this.type === 'HOURLY' && this.selectedDate) {
                    isSelected = (date.toDateString() === this.selectedDate.toDateString());
                } else if (this.type === 'DAILY' && this.startDate) {
                    var s0 = new Date(this.startDate); s0.setHours(0, 0, 0, 0);
                    isRangeStart = (date.toDateString() === s0.toDateString());
                    if (this.endDate) {
                        var e0 = new Date(this.endDate); e0.setHours(0, 0, 0, 0);
                        isRangeEnd = (date.toDateString() === e0.toDateString());
                        inRange    = date > s0 && date < e0;
                    }
                    isSelected = isRangeStart || isRangeEnd;
                }

                var btn   = document.createElement('button');
                btn.type  = 'button';
                btn.className = 'vdp-day-btn';

                var inner = this._mk('span', 'vdp-day-inner');
                inner.textContent = d;

                if (isPast) {
                    inner.classList.add('vdp-past');
                    btn.disabled = true;
                } else if (fullyBlocked) {
                    inner.classList.add('vdp-blocked');
                    btn.disabled = true;
                } else if (isSelected) {
                    inner.classList.add('vdp-selected');
                    if (isRangeStart && this.endDate) inner.classList.add('vdp-range-start');
                    if (isRangeEnd)                   inner.classList.add('vdp-range-end');
                } else if (inRange) {
                    inner.classList.add('vdp-in-range');
                }

                if (hasPartial && !isSelected && !inRange) {
                    inner.appendChild(this._mk('span', 'vdp-booking-dot'));
                }

                if (!isPast && !fullyBlocked) {
                    (function (yr, mo, dy) {
                        btn.onclick = function () { self.selectDate(yr, mo, dy); };
                    })(this.viewYear, this.viewMonth, d);
                }

                btn.appendChild(inner);
                grid.appendChild(btn);
            }

            wrap.appendChild(grid);

            /* DAILY hint */
            if (this.type === 'DAILY') {
                var hint = this._mk('p', '', 'text-align:center;font-size:.7rem;color:#94a3b8;margin-top:.5rem;');
                hint.textContent = this.dateClicks === 1
                    ? 'Klik tanggal akhir booking'
                    : (this.dateClicks === 2
                        ? 'Klik tanggal lain untuk mengubah rentang'
                        : 'Klik tanggal mulai booking');
                wrap.appendChild(hint);
            }

            this.el.appendChild(wrap);
        };

        VenueDatePicker.prototype._navBtn = function (char, cb) {
            var btn = document.createElement('button');
            btn.type      = 'button';
            btn.className = 'vdp-nav-btn';
            btn.textContent = char;
            btn.onclick   = cb;
            return btn;
        };

        VenueDatePicker.prototype._renderHourPicker = function () {
            var self = this;
            var wrap = this._mk('div', '', 'margin-top:.75rem;');

            /* date label */
            var sd   = this.selectedDate;
            var dlbl = this._mk('div', '', 'font-size:.75rem;font-weight:600;color:#64748b;margin-bottom:.25rem;');
            dlbl.textContent = DAYS_LONG[sd.getDay()] + ', ' + sd.getDate() + ' ' + MONTHS_LONG[sd.getMonth()] + ' ' + sd.getFullYear();
            wrap.appendChild(dlbl);

            /* instruction */
            var instr = this._mk('div', '', 'font-size:.7rem;color:#94a3b8;margin-bottom:.4rem;');
            if (this.startHour === null) {
                instr.textContent = 'Pilih jam mulai';
            } else if (this.endHour === null) {
                instr.textContent = 'Jam mulai: ' + this._fmtH(this.startHour) + ' — pilih jam selesai';
            } else {
                instr.textContent = this._fmtH(this.startHour) + ' → ' + this._fmtH(this.endHour) + '  (' + (this.endHour - this.startHour) + ' jam)';
            }
            wrap.appendChild(instr);

            /* hour grid */
            var grid = this._mk('div', '', 'display:grid;grid-template-columns:repeat(6,1fr);gap:3px;');

            for (var h = 0; h < 24; h++) {
                var blocked = this.isHourBlocked(this.selectedDate, h);
                var isStart = (this.startHour === h);
                var isEnd   = (this.endHour   === h);
                var inR     = this.startHour !== null && this.endHour !== null && h > this.startHour && h < this.endHour;

                var btn = document.createElement('button');
                btn.type      = 'button';
                btn.className = 'vdp-hour-btn'
                    + (blocked          ? ' vdp-blocked'  : '')
                    + (isStart || isEnd ? ' vdp-selected' : '')
                    + (inR              ? ' vdp-in-range' : '');
                btn.textContent = this._fmtH(h);

                if (blocked) {
                    btn.disabled = true;
                } else {
                    (function (hour) {
                        btn.onclick = function () { self.selectHour(hour); };
                    })(h);
                }

                grid.appendChild(btn);
            }

            wrap.appendChild(grid);

            /* legend */
            var leg = this._mk('div', '', 'display:flex;flex-wrap:wrap;gap:.6rem;margin-top:.5rem;font-size:.68rem;color:#94a3b8;');
            leg.innerHTML =
                '<span style="display:flex;align-items:center;gap:.25rem;">'
                + '<span class="vdp-legend-dot" style="background:rgba(239,68,68,.15);"></span>Sudah dipesan</span>'
                + '<span style="display:flex;align-items:center;gap:.25rem;">'
                + '<span class="vdp-legend-dot" style="background:#9f1239;"></span>Dipilih</span>'
                + '<span style="display:flex;align-items:center;gap:.25rem;">'
                + '<span class="vdp-legend-dot" style="background:rgba(159,18,57,.12);border:1px solid rgba(159,18,57,.25);"></span>Rentang</span>';
            wrap.appendChild(leg);

            this.el.appendChild(wrap);
        };

        /* ─── sync hidden inputs with current selection ─── */

        VenueDatePicker.prototype._sync = function () {
            var startEl  = document.getElementById('start-'   + this.venueId);
            var endEl    = document.getElementById('end-'     + this.venueId);
            var typeEl   = document.getElementById('type-'    + this.venueId);
            var submitEl = document.getElementById('submit-'  + this.venueId);
            var sumWrap  = document.getElementById('summary-' + this.venueId);
            var sumText  = document.getElementById('summary-text-' + this.venueId);

            if (typeEl) typeEl.value = this.type;

            var valid = false, summary = '';

            if (this.type === 'HOURLY'
                && this.selectedDate
                && this.startHour !== null
                && this.endHour   !== null
                && this.endHour   >  this.startHour)
            {
                var start = new Date(this.selectedDate); start.setHours(this.startHour, 0, 0, 0);
                var end   = new Date(this.selectedDate); end.setHours(this.endHour,     0, 0, 0);
                if (startEl) startEl.value = this._dtl(start);
                if (endEl)   endEl.value   = this._dtl(end);

                var hours = this.endHour - this.startHour;
                var sd    = this.selectedDate;
                summary   = sd.getDate() + ' ' + MONTHS_SHORT[sd.getMonth()] + ' ' + sd.getFullYear()
                          + '  ·  ' + this._fmtH(this.startHour) + ' – ' + this._fmtH(this.endHour)
                          + '  (' + hours + ' jam)';
                valid = true;

            } else if (this.type === 'DAILY' && this.startDate && this.endDate) {
                var sDay = new Date(this.startDate); sDay.setHours(0, 0, 0, 0);
                var eDay = new Date(this.endDate);   eDay.setDate(eDay.getDate() + 1); eDay.setHours(0, 0, 0, 0);
                if (startEl) startEl.value = this._dtl(sDay);
                if (endEl)   endEl.value   = this._dtl(eDay);

                var days  = Math.round((this.endDate - this.startDate) / 864e5) + 1;
                var fmtD  = function (d) { return d.getDate() + ' ' + MONTHS_SHORT[d.getMonth()] + ' ' + d.getFullYear(); };
                summary   = fmtD(this.startDate) + ' – ' + fmtD(this.endDate) + '  (' + days + ' hari)';
                valid = true;

            } else {
                if (startEl) startEl.value = '';
                if (endEl)   endEl.value   = '';
            }

            if (submitEl) submitEl.disabled = !valid;

            if (sumWrap) {
                sumWrap.classList.toggle('hidden', !valid);
                if (sumText && valid) sumText.textContent = summary;
            }

            if (typeof lucide !== 'undefined') lucide.createIcons();
        };

        /* ─── DOM helpers ─── */

        VenueDatePicker.prototype._mk = function (tag, className, extraStyle) {
            var el = document.createElement(tag);
            if (className)  el.className = className;
            if (extraStyle) el.style.cssText = extraStyle;
            return el;
        };

        VenueDatePicker.prototype._fmtH = function (h) {
            return (h < 10 ? '0' : '') + h + ':00';
        };

        VenueDatePicker.prototype._dtl = function (date) {
            var p = function (n) { return (n < 10 ? '0' : '') + n; };
            return date.getFullYear() + '-' + p(date.getMonth() + 1) + '-' + p(date.getDate())
                 + 'T' + p(date.getHours()) + ':' + p(date.getMinutes());
        };

        /* ════════════════════════════════════════════
           Lazy-init: create a picker the first time
           its modal is opened
           ════════════════════════════════════════════ */
        var _origOpen = window.openModal;
        window.openModal = function (id) {
            _origOpen(id);
            if (!id.startsWith('book-')) return;
            var venueId = id.slice(5);
            var el = document.querySelector('.venue-datepicker[data-venue="' + venueId + '"]');
            if (el && !el._picker) {
                el._picker = new VenueDatePicker(el);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        };

    })();
    </script>
    @endpush

</x-layouts.app>
