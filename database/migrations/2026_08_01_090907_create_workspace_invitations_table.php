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
        Schema::create('workspace_invitations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('workspace_id')
                ->constrained()
                ->cascadeOnDelete();

            // Satu workspace hanya boleh memiliki satu invitation
            $table->unique('workspace_id');

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('token')
                ->unique();

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamp('expires_at')
                ->nullable();

            $table->timestamp('last_used_at')
                ->nullable();

            $table->unsignedInteger('usage_count')
                ->default(0);

            $table->timestamps();

            $table->index([
                'status',
                'expires_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_invitations');
    }
};