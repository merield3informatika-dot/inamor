<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Repositories\DocumentRepository;
use App\Services\KnowledgeMemory\KnowledgeExtractorService;
use App\Services\KnowledgeMemory\KnowledgeMemoryService;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Throwable;

final class DocumentProcessorService
{
    public function __construct(
        private readonly Parser $parser,
        private readonly DocumentRepository $documentRepository,
        private readonly KnowledgeExtractorService $knowledgeExtractor,
        private readonly KnowledgeMemoryService $knowledgeMemoryService,
    ) {
    }

    public function process(Document $document): void
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Parse PDF
            |--------------------------------------------------------------------------
            */

            $pdf = $this->parser->parseFile(
                Storage::disk('local')->path($document->file_path)
            );

            $rawText = trim($pdf->getText());

            /*
            |--------------------------------------------------------------------------
            | Save Document Content
            |--------------------------------------------------------------------------
            */

            $this->documentRepository->createContent($document, [
                'raw_text'   => $rawText,
                'page_count' => count($pdf->getPages()),
            ]);

            /*
            |--------------------------------------------------------------------------
            | AI Knowledge Extraction
            |--------------------------------------------------------------------------
            */

            $memories = $this->knowledgeExtractor->extract(
                title: $document->title,
                rawText: $rawText,
            );

            /*
            |--------------------------------------------------------------------------
            | Save Knowledge Memories
            |--------------------------------------------------------------------------
            */

            foreach ($memories as $memory) {

                $this->knowledgeMemoryService->create(

                    memory: [

                        'workspace_id' => $document->workspace_id,

                        'source_type' => 'document',

                        'source_id' => $document->id,

                        'title' => $memory->title,

                        'knowledge' => $memory->knowledge,

                        'page_number' => $memory->pageNumber,

                        'confidence' => $memory->confidence,

                        'status' => 'active',

                    ],

                    aliases: $memory->aliases,

                );

            }

            /*
            |--------------------------------------------------------------------------
            | Finish
            |--------------------------------------------------------------------------
            */

            $this->documentRepository->markAsReady($document);

        } catch (Throwable $e) {

            $this->documentRepository->markAsFailed($document);

            throw $e;

        }
    }
}