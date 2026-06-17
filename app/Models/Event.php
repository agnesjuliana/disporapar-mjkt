<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'organizer_id',
        'venue_type',
        'venue_id',
        'external_venue_name',
        'external_venue_address',
        'external_venue_capacity',
        'event_start',
        'event_end',
        'slot_size',
        'capacity',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'external_venue_capacity' => 'integer',
            'event_start' => 'datetime',
            'event_end' => 'datetime',
            'slot_size' => 'integer',
            'capacity' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(EventOrganizer::class, 'organizer_id');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function venueBookings(): HasMany
    {
        return $this->hasMany(VenueBooking::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(EventSlot::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function participantRegistrations(): HasMany
    {
        return $this->hasMany(ParticipantRegistration::class);
    }
}
