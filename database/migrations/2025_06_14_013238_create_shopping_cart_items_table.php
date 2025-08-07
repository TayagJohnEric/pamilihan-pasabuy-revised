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
        Schema::create('shopping_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('customer_budget', 10, 2)->nullable();
            $table->text('customer_notes')->nullable();
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate products per user
            $table->unique(['user_id', 'product_id']);
            
            // Add indexes for better query performance
            $table->index('user_id');
            $table->index('product_id');
            $table->index(['user_id', 'created_at']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_cart_items');
    }
};
