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
        Schema::create('plant_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id');
            $table->foreignId('plant_master_id');
            $table->integer('quantity')->default(0);
            $table->string('health_status')->nullable();
            $table->date('last_inspected')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('plant_master_id')->references('id')->on('plant_masters')->onDelete('cascade');

            // Indexes
            $table->index('area_id');
            $table->index('plant_master_id');
            $table->index('health_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_inventories');
    }
};
