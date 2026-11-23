<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissionProgress extends Model
{
    protected $table = 'mission_progresses';

    protected $fillable = [
        'user_id',
        'mission_id',
        'status',
        'response_data',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'response_data' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mission(): BelongsTo
    {
        return $this->belongsTo(Mission::class);
    }
}