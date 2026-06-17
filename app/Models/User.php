<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'password_hash',
        'role',
        'status',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }

    public function eventOrganizer(): HasOne
    {
        return $this->hasOne(EventOrganizer::class);
    }

    public function approvedTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'approved_by');
    }

    public function approvedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'approved_by');
    }

    public function approvedVenueBookings(): HasMany
    {
        return $this->hasMany(VenueBooking::class, 'approved_by');
    }

    public function approvedEventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class, 'approved_by');
    }

    public function participantRegistrations(): HasMany
    {
        return $this->hasMany(ParticipantRegistration::class);
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
        ];
    }
}
