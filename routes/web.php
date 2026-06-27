<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EoEventController;
use App\Http\Controllers\EoVenueBookingController;
use App\Http\Controllers\EventController;
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

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'chooseRole'])->name('register');
    Route::get('/register/masyarakat', [AuthController::class, 'showMasyarakatRegistration'])->name('register.masyarakat');
    Route::post('/register/masyarakat', [AuthController::class, 'registerMasyarakat'])->name('register.masyarakat.store');
    Route::get('/register/tenant', [AuthController::class, 'showTenantRegistration'])->name('register.tenant');
    Route::post('/register/tenant', [AuthController::class, 'registerTenant'])->name('register.tenant.store');
    Route::get('/register/event-organizer', [AuthController::class, 'showEventOrganizerRegistration'])->name('register.event-organizer');
    Route::post('/register/event-organizer', [AuthController::class, 'registerEventOrganizer'])->name('register.event-organizer.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{page}', [DashboardController::class, 'placeholder'])->name('dashboard.placeholder');

    Route::resource('admin/venues', VenueController::class)
        ->names('admin.venues')
        ->middleware('role:ADMIN');
    Route::post('admin/venue-bookings/{venue_booking}/approve', [VenueBookingController::class, 'approve'])
        ->name('admin.venue-bookings.approve')
        ->middleware('role:ADMIN');
    Route::post('admin/venue-bookings/{venue_booking}/reject', [VenueBookingController::class, 'reject'])
        ->name('admin.venue-bookings.reject')
        ->middleware('role:ADMIN');
    Route::resource('admin/venue-bookings', VenueBookingController::class)
        ->only(['index', 'show'])
        ->names('admin.venue-bookings')
        ->middleware('role:ADMIN');
    Route::post('admin/events/{event}/approve', [AdminEventController::class, 'approve'])
        ->name('admin.events.approve')
        ->middleware('role:ADMIN');
    Route::post('admin/events/{event}/reject', [AdminEventController::class, 'reject'])
        ->name('admin.events.reject')
        ->middleware('role:ADMIN');
    Route::resource('admin/events', AdminEventController::class)
        ->only(['index', 'show', 'destroy'])
        ->names('admin.events')
        ->middleware('role:ADMIN');

    Route::middleware('role:EVENT_ORGANIZER')->group(function () {
        Route::post('/eo/events/{event}/submit', [EoEventController::class, 'submit'])->name('eo.events.submit');
        Route::post('/eo/events/{event}/cancel', [EoEventController::class, 'cancel'])->name('eo.events.cancel');
        Route::resource('/eo/events', EoEventController::class)
            ->names('eo.events')
            ->except(['destroy']);
        Route::get('/eo/daftar-venue', [EoVenueBookingController::class, 'venues'])->name('eo.daftar-venue');
        Route::get('/eo/venue-booking', [EoVenueBookingController::class, 'bookings'])->name('eo.venue-booking');
        Route::post('/eo/venue-booking', [EoVenueBookingController::class, 'store'])->name('eo.venue-booking.store');
    });
});

Route::resource('users', UserController::class);
Route::resource('tenants', TenantController::class);
Route::resource('event-organizers', EventOrganizerController::class);
Route::resource('events', EventController::class);
Route::resource('event-slots', EventSlotController::class);
Route::resource('event-registrations', EventRegistrationController::class);
Route::resource('registration-documents', RegistrationDocumentController::class);
Route::resource('registration-slots', RegistrationSlotController::class);
Route::resource('registration-attendances', RegistrationAttendanceController::class);
Route::resource('participant-registrations', ParticipantRegistrationController::class);
