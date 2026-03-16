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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('full_name');
            $table->string('profile_picture_path')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->text('current_address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('employment_status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('nik');
            $table->index('full_name');
            $table->index('employment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
