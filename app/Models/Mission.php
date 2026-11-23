<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mission extends Model
{
    protected $fillable = [
        'step_order',
        'title',
        'instruction',
        'mission_type',
    ];

    public function progresses(): HasMany
    {
        return $this->hasMany(MissionProgress::class);
    }
}