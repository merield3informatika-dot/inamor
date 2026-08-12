<?php

namespace App\Providers;

use App\Contracts\RetrievalServiceInterface;
use App\Services\AI\AIRouterService;
use App\Services\AI\AIService;
use App\Services\AI\ProviderHealthService;
use App\Services\AI\Providers\GeminiProvider;
use App\Services\AI\Providers\GroqProvider;
use App\Services\AI\Providers\OpenRouterProvider;
use App\Services\AI\PromptBuilder;
use App\Services\Document\Extraction\CsvExtractor;
use App\Services\Document\Extraction\DocumentExtractor;
use App\Services\Document\Extraction\DocxExtractor;
use App\Services\Document\Extraction\OcrEngine;
use App\Services\Document\Extraction\OcrExtractor;
use App\Services\Document\Extraction\PdfExtractor;
use App\Services\Document\Extraction\PdfPageRasterizer;
use App\Services\Document\Extraction\PptxExtractor;
use App\Services\Document\Extraction\SpreadsheetExtractor;
use App\Services\Document\Extraction\TextExtractor;
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
        $this->app->singleton(GeminiProvider::class);
        $this->app->singleton(GroqProvider::class);
        $this->app->singleton(OpenRouterProvider::class);
        $this->app->singleton(ProviderHealthService::class);
        $this->app->singleton(AIRouterService::class);
        $this->app->singleton(KeywordRetrievalService::class);

        /*
        |--------------------------------------------------------------------------
        | Document Extraction Layer
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(PdfPageRasterizer::class);
        $this->app->singleton(OcrEngine::class);

        $this->app->singleton(PdfExtractor::class);
        $this->app->singleton(DocxExtractor::class);
        $this->app->singleton(SpreadsheetExtractor::class);
        $this->app->singleton(CsvExtractor::class);
        $this->app->singleton(PptxExtractor::class);
        $this->app->singleton(TextExtractor::class);
        $this->app->singleton(OcrExtractor::class);

        $this->app->singleton(DocumentExtractor::class, function ($app) {

            return new DocumentExtractor([
                $app->make(PdfExtractor::class),
                $app->make(DocxExtractor::class),
                $app->make(SpreadsheetExtractor::class),
                $app->make(CsvExtractor::class),
                $app->make(PptxExtractor::class),
                $app->make(TextExtractor::class),
                $app->make(OcrExtractor::class),
            ]);

        });

    }

    public function boot(): void
    {
        //
    }
}