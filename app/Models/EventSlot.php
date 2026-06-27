<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventSlot extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'event_id',
        'slot_number',
        'slot_label',
        'date_start',
        'date_end',
        'slot_width',
        'slot_long',
        'price',
        'is_booked',
    ];

    protected function casts(): array
    {
        return [
            'slot_number' => 'integer',
            'date_start' => 'datetime',
            'date_end' => 'datetime',
            'slot_width' => 'float',
            'slot_long' => 'float',
            'price' => 'decimal:2',
            'is_booked' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function registrationSlots(): HasMany
    {
        return $this->hasMany(RegistrationSlot::class, 'slot_id');
    }
}
