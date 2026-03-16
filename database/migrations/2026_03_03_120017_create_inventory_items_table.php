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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->enum('category', ['Tool', 'Material', 'Fertilizer', 'Chemical']);
            $table->enum('unit', ['pcs', 'kg', 'liter', 'zak']);
            $table->boolean('is_consumable')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('item_name');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
