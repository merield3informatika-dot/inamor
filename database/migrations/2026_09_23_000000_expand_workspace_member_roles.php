<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workspace_members', function (Blueprint $table): void {
            $table->enum('role', [
                'owner',
                'admin',
                'member',
                'viewer',
            ])->default('member')->change();
        });

        DB::table('workspace_members')
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('workspaces')
                    ->whereColumn(
                        'workspaces.id',
                        'workspace_members.workspace_id',
                    )
                    ->whereColumn(
                        'workspaces.owner_id',
                        'workspace_members.user_id',
                    );
            })
            ->update(['role' => 'owner']);
    }

    public function down(): void
    {
        DB::table('workspace_members')
            ->where('role', 'owner')
            ->update(['role' => 'admin']);

        DB::table('workspace_members')
            ->where('role', 'viewer')
            ->update(['role' => 'member']);

        Schema::table('workspace_members', function (Blueprint $table): void {
            $table->enum('role', [
                'admin',
                'member',
            ])->default('member')->change();
        });
    }
};
