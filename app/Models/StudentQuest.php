<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'activities' => 'array',
        'favorite_subjects' => 'array',
        'read_profession_ids' => 'array',
        'selected_profession_ids' => 'array',
        'selected_education_paths' => 'array',
        'is_completed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}