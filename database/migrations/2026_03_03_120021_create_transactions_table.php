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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->enum('transaction_type', ['Income', 'Expense']);
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->string('reference_type')->nullable(); // Polymorphic
            $table->foreignId('reference_id')->nullable(); // Polymorphic
            $table->string('payment_method');
            $table->text('description');
            $table->string('receipt_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('transaction_date');
            $table->index('transaction_type');
            $table->index('category');
            $table->index('payment_method');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
