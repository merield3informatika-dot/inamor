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
        $this->timeout = (int) config(
            'knowledge.gemini.timeout',
            60
        );
    }

    /**
     * Chat AI.
     */
    public function ask(string $prompt): string
    {
        return $this->request($prompt);
    }

    /**
     * Knowledge Extraction AI.
     */
    public function extractKnowledge(string $prompt): string
    {
        return $this->request($prompt);
    }

    /**
     * Send request to Gemini.
     */
   private function request(string $prompt): string
{
    try {

        $response = Http::timeout($this->timeout)
            ->post(
                $this->endpoint(),
                $this->payload($prompt)
            );

    } catch (\Throwable $e) {

        return '⚠️ Maaf, saya sedang tidak dapat terhubung ke AI. Silakan coba beberapa saat lagi.';

    }

    if ($response->status() === 429) {

        return '🤒 Maaf, saya sedang kelelahan karena terlalu banyak permintaan. Coba lagi beberapa saat ya.';

    }

    if ($response->status() === 401) {

        return '🔑 AI sedang mengalami masalah autentikasi. Hubungi administrator.';

    }

    if ($response->status() === 403) {

        return '🚫 AI tidak memiliki izin untuk menggunakan model ini.';

    }

    if ($response->serverError()) {

        return '⚠️ AI sedang mengalami gangguan dari penyedia layanan. Silakan coba lagi nanti.';

    }

    if ($response->failed()) {

        return '😵 Terjadi kesalahan saat memproses permintaan AI.';

    }

    $text = data_get(
        $response->json(),
        self::RESPONSE_TEXT_PATH
    );

    if (! is_string($text) || trim($text) === '') {

        return self::FALLBACK_RESPONSE;

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