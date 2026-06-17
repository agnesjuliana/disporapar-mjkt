<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationAttendance extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'event_registration_id',
        'attendance_status',
        'checked_in_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    public function eventRegistration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }
}
