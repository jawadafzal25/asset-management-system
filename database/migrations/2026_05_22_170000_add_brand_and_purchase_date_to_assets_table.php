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
        if (Schema::hasTable('assets')) {
            Schema::table('assets', function (Blueprint $table) {
                if (!Schema::hasColumn('assets', 'brand')) {
                    $table->string('brand', 100)->nullable()->after('department_id');
                }
                if (!Schema::hasColumn('assets', 'purchase_date')) {
                    $table->date('purchase_date')->nullable()->after('brand');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('assets')) {
            Schema::table('assets', function (Blueprint $table) {
                if (Schema::hasColumn('assets', 'purchase_date')) {
                    $table->dropColumn('purchase_date');
                }
                if (Schema::hasColumn('assets', 'brand')) {
                    $table->dropColumn('brand');
                }
            });
        }
    }
};
