<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    protected $fillable = [
        'workspace_id',
        'uploaded_by',
        'title',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'status',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function content(): HasOne
    {
        return $this->hasOne(DocumentContent::class);
    }
}