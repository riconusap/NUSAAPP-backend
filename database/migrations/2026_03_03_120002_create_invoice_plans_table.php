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
        Schema::create('invoice_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_contract_id');
            $table->enum('invoice_schedule', ['Monthly', 'Quarterly', 'Yearly', 'One-time']);
            $table->decimal('amount_per_invoice', 15, 2);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('client_contract_id')->references('id')->on('client_contracts')->onDelete('cascade');

            // Indexes
            $table->index('client_contract_id');
            $table->index('invoice_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_plans');
    }
};
