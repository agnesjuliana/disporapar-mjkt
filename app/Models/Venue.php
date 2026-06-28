<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'address',
        'capacity',
        'description',
        'image_url',
        'lowest_price',
        'highest_price',
        'available_from',
        'available_to',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'lowest_price' => 'decimal:2',
            'highest_price' => 'decimal:2',
            'available_from' => 'datetime',
            'available_to' => 'datetime',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function venueBookings(): HasMany
    {
        return $this->hasMany(VenueBooking::class);
    }

    public function scopeWithCurrentBookings(Builder $query): Builder
    {
        return $query->with([
            'venueBookings' => function ($q) {
                $q->whereIn('status', ['PENDING', 'APPROVED'])
                    ->where('booking_start', '<=', now())
                    ->where('booking_end', '>=', now())
                    ->orderByRaw("case when status = 'APPROVED' then 0 else 1 end")
                    ->orderBy('booking_end');
            },
        ]);
    }
}
