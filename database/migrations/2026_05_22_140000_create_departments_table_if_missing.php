<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('departments')) {
            return;
        }

        Schema::create('departments', function (Blueprint $table) {
            $table->id('department_id');
            $table->string('department_name')->unique();
            $table->timestamps();
        });

        // Insert a default department to allow quick testing
        try {
            DB::table('departments')->insert([
                'department_name' => 'General',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // ignore if insert fails
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
