<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assets')) {
            return;
        }

        // Add `asset_name` column and populate from `name` if present
        if (!Schema::hasColumn('assets', 'asset_name')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->string('asset_name', 150)->nullable()->after('id');
            });

            try {
                if (Schema::hasColumn('assets', 'name')) {
                    DB::table('assets')->whereNull('asset_name')->update(['asset_name' => DB::raw('name')]);
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Add `asset_code` column
        if (!Schema::hasColumn('assets', 'asset_code')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->string('asset_code', 50)->nullable()->unique()->after('asset_name');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('assets')) {
            return;
        }

        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'asset_code')) {
                try {
                    $table->dropUnique(['asset_code']);
                } catch (\Throwable $e) {
                    // ignore
                }
                $table->dropColumn('asset_code');
            }

            if (Schema::hasColumn('assets', 'asset_name')) {
                $table->dropColumn('asset_name');
            }
        });
    }
};
