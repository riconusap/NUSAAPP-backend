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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->foreignId('site_id')->nullable();
            $table->string('internal_contract_number')->unique();
            $table->string('position');
            $table->enum('salary_type', ['Monthly', 'Daily']);
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->decimal('daily_rate', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('contract_type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('set null');

            // Indexes
            $table->index('employee_id');
            $table->index('site_id');
            $table->index('internal_contract_number');
            $table->index('salary_type');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
    }
};
