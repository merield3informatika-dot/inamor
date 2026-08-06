<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_caches', function (Blueprint $table) {

            $table->id();

            $table->foreignId('workspace_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('question_hash', 32);

            $table->text('normalized_question');

            $table->longText('answer');

            $table->json('sources')->nullable();

            $table->decimal('confidence', 5, 2)->nullable();

            $table->string('provider');

            $table->string('model');

            $table->timestamp('expires_at')->nullable();

            $table->unsignedInteger('hit_count')->default(0);

            $table->timestamp('last_hit_at')->nullable();

            $table->timestamps();

            $table->index('workspace_id');

            $table->index('question_hash');

            $table->index('expires_at');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_caches');
    }
};