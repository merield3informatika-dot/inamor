<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentContent extends Model
{
    protected $fillable = [
        'document_id',
        'raw_text',
        'page_count',
        'extraction_method',
        'ocr_used',
        'confidence',
        'metadata',
    ];

    protected $casts = [
        'ocr_used' => 'boolean',
        'confidence' => 'float',
        'metadata' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}