<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(WorkspaceConversation::class, 'conversation_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}