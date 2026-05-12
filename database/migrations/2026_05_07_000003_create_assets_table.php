<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the assets table with all required fields, constraints,
     * foreign keys, and indexes for the Asset Management System.
     *
     * PostgreSQL-compatible migration.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Asset identification
            $table->string('asset_name', 150);
            $table->string('asset_code', 50)->unique()->comment('Unique identifier code for the asset');

            // Foreign keys
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->restrictOnDelete();

            $table->foreignId('department_id')
                  ->constrained('departments', 'department_id')
                  ->restrictOnDelete();

            // Asset details
            $table->string('brand', 100)->nullable();
            $table->date('purchase_date')->nullable();

            // Quantity management
            // remaining_quantity is enforced >= 0 at app level; DB check added via raw
            $table->unsignedInteger('total_quantity')->default(0);
            $table->unsignedInteger('remaining_quantity')->default(0);

            // Status: available | assigned | maintenance | inactive
            $table->string('status', 20)->default('available');

            // Timestamps & soft delete
            $table->timestamps();
            $table->softDeletes();

            // ---------------------
            // Indexes
            // ---------------------
            $table->index('asset_name');
            $table->index('category_id');
            $table->index('department_id');
            $table->index('status');
            $table->index('purchase_date');
        });

        // Add CHECK constraint if using PostgreSQL or MySQL/MariaDB (modern versions)
        $driver = \DB::getDriverName();
        if (in_array($driver, ['pgsql', 'mysql', 'mariadb'])) {
            \DB::statement('ALTER TABLE assets ADD CONSTRAINT chk_remaining_qty 
                CHECK (remaining_quantity <= total_quantity)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
