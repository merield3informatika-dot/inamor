<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_contents', function (Blueprint $table) {

            $table->string('extraction_method')->nullable()->after('page_count');

            $table->boolean('ocr_used')->default(false)->after('extraction_method');

            $table->decimal('confidence', 5, 2)->nullable()->after('ocr_used');

            $table->json('metadata')->nullable()->after('confidence');

        });
    }

    public function down(): void
    {
        Schema::table('document_contents', function (Blueprint $table) {

            $table->dropColumn([
                'extraction_method',
                'ocr_used',
                'confidence',
                'metadata',
            ]);

        });
    }
};