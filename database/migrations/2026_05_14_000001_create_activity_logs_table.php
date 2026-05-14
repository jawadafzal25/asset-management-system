<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Who did it
            $table->unsignedBigInteger('user_id')->nullable();   // null = system/unauthenticated
            $table->string('user_name')->nullable();             // snapshot — user may be deleted later
            $table->string('user_email')->nullable();            // snapshot

            // What they did
            $table->string('action');           // login, logout, created, updated, deleted, failed
            $table->string('module');           // Auth, Employee, Asset, Department, Category, Permission
            $table->string('entity_type')->nullable();  // Employee, Asset, Department ...
            $table->unsignedBigInteger('entity_id')->nullable();   // the record's primary key

            // Change tracking
            $table->json('old_values')->nullable();   // values BEFORE update
            $table->json('new_values')->nullable();   // values AFTER update / created values

            // Request context
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable();

            // Status
            $table->string('status')->default('success');   // success, failed, error
            $table->text('error_message')->nullable();       // filled on failed/error

            $table->timestamps();

            // Indexes for fast filtering
            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index('entity_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
