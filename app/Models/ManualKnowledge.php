<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualKnowledge extends Model
{
    protected $table = 'manual_knowledges';

    protected $fillable = [
        'workspace_id',
        'created_by',
        'title',
        'content',
        'status',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
        ]);
    }

    public function draft(): void
    {
        $this->update([
            'status' => 'draft',
        ]);
    }
    public function index(Request $request)
{
    $knowledges = $this->manualKnowledgeService
        ->getWorkspaceKnowledge($request->user());

    return view('knowledge.manual.index', [
        'knowledges' => $knowledges,
    ]);
}
}