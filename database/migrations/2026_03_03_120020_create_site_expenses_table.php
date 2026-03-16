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
        Schema::create('site_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id');
            $table->unsignedBigInteger('created_by');
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('site_id');
            $table->index('created_by');
            $table->index('category');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_expenses');
    }
};
