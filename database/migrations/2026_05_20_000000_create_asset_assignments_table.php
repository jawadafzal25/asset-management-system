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
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');

            // int column
            $table->integer('quantity')->default(1);

            // date columns (image mein data type 'date' hai, isliye $table->date use kiya)
            $table->date('assign_date'); 
            $table->date('return_date')->nullable(); 

            // varchar column
            $table->string('status'); 

            // Image mein sirf created_at hai, lekin Laravel mein $table->timestamps() use karna best practice hai
            // jo created_at aur updated_at dono bana deta hai.
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_assignments');
    }
};