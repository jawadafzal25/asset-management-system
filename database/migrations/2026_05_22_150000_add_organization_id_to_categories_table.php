<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        if (!Schema::hasColumn('categories', 'organization_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedBigInteger('organization_id')->nullable()->after('status');
                $table->foreign('organization_id')
                      ->references('id')
                      ->on('organizations')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'organization_id')) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            }
        });
    }
};
