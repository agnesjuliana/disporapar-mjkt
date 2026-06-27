<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'id',
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
        'registration_deadline',
        'slot_size',
        'capacity',
        'banner_url',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'external_venue_capacity' => 'integer',
            'event_start' => 'datetime',
            'event_end' => 'datetime',
            'registration_deadline' => 'datetime',
            'slot_size' => 'integer',
            'capacity' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function scopeForOrganizer(Builder $query, string $organizerId): Builder
    {
        return $query->where('organizer_id', $organizerId);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query) use ($search) {
            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when($status, fn (Builder $query) => $query->where('status', $status));
    }

    public function scopeWithEventCounts(Builder $query): Builder
    {
        return $query->withCount([
            'slots',
            'slots as booked_slots_count' => fn (Builder $query) => $query->where('is_booked', true),
            'registrations',
        ]);
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
