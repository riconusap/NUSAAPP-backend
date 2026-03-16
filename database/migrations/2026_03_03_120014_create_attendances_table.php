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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->foreignId('site_id');
            $table->date('date');
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->string('latitude_in')->nullable();
            $table->string('longitude_in')->nullable();
            $table->string('selfie_path_in')->nullable();
            $table->string('selfie_path_out')->nullable();
            $table->enum('status', ['Present', 'Late', 'Half-day'])->default('Present');
            $table->timestamps();

            // Foreign keys
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');

            // Indexes
            $table->index('employee_id');
            $table->index('site_id');
            $table->index('date');
            $table->index('status');
            $table->unique(['employee_id', 'site_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
