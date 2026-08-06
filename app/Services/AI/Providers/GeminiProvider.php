<?php

namespace App\Services\AI\Providers;

use App\Exceptions\AIProviderException;
use Illuminate\Support\Facades\Http;

final class GeminiProvider implements AIProviderInterface
{
    private const RESPONSE_TEXT_PATH = 'candidates.0.content.parts.0.text';

    private readonly int $timeout;

    public function __construct()
    {
        $this->timeout = (int) config('knowledge.gemini.timeout', 60);
    }

    public function name(): string
    {
        return 'gemini';
    }

    public function model(): string
    {
        return (string) config('gemini.model');
    }

    public function ask(string $prompt): string
    {
        try {

            $response = Http::timeout($this->timeout)
                ->post(
                    $this->endpoint(),
                    $this->payload($prompt)
                );

        } catch (\Throwable $e) {

            throw new AIProviderException(
                'Gemini connection error: ' . $e->getMessage(),
                statusCode: null,
                isConnectionError: true,
                previous: $e
            );

        }

        if ($response->status() === 429) {

            throw new AIProviderException(
                'Gemini quota exceeded (429)',
                statusCode: 429
            );

        }

        if ($response->status() === 401) {

            throw new AIProviderException(
                'Gemini authentication error (401)',
                statusCode: 401
            );

        }

        if ($response->status() === 403) {

            throw new AIProviderException(
                'Gemini forbidden (403)',
                statusCode: 403
            );

        }

        if ($response->serverError()) {

            throw new AIProviderException(
                'Gemini server error (' . $response->status() . ')',
                statusCode: $response->status()
            );

        }

        if ($response->failed()) {

            throw new AIProviderException(
                'Gemini request failed (' . $response->status() . ')',
                statusCode: $response->status()
            );

        }

        $text = data_get(
            $response->json(),
            self::RESPONSE_TEXT_PATH
        );

        if (! is_string($text) || trim($text) === '') {

            throw new AIProviderException('Gemini returned invalid response');

        }

        return trim($text);
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
     * @return array<string,mixed>
     */
    private function payload(string $prompt): array
    {
        return [

            'contents' => [

                [

                    'parts' => [

                        [
                            'text' => $prompt,
                        ],

                    ],

                ],

            ],

        ];
    }
}