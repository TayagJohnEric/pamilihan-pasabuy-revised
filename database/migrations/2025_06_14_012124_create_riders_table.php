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
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->unique();
            $table->decimal('average_rating', 3, 2)->nullable(); // e.g. 4.75
            $table->unsignedInteger('total_deliveries')->default(0);
            $table->unsignedInteger('daily_deliveries')->default(0);
            $table->boolean('is_available')->default(false);
            $table->string('verification_status')->default('pending'); // pending, verified, rejected
            $table->string('license_number')->nullable();
            $table->string('vehicle_type')->nullable(); // bike, motorcycle, car, etc.
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('location_last_updated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};
