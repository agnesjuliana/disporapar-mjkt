<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventRegistration extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'event_id',
        'tenant_id',
        'registration_status',
        'registered_at',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'notes',
        'requested_slot_count',
        'requested_slot_ids',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'approved_at' => 'datetime',
            'requested_slot_count' => 'integer',
            'requested_slot_ids' => 'array',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(RegistrationSlot::class);
    }

    public function attendance(): HasOne
    {
        return $this->hasOne(RegistrationAttendance::class);
    }
}
