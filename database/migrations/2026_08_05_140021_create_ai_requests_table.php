<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_requests', function (Blueprint $table) {

            $table->id();

            $table->foreignId('workspace_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | AI Provider
            |--------------------------------------------------------------------------
            */

            $table->string('provider', 50);
            // gemini
            // groq
            // openrouter
            // openai
            // claude
            // internal

            $table->string('model', 100);
            // gemini-2.5-flash
            // llama-3.3-70b
            // deepseek-v3
            // claude-sonnet-4

            /*
            |--------------------------------------------------------------------------
            | Engine
            |--------------------------------------------------------------------------
            */

            $table->string('engine', 50);
            // knowledge_memory
            // manual_knowledge
            // gemini
            // groq
            // openrouter
            // llm
            // fallback

            /*
            |--------------------------------------------------------------------------
            | Request
            |--------------------------------------------------------------------------
            */

            $table->text('question');

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string('status', 30);
            // success
            // failed
            // timeout
            // quota
            // not_found
            // cached
            // fallback

            /*
            |--------------------------------------------------------------------------
            | Performance
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('latency')
                ->nullable()
                ->comment('Milliseconds');

            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            $table->text('error_message')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('workspace_id');
            $table->index('user_id');
            $table->index('provider');
            $table->index('model');
            $table->index('engine');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_requests');
    }
};