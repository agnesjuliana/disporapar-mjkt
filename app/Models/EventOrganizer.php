<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EventOrganizer extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'organization_name',
        'contact_person',
        'contact_phone',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function venueBookings(): HasMany
    {
        return $this->hasMany(VenueBooking::class, 'organizer_id');
    }

    public static function forUserOrCreate(User $user): self
    {
        return self::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'id' => (string) Str::uuid(),
                'organization_name' => $user->name,
                'contact_person' => $user->name,
                'contact_phone' => '-',
                'address' => '-',
            ],
        );
    }
}
