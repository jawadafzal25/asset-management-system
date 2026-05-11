<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique('categories_name_unique');
        });

        DB::statement(
            'CREATE UNIQUE INDEX categories_name_active_unique ON categories (name) WHERE deleted_at IS NULL'
        );
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS categories_name_active_unique');

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
