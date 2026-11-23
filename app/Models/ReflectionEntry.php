<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReflectionEntry extends Model
{
    protected $fillable = [
        'user_id',
        'liked_activities',
        'liked_subjects',
        'interest_notes',
        'compared_options',
        'new_learnings',
        'follow_up_questions',
        'next_action',
        'shared_with_bk',
    ];

    protected function casts(): array
    {
        return [
            'liked_activities' => 'array',
            'liked_subjects' => 'array',
            'compared_options' => 'array',
            'shared_with_bk' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}