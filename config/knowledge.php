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

    /*
    |--------------------------------------------------------------------------
    | Groq Provider Settings
    |--------------------------------------------------------------------------
    */
    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model'   => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'timeout' => (int) env('KNOWLEDGE_GROQ_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | OpenRouter Provider Settings
    |--------------------------------------------------------------------------
    */
    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
        'model'   => env('OPENROUTER_MODEL', 'openrouter/auto'),
        'timeout' => (int) env('KNOWLEDGE_OPENROUTER_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Router Settings
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'order' => ['gemini', 'groq', 'openrouter'],
    ],
/*
|--------------------------------------------------------------------------
| Smart Router
|--------------------------------------------------------------------------
*/

'router' => [

    /*
    |--------------------------------------------------------------------------
    | Router Mode
    |--------------------------------------------------------------------------
    |
    | priority
    | latency
    | health
    |
    */

    'mode' => env(
        'KNOWLEDGE_ROUTER_MODE',
        'priority'
    ),

    /*
    |--------------------------------------------------------------------------
    | Provider Priority
    |--------------------------------------------------------------------------
    */

    'provider_order' => [

        'gemini',

        'groq',

        'openrouter',

    ],

    /*
    |--------------------------------------------------------------------------
    | Circuit Breaker Cooldown
    |--------------------------------------------------------------------------
    */

    'health_cooldown' => (int) env(
        'KNOWLEDGE_ROUTER_HEALTH_COOLDOWN',
        300
    ),

],
];