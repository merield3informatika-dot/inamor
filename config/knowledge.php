<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Retrieval Settings
    |--------------------------------------------------------------------------
    |
    | Controls how KeywordRetrievalService (and any future
    | EmbeddingRetrievalService) scores and limits results.
    |
    */
    'retrieval' => [
        'result_limit' => (int) env('KNOWLEDGE_RESULT_LIMIT', 3),

        // Minimum score agar dokumen dianggap relevan
        'minimum_score' => (int) env('KNOWLEDGE_MINIMUM_SCORE', 4),

        'min_keyword_length' => (int) env('KNOWLEDGE_MIN_KEYWORD_LENGTH', 2),

        'max_heading_length' => (int) env('KNOWLEDGE_MAX_HEADING_LENGTH', 80),

        'weights' => [
            'title'   => (int) env('KNOWLEDGE_WEIGHT_TITLE', 5),
            'heading' => (int) env('KNOWLEDGE_WEIGHT_HEADING', 3),
            'body'    => (int) env('KNOWLEDGE_WEIGHT_BODY', 1),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Context Settings
    |--------------------------------------------------------------------------
    */
    'context' => [
        'max_length' => (int) env('KNOWLEDGE_MAX_CONTEXT_LENGTH', 12000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Prompt Settings
    |--------------------------------------------------------------------------
    */
    'prompt' => [
        'system_role'     => env('KNOWLEDGE_SYSTEM_ROLE', 'You are an AI Knowledge Assistant.'),
        'fallback_answer' => env('KNOWLEDGE_FALLBACK_ANSWER', 'Informasi tidak ditemukan pada dokumen.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gemini Call Settings
    |--------------------------------------------------------------------------
    */
    'gemini' => [
        'timeout' => (int) env('KNOWLEDGE_GEMINI_TIMEOUT', 60),
    ],

];