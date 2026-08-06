<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE ai_requests
            MODIFY engine VARCHAR(50) NOT NULL
        ");

        DB::statement("
            ALTER TABLE ai_requests
            MODIFY status VARCHAR(30) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE ai_requests
            MODIFY engine ENUM(
                'knowledge_memory',
                'manual_knowledge',
                'gemini'
            ) NOT NULL
        ");

        DB::statement("
            ALTER TABLE ai_requests
            MODIFY status ENUM(
                'success',
                'quota',
                'timeout',
                'failed'
            ) NOT NULL
        ");
    }
};