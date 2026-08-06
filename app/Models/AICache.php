<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class AICache extends Model
{
    protected $table = 'ai_caches';
    protected $fillable = [
        'workspace_id',
        'question_hash',
        'normalized_question',
        'answer',
        'sources',
        'confidence',
        'provider',
        'model',
        'expires_at',
        'hit_count',
        'last_hit_at',
    ];

    protected $casts = [
        'sources' => 'array',
        'confidence' => 'float',
        'expires_at' => 'datetime',
        'last_hit_at' => 'datetime',
        'hit_count' => 'integer',
    ];
}