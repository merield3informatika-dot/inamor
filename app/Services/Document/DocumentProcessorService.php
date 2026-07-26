<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Repositories\DocumentRepository;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Throwable;

final class DocumentProcessorService
{
    public function __construct(
        private readonly Parser $parser,
        private readonly DocumentRepository $documentRepository,
    ) {}

    public function process(Document $document): void
    {
        try {
            $pdf = $this->parser->parseFile(
                Storage::disk('local')->path($document->file_path)
            );

            $this->documentRepository->createContent($document, [
                'raw_text'   => trim($pdf->getText()),
                'page_count' => count($pdf->getPages()),
            ]);

            $this->documentRepository->markAsReady($document);
        } catch (Throwable $e) {
            $this->documentRepository->markAsFailed($document);

            throw $e;
        }
    }
}
