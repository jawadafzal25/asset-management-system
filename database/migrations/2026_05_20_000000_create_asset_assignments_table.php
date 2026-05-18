<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_assignments', function (Blueprint $table) {
            
            $table->id('assignment_id'); 

            // Foreign Keys (bigint)
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees', 'employee_id')->onDelete('cascade');
            
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');

            // int column
            $table->integer('quantity')->default(1);

            $table->date('assign_date'); 
            $table->date('return_date')->nullable(); 

            // varchar column
            $table->string('status'); 

            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_assignments');
    }
};