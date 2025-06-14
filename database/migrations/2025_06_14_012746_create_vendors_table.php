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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('vendor_name');
            $table->string('shop_logo_url')->nullable();
            $table->string('shop_banner_url')->nullable();
            $table->string('stall_number')->nullable();
            $table->string('market_section')->nullable();
            $table->string('business_hours')->nullable();
            $table->string('public_contact_number')->nullable();
            $table->string('public_email')->nullable();
            $table->text('description')->nullable();
            $table->string('verification_status')->default('pending');
            $table->string('permit_documents_path')->nullable();
            $table->boolean('accepts_cod')->default(false);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
