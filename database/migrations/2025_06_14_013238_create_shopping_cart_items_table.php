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

            // ✅ Composite unique constraint (supports budget + regular entries)
            $table->unique(
                ['user_id', 'product_id', 'customer_budget'],
                'shopping_cart_items_user_product_budget_unique'
            );

            // ✅ Helpful indexes
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
