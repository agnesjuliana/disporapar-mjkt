<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EoEventController;
use App\Http\Controllers\EoEventSlotController;
use App\Http\Controllers\EoParticipantRegistrationController;
use App\Http\Controllers\EoTenantRegistrationController;
use App\Http\Controllers\EoVenueBookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\EventOrganizerController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\EventSlotController;
use App\Http\Controllers\ParticipantRegistrationController;
use App\Http\Controllers\RegistrationAttendanceController;
use App\Http\Controllers\RegistrationDocumentController;
use App\Http\Controllers\RegistrationSlotController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueBookingController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

// ──────────────────────────────────────────────
// Guest-only routes
// ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/verify-email', [AuthController::class, 'showVerification'])->name('verification.notice');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/verify-email/resend', [AuthController::class, 'resendVerificationOtp'])->name('verification.resend');
    Route::get('/register', [AuthController::class, 'chooseRole'])->name('register');
    Route::get('/register/masyarakat', [AuthController::class, 'showMasyarakatRegistration'])->name('register.masyarakat');
    Route::post('/register/masyarakat', [AuthController::class, 'registerMasyarakat'])->name('register.masyarakat.store');
    Route::get('/register/tenant', [AuthController::class, 'showTenantRegistration'])->name('register.tenant');
    Route::post('/register/tenant', [AuthController::class, 'registerTenant'])->name('register.tenant.store');
    Route::get('/register/event-organizer', [AuthController::class, 'showEventOrganizerRegistration'])->name('register.event-organizer');
    Route::post('/register/event-organizer', [AuthController::class, 'registerEventOrganizer'])->name('register.event-organizer.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ──────────────────────────────────────────────
// Authenticated routes
// ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard & shared
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/dashboard/calendar', [EventCalendarController::class, 'index'])->name('event.calendar');
    Route::get('/dashboard/{page}', [DashboardController::class, 'placeholder'])->name('dashboard.placeholder');

    // ── Admin ────────────────────────────────
    Route::middleware('role:ADMIN')->group(function () {
        Route::resource('admin/venues', VenueController::class)->names('admin.venues');

        Route::resource('admin/venue-bookings', VenueBookingController::class)
            ->only(['index', 'show'])
            ->names('admin.venue-bookings');
        Route::post('admin/venue-bookings/{venue_booking}/approve', [VenueBookingController::class, 'approve'])
            ->name('admin.venue-bookings.approve');
        Route::post('admin/venue-bookings/{venue_booking}/reject', [VenueBookingController::class, 'reject'])
            ->name('admin.venue-bookings.reject');

        Route::resource('admin/events', AdminEventController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('admin.events');
        Route::post('admin/events/{event}/approve', [AdminEventController::class, 'approve'])->name('admin.events.approve');
        Route::post('admin/events/{event}/reject', [AdminEventController::class, 'reject'])->name('admin.events.reject');

        Route::resource('users', UserController::class);

        // Placeholder for future admin APIs (scaffolded, not yet implemented)
        Route::resource('tenants', TenantController::class);
        Route::resource('event-organizers', EventOrganizerController::class);
        Route::resource('event-slots', EventSlotController::class);
        Route::resource('registration-documents', RegistrationDocumentController::class);
        Route::resource('registration-slots', RegistrationSlotController::class);
        Route::resource('registration-attendances', RegistrationAttendanceController::class);
    });

    // ── Event Organizer ──────────────────────
    Route::middleware('role:EVENT_ORGANIZER')->group(function () {
        Route::resource('/eo/events', EoEventController::class)
            ->names('eo.events')
            ->except(['destroy']);
        Route::post('/eo/events/{event}/submit', [EoEventController::class, 'submit'])->name('eo.events.submit');
        Route::post('/eo/events/{event}/cancel', [EoEventController::class, 'cancel'])->name('eo.events.cancel');

        Route::get('/eo/events/{event}/visitors', [EoParticipantRegistrationController::class, 'index'])
            ->name('eo.events.visitors.index');

        Route::get('/eo/events/{event}/tenant-registrations', [EoTenantRegistrationController::class, 'index'])
            ->name('eo.events.tenant-registrations.index');
        Route::get('/eo/events/{event}/tenant-registrations/{registration}', [EoTenantRegistrationController::class, 'show'])
            ->name('eo.events.tenant-registrations.show');
        Route::post('/eo/events/{event}/tenant-registrations/{registration}/approve', [EoTenantRegistrationController::class, 'approve'])
            ->name('eo.events.tenant-registrations.approve');
        Route::post('/eo/events/{event}/tenant-registrations/{registration}/assign', [EoTenantRegistrationController::class, 'assign'])
            ->name('eo.events.tenant-registrations.assign');
        Route::post('/eo/events/{event}/tenant-registrations/{registration}/reject', [EoTenantRegistrationController::class, 'reject'])
            ->name('eo.events.tenant-registrations.reject');

        Route::get('/eo/events/{event}/slots', [EoEventSlotController::class, 'index'])->name('eo.events.slots.index');
        Route::post('/eo/events/{event}/slots', [EoEventSlotController::class, 'store'])->name('eo.events.slots.store');
        Route::patch('/eo/events/{event}/slots/{eventSlot}', [EoEventSlotController::class, 'update'])->name('eo.events.slots.update');
        Route::delete('/eo/events/{event}/slots/{eventSlot}', [EoEventSlotController::class, 'destroy'])->name('eo.events.slots.destroy');

        Route::get('/eo/daftar-venue', [EoVenueBookingController::class, 'venues'])->name('eo.daftar-venue');
        Route::get('/eo/venue-booking', [EoVenueBookingController::class, 'bookings'])->name('eo.venue-booking');
        Route::post('/eo/venue-booking', [EoVenueBookingController::class, 'store'])->name('eo.venue-booking.store');
    });

    // ── Tenant ───────────────────────────────
    Route::middleware('role:TENANT')->group(function () {
        Route::resource('events', EventController::class)->only(['index', 'show']);

        Route::resource('event-registrations', EventRegistrationController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::post('event-registrations/{event_registration}/cancel', [EventRegistrationController::class, 'cancel'])
            ->name('event-registrations.cancel');
    });

    // ── Masyarakat ───────────────────────────
    Route::middleware('role:MASYARAKAT')->group(function () {
        Route::resource('participant-registrations', ParticipantRegistrationController::class)
            ->only(['index', 'create', 'store', 'show']);
    });
});
