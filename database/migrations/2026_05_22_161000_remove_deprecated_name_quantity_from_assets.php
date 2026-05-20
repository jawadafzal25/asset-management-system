<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assets')) {
            return;
        }

        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('assets', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('assets')) {
            return;
        }

        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            if (!Schema::hasColumn('assets', 'quantity')) {
                $table->integer('quantity')->nullable()->after('asset_name');
            }
        });
    }
};
