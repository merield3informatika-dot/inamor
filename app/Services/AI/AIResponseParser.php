<?php

namespace App\Services\AI;

use App\DataTransferObjects\KnowledgeMemoryDTO;
use Illuminate\Support\Collection;
use RuntimeException;

final class AIResponseParser
{
    /**
     * Parse Gemini JSON response into KnowledgeMemoryDTO collection.
     *
     * @return Collection<int, KnowledgeMemoryDTO>
     */
    public function parseKnowledgeExtraction(
        string $response,
    ): Collection {

        $json = $this->cleanJson($response);

        $decoded = json_decode($json, true);

        if (
            json_last_error() !== JSON_ERROR_NONE ||
            ! is_array($decoded)
        ) {
            throw new RuntimeException(
                'Invalid AI JSON response.'
            );
        }

        return collect($decoded)
            ->map(function (array $item): KnowledgeMemoryDTO {

                return new KnowledgeMemoryDTO(

                    title: (string) ($item['title'] ?? ''),

                    knowledge: (string) ($item['knowledge'] ?? ''),

                    pageNumber: isset($item['page_number'])
                        ? (int) $item['page_number']
                        : null,

                    confidence: isset($item['confidence'])
                        ? (float) $item['confidence']
                        : null,

                    aliases: collect(
                        $item['aliases'] ?? []
                    )
                        ->filter(fn ($alias) => is_string($alias))
                        ->map(fn ($alias) => trim($alias))
                        ->unique()
                        ->values()
                        ->all(),
                );

            })
            ->filter(fn (KnowledgeMemoryDTO $dto) =>

                $dto->title !== '' &&
                $dto->knowledge !== ''

            )
            ->values();
    }

    /**
     * Remove markdown fences and extra text.
     */
    private function cleanJson(
        string $response,
    ): string {

        $response = trim($response);

        /*
        |--------------------------------------------------------------------------
        | Remove ```json
        |--------------------------------------------------------------------------
        */

        $response = preg_replace(
            '/^```json/i',
            '',
            $response
        );

        $response = preg_replace(
            '/^```/',
            '',
            $response
        );

        $response = preg_replace(
            '/```$/',
            '',
            $response
        );

        $response = trim($response);

        /*
        |--------------------------------------------------------------------------
        | Extract JSON Array
        |--------------------------------------------------------------------------
        */

        $start = strpos($response, '[');
        $end   = strrpos($response, ']');

        if (
            $start === false ||
            $end === false
        ) {
            throw new RuntimeException(
                'JSON array not found.'
            );
        }

        return substr(
            $response,
            $start,
            ($end - $start) + 1
        );
    }
}