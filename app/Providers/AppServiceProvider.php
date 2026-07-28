<?php

namespace App\Providers;

use App\Contracts\RetrievalServiceInterface;
use App\Services\AI\AIService;
use App\Services\AI\PromptBuilder;
use App\Services\Knowledge\ContextBuilder;
use App\Services\Retrieval\KeywordRetrievalService;
use App\Services\Retrieval\QuestionNormalizer;
use App\Services\Workspace\WorkspaceResolver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

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