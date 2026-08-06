<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeMemoryAlias extends Model
{
    protected $fillable = [

        'knowledge_memory_id',

        'alias',

    ];

    public function memory(): BelongsTo
    {
        return $this->belongsTo(
            KnowledgeMemory::class,
            'knowledge_memory_id'
        );
    }
}