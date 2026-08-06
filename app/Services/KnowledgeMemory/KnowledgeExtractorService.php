<?php

namespace App\Services\KnowledgeMemory;

use App\DataTransferObjects\KnowledgeMemoryDTO;
use App\Services\AI\AIResponseParser;
use App\Services\AI\AIService;
use App\Services\AI\KnowledgeExtractionPrompt;
use Illuminate\Support\Collection;

final class KnowledgeExtractorService
{
    public function __construct(
        private readonly KnowledgeExtractionPrompt $prompt,
        private readonly AIService $aiService,
        private readonly AIResponseParser $parser,
    ) {
    }

    /**
     * Extract structured knowledge from a document.
     *
     * @return Collection<int, KnowledgeMemoryDTO>
     */
    public function extract(
        string $title,
        string $rawText,
    ): Collection {

        /*
        |--------------------------------------------------------------------------
        | Build AI Prompt
        |--------------------------------------------------------------------------
        */

        $prompt = $this->prompt->build(
            title: $title,
            content: $rawText,
        );

        /*
        |--------------------------------------------------------------------------
        | Send to Gemini
        |--------------------------------------------------------------------------
        */

       $response = $this->aiService->extractKnowledge($prompt);

        /*
        |--------------------------------------------------------------------------
        | Parse AI Response
        |--------------------------------------------------------------------------
        */

        return $this->parser->parseKnowledgeExtraction(
            $response
        );
    }
}