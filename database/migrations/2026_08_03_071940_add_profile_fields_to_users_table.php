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
        Schema::table('users', function (Blueprint $table) {

            $table->string('username')
                ->nullable()
                ->unique()
                ->after('name');

            $table->string('avatar')
                ->nullable()
                ->after('password');

            $table->text('bio')
                ->nullable()
                ->after('avatar');

            $table->string('phone')
                ->nullable()
                ->after('bio');

            $table->string('job_title')
                ->nullable()
                ->after('phone');

            $table->string('department')
                ->nullable()
                ->after('job_title');

            $table->string('location')
                ->nullable()
                ->after('department');

            $table->timestamp('last_seen_at')
                ->nullable()
                ->after('location');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'username',
                'avatar',
                'bio',
                'phone',
                'job_title',
                'department',
                'location',
                'last_seen_at',
            ]);

        });
    }
};