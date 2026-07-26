<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use RuntimeException;

final class AIService
{
    private const FALLBACK_RESPONSE = 'Tidak ada respon dari AI.';
    private const RESPONSE_TEXT_PATH = 'candidates.0.content.parts.0.text';

    private readonly int $timeout;

    public function __construct()
    {
        $this->timeout = (int) config('knowledge.gemini.timeout', 60);
    }

public function ask(string $prompt): string
{
    $response = Http::timeout($this->timeout)
        ->post($this->endpoint(), $this->payload($prompt));

    if ($response->failed()) {
        throw new RuntimeException($response->body());
    }

    return data_get(
        $response->json(),
        self::RESPONSE_TEXT_PATH,
        self::FALLBACK_RESPONSE
    );
}

    private function endpoint(): string
    {
        return sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            config('gemini.model'),
            config('gemini.api_key')
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(string $prompt): array
    {
        return [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
        ];
    }
}
