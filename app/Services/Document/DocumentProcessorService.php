<?php

namespace App\Services\Document;

use App\Exceptions\Document\ExtractionFailedException;
use App\Models\Document;
use App\Repositories\DocumentRepository;
use App\Services\Document\Extraction\DocumentExtractor;
use App\Services\KnowledgeMemory\KnowledgeExtractorService;
use App\Services\KnowledgeMemory\KnowledgeMemoryService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class DocumentProcessorService
{
    public function __construct(
        private readonly DocumentExtractor $documentExtractor,
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
            | Extract (routes to the correct extractor: pdf/docx/xlsx/csv/pptx/txt/ocr)
            |--------------------------------------------------------------------------
            */

            $extracted = $this->documentExtractor->extract(
                Storage::disk('local')->path($document->file_path),
                $document->mime_type,
                $document->file_name,
            );

            if (trim($extracted->text) === '') {

                throw new ExtractionFailedException(
                    'Tidak ada teks yang dapat diekstrak dari dokumen ini.'
                );

            }

            /*
            |--------------------------------------------------------------------------
            | Save Document Content
            |--------------------------------------------------------------------------
            */

            $this->documentRepository->createContent($document, [

                'raw_text' => $extracted->text,

                'page_count' => (int) ($extracted->metadata['page_count'] ?? 0),

                'extraction_method' => $extracted->extractionMethod,

                'ocr_used' => (bool) ($extracted->metadata['ocr_used'] ?? false),

                'confidence' => $extracted->confidence,

                'metadata' => $extracted->metadata,

            ]);

            /*
            |--------------------------------------------------------------------------
            | AI Knowledge Extraction (existing pipeline, untouched)
            |--------------------------------------------------------------------------
            */

            $memories = $this->knowledgeExtractor->extract(
                title: $document->title,
                rawText: $extracted->text,
            );

            /*
            |--------------------------------------------------------------------------
            | Save Knowledge Memories (existing pipeline, untouched)
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

            Log::error('Document processing failed', [
                'document_id' => $document->id,
                'workspace_id' => $document->workspace_id,
                'mime_type' => $document->mime_type,
                'exception_type' => get_class($e),
                'message' => $e->getMessage(),
                'stage' => 'extraction_or_knowledge',
            ]);

            $this->documentRepository->markAsFailed($document);

            throw $e;

        }
    }
}