<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'ai_requests';
    protected $fillable = [

        'workspace_id',

        'user_id',

        'provider',

        'model',

        'engine',

        'question',

        'status',

        'latency',

        'error_message',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected function casts(): array
    {
        return [

            'workspace_id' => 'integer',

            'user_id' => 'integer',

            'latency' => 'integer',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(
            Workspace::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isQuotaExceeded(): bool
    {
        return $this->status === 'quota';
    }

    public function isTimeout(): bool
    {
        return $this->status === 'timeout';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isKnowledgeMemory(): bool
    {
        return $this->engine === 'knowledge_memory';
    }

    public function isManualKnowledge(): bool
    {
        return $this->engine === 'manual_knowledge';
    }

    public function isGemini(): bool
    {
        return $this->engine === 'gemini';
    }
}