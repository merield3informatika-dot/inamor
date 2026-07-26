<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function chat(
        string $question,
        string $context
    ): string {

        $prompt = <<<PROMPT
Kamu adalah AI Knowledge Assistant.

Jawab HANYA berdasarkan context yang diberikan.

Jika jawabannya tidak ada di context, balas:

"Informasi tidak ditemukan pada dokumen."

========================
CONTEXT
========================

{$context}

========================
QUESTION
========================

{$question}
PROMPT;

        $response = Http::timeout(60)
            ->post(
                'https://generativelanguage.googleapis.com/v1beta/models/' .
                config('gemini.model') .
                ':generateContent?key=' .
                config('gemini.api_key'),
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                ]
            );

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return data_get(
            $response->json(),
            'candidates.0.content.parts.0.text',
            'Tidak ada respon dari AI.'
        );
    }
}