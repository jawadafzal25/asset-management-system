<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            if (DB::getSchemaBuilder()->getIndexes('categories')) {
                try {
                    $table->dropUnique('categories_name_unique');
                } catch (\Exception $e) {
                    // Index doesn't exist, skip
                }
            }
        });

        try {
            DB::statement(
                'CREATE UNIQUE INDEX categories_name_active_unique ON categories (name) WHERE deleted_at IS NULL'
            );
        } catch (\Exception $e) {
            // Index already exists, skip
        }
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS categories_name_active_unique');

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
