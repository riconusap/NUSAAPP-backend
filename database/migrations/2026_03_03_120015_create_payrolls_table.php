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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->integer('period_month'); // 1-12
            $table->integer('period_year'); // e.g., 2026
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->integer('total_days_worked')->nullable();
            $table->decimal('total_hours_worked', 8, 2)->nullable();
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('overtime_pay', 15, 2)->default(0);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->enum('status', ['Draft', 'Approved', 'Paid'])->default('Draft');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            // Indexes
            $table->index('employee_id');
            $table->index(['period_month', 'period_year']);
            $table->index('status');
            $table->unique(['employee_id', 'period_month', 'period_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
