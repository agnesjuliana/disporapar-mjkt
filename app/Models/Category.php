<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'icon',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function interestedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_category_interests');
    }
}
