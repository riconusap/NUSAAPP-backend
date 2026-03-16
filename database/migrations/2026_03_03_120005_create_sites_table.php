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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->string('site_name');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->float('radius_meters')->default(100);
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            // Indexes
            $table->index('client_id');
            $table->index('site_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
