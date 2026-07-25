<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentProcessorService
{
    public function __construct(
        protected \Smalot\PdfParser\Parser $parser
    ) {}

    public function process(Document $document): void
    {
        $filePath = Storage::disk('local')->path($document->file_path);

        $pdf = $this->parser->parseFile($filePath);

        $text = trim($pdf->getText());

        $document->content()->create([
            'raw_text'   => $text,
            'page_count' => count($pdf->getPages()),
        ]);

        $document->update([
            'status' => 'ready',
        ]);
    }
}