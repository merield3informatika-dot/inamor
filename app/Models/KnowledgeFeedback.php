<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeFeedback extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'knowledge_feedbacks';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'workspace_id',
        'question',
        'normalized_question',
        'asked_count',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'asked_count' => 'integer',
    ];

    /**
     * Get the workspace that owns this feedback.
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Determine whether the feedback is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Determine whether the feedback has been resolved.
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Increment the number of times this question has been asked.
     */
    public function incrementAskedCount(): void
    {
        $this->increment('asked_count');
    }

    /**
     * Mark this feedback as resolved.
     */
    public function markAsResolved(): void
    {
        $this->update([
            'status' => 'resolved',
        ]);
    }
}