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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_plan_id')->nullable();
            $table->foreignId('client_contract_id');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'])->default('Draft');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('invoice_plan_id')->references('id')->on('invoice_plans')->onDelete('set null');
            $table->foreign('client_contract_id')->references('id')->on('client_contracts')->onDelete('cascade');

            // Indexes
            $table->index('invoice_plan_id');
            $table->index('client_contract_id');
            $table->index('invoice_number');
            $table->index('status');
            $table->index('invoice_date');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
