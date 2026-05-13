<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
   public function up(): void
{
    Schema::create('employees', function (Blueprint $table) {
        // Primary Key (unique Employee ID)
        $table->id('employee_id');

        // Personal Information
        $table->string('name');
        $table->string('father_name')->nullable();
        $table->string('contact_info');
        $table->string('email')->unique();
        $table->text('address')->nullable();

        #Professional Information
        $table->string('designation');
        $table->date('joining_date');
        $table->decimal('salary', 15, 2)->default(0.00);

        // Employment Status
        $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');

        // Relationship: COMPULSORY Department Link
        $table->foreignId('department_id')
              ->comment('References departments.department_id - constraint added separately');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
