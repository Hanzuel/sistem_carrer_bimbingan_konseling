<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionCard extends Model
{
    protected $fillable = [
        'title',
        'duties',
        'work_environment',
        'general_skills',
        'education_path',
        'reference_links',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }
}