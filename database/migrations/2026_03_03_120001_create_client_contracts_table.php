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
        Schema::create('client_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->string('contract_number')->unique();
            $table->enum('contract_type', ['Monthly_Retainer', 'Project_Based']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('total_contract_value', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            // Indexes
            $table->index('client_id');
            $table->index('contract_number');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_contracts');
    }
};
