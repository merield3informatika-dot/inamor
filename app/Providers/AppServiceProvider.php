<?php

namespace App\Providers;

use App\Contracts\RetrievalServiceInterface;
use App\Services\AIService;
use App\Services\ContextBuilder;
use App\Services\PromptBuilder;
use App\Services\Retrieval\KeywordRetrievalService;
use App\Services\Retrieval\QuestionNormalizer;
use App\Services\WorkspaceResolver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Swap this single binding to point at an EmbeddingRetrievalService
        // (or any other RetrievalServiceInterface implementation) later -
        // KnowledgeService and AIController never need to change.
        $this->app->bind(
            RetrievalServiceInterface::class,
            KeywordRetrievalService::class
        );

        $this->app->singleton(QuestionNormalizer::class);
        $this->app->singleton(WorkspaceResolver::class);
        $this->app->singleton(ContextBuilder::class);
        $this->app->singleton(PromptBuilder::class);
        $this->app->singleton(AIService::class);
        $this->app->singleton(KeywordRetrievalService::class);
    }

    public function boot(): void
    {
        //
    }
}
