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
        Schema::create('knowledge_feedbacks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('workspace_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('question');

            $table->string('normalized_question')->nullable();

            $table->unsignedInteger('asked_count')->default(1);

            $table->enum('status', [
                'pending',
                'resolved',
            ])->default('pending');

            $table->timestamps();

            $table->index('workspace_id');
            $table->index('normalized_question');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_feedbacks');
    }
};