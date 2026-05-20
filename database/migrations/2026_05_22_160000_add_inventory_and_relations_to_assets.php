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

        // Add total_quantity and remaining_quantity, migrate from `quantity` if present
        if (!Schema::hasColumn('assets', 'total_quantity')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->integer('total_quantity')->default(0)->after('asset_code');
                $table->integer('remaining_quantity')->default(0)->after('total_quantity');
            });

            try {
                if (Schema::hasColumn('assets', 'quantity')) {
                    DB::table('assets')->update(['total_quantity' => DB::raw('quantity'), 'remaining_quantity' => DB::raw('quantity')]);
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Add category_id
        if (!Schema::hasColumn('assets', 'category_id')) {
            if (Schema::hasTable('categories')) {
                Schema::table('assets', function (Blueprint $table) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('asset_code');
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('restrict');
                });
            } else {
                Schema::table('assets', function (Blueprint $table) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('asset_code');
                });
            }
        }

        // Add department_id
        if (!Schema::hasColumn('assets', 'department_id')) {
            if (Schema::hasTable('departments')) {
                Schema::table('assets', function (Blueprint $table) {
                    $table->unsignedBigInteger('department_id')->nullable()->after('category_id');
                    $table->foreign('department_id')->references('department_id')->on('departments')->onDelete('restrict');
                });
            } else {
                Schema::table('assets', function (Blueprint $table) {
                    $table->unsignedBigInteger('department_id')->nullable()->after('category_id');
                });
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('assets')) {
            return;
        }

        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'department_id')) {
                try { $table->dropForeign(['department_id']); } catch (\Throwable $e) {}
                $table->dropColumn('department_id');
            }
            if (Schema::hasColumn('assets', 'category_id')) {
                try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('assets', 'remaining_quantity')) {
                $table->dropColumn('remaining_quantity');
            }
            if (Schema::hasColumn('assets', 'total_quantity')) {
                $table->dropColumn('total_quantity');
            }
        });
    }
};
