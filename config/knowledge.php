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
    |
    | Bounds how much retrieved document text can be packed into a single
    | prompt, so a workspace with large documents never overflows Gemini.
    |
    */
    'context' => [
        'max_length' => (int) env('KNOWLEDGE_MAX_CONTEXT_LENGTH', 12000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Prompt Settings
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the assistant's system role and the exact
    | fallback answer used both inside the prompt and by KnowledgeService
    | when no documents are found at all.
    |
    */
    'prompt' => [
        'system_role'     => env('KNOWLEDGE_SYSTEM_ROLE', 'You are an AI Knowledge Assistant.'),
        'fallback_answer' => env('KNOWLEDGE_FALLBACK_ANSWER', 'Informasi tidak ditemukan pada dokumen.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gemini Call Settings
    |--------------------------------------------------------------------------
    |
    | Model and API key stay in config/gemini.php. This only controls the
    | HTTP behaviour of AIService itself.
    |
    */
    'gemini' => [
        'timeout' => (int) env('KNOWLEDGE_GEMINI_TIMEOUT', 60),
    ],

];
