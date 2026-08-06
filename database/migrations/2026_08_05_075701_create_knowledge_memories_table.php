<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('knowledge_memories', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Workspace
            |--------------------------------------------------------------------------
            */

            $table->foreignId('workspace_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Knowledge Source
            |--------------------------------------------------------------------------
            |
            | document
            | manual
            | calendar
            | announcement
            |
            */

            $table->string('source_type');

            $table->unsignedBigInteger('source_id');

            /*
            |--------------------------------------------------------------------------
            | Knowledge
            |--------------------------------------------------------------------------
            */

            $table->string('title');

            $table->longText('knowledge');

            $table->unsignedInteger('page_number')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | AI Metadata
            |--------------------------------------------------------------------------
            */

            $table->decimal('confidence', 5, 2)
                ->nullable();

            $table->enum('status', [
                'active',
                'outdated',
                'archived',
            ])->default('active');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index([
                'workspace_id',
                'source_type',
                'source_id',
            ]);

            $table->fullText([
                'title',
                'knowledge',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_memories');
    }
};