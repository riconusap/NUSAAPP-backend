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
        Schema::create('plant_masters', function (Blueprint $table) {
            $table->id();
            $table->string('local_name');
            $table->string('latin_name')->nullable();
            $table->string('image_path')->nullable();
            $table->string('category')->nullable();
            $table->text('care_instructions')->nullable();
            $table->string('watering_frequency')->nullable();
            $table->string('planting_medium')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('local_name');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_masters');
    }
};
