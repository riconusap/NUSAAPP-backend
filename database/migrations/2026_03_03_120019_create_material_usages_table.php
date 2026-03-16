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
        Schema::create('material_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_log_id');
            $table->foreignId('inventory_item_id');
            $table->integer('quantity_used')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('task_log_id')->references('id')->on('task_logs')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');

            // Indexes
            $table->index('task_log_id');
            $table->index('inventory_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_usages');
    }
};
