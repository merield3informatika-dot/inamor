<?php

namespace App\Services\AI\Providers;

use App\Exceptions\AIProviderException;
use Illuminate\Support\Facades\Http;

final class GroqProvider implements AIProviderInterface
{
    private readonly int $timeout;

    public function __construct()
    {
        $this->timeout = (int) config('knowledge.groq.timeout', 60);
    }

    public function name(): string
    {
        return 'groq';
    }

    public function model(): string
    {
        return (string) config('knowledge.groq.model');
    }

    public function ask(string $prompt): string
    {
        try {

            $response = Http::timeout($this->timeout)
                ->withToken((string) config('knowledge.groq.api_key'))
                ->post(
                    $this->endpoint(),
                    $this->payload($prompt)
                );

        } catch (\Throwable $e) {

            throw new AIProviderException(
                'Groq connection error: ' . $e->getMessage(),
                statusCode: null,
                isConnectionError: true,
                previous: $e
            );

        }

        if ($response->status() === 429) {

            throw new AIProviderException(
                'Groq quota exceeded (429)',
                statusCode: 429
            );

        }

        if ($response->status() === 401) {

            throw new AIProviderException(
                'Groq authentication error (401)',
                statusCode: 401
            );

        }

        if ($response->status() === 403) {

            throw new AIProviderException(
                'Groq forbidden (403)',
                statusCode: 403
            );

        }

        if ($response->serverError()) {

            throw new AIProviderException(
                'Groq server error (' . $response->status() . ')',
                statusCode: $response->status()
            );

        }

        if ($response->failed()) {

            throw new AIProviderException(
                'Groq request failed (' . $response->status() . ')',
                statusCode: $response->status()
            );

        }

        $text = data_get(
            $response->json(),
            'choices.0.message.content'
        );

        if (! is_string($text) || trim($text) === '') {

            throw new AIProviderException('Groq returned invalid response');

        }

        return trim($text);
    }

    private function endpoint(): string
    {
        return 'https://api.groq.com/openai/v1/chat/completions';
    }

    /**
     * @return array<string,mixed>
     */
    private function payload(string $prompt): array
    {
        return [

            'model' => $this->model(),

            'messages' => [

                [
                    'role' => 'user',
                    'content' => $prompt,
                ],

            ],

        ];
    }
}