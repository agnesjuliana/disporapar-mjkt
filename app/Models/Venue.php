<?php

namespace App\Models;

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
}
