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
        Schema::create('site_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id');
            $table->foreignId('inventory_item_id');
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();

            // Foreign keys
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            $table->foreign('inventory_item_id')->references('id')->on('inventory_items')->onDelete('cascade');

            // Indexes
            $table->index('site_id');
            $table->index('inventory_item_id');
            $table->unique(['site_id', 'inventory_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_inventories');
    }
};
