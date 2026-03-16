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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id');
            $table->foreignId('assigned_to_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('task_type', ['Daily', 'Weekly', 'Monthly', 'Yearly', 'Accidental']);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['To Do', 'In Progress', 'Review', 'Completed'])->default('To Do');
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('assigned_to_id')->references('id')->on('employees')->onDelete('set null');

            // Indexes
            $table->index('area_id');
            $table->index('assigned_to_id');
            $table->index('task_type');
            $table->index('priority');
            $table->index('status');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
