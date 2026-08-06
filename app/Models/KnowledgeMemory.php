<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KnowledgeMemory extends Model
{
    protected $fillable = [

        'workspace_id',

        'source_type',

        'source_id',

        'title',

        'knowledge',

        'page_number',

        'confidence',

        'status',

    ];

    protected $casts = [

        'confidence' => 'float',

    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(
            Workspace::class
        );
    }

    public function aliases(): HasMany
    {
        return $this->hasMany(
            KnowledgeMemoryAlias::class
        );
    }
}