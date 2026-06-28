<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VenueBooking extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'venue_id',
        'organizer_id',
        'event_id',
        'booking_start',
        'booking_end',
        'booking_type',
        'status',
        'requested_at',
        'approved_by',
        'approved_at',
        'final_price',
    ];

    protected function casts(): array
    {
        return [
            'booking_start' => 'datetime',
            'booking_end' => 'datetime',
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'final_price' => 'decimal:2',
        ];
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(EventOrganizer::class, 'organizer_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
