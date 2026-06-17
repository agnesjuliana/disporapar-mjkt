<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationDocument extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'event_registration_id',
        'document_type',
        'file_url',
    ];

    public function eventRegistration(): BelongsTo
    {
        return $this->belongsTo(EventRegistration::class);
    }
}
