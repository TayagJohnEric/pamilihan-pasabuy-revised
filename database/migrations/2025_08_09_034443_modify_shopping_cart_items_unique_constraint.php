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
        Schema::table('shopping_cart_items', function (Blueprint $table) {
            // Drop the existing unique constraint
            $table->dropUnique(['user_id', 'product_id']);
            
            // Add a new composite unique constraint that includes customer_budget
            // This allows the same product to be in cart twice - once as budget-based and once as regular price
            $table->unique(['user_id', 'product_id', 'customer_budget'], 'shopping_cart_items_user_product_budget_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shopping_cart_items', function (Blueprint $table) {
            // Drop the new constraint
            $table->dropUnique('shopping_cart_items_user_product_budget_unique');
            
            // Restore the original constraint
            $table->unique(['user_id', 'product_id']);
        });
    }
};
