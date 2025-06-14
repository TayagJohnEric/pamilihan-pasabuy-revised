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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->string('product_name_snapshot');
            $table->unsignedInteger('quantity_requested');
            $table->decimal('unit_price_snapshot', 10, 2);
            $table->decimal('customer_budget_requested', 10, 2)->nullable();
            $table->string('vendor_assigned_quantity_description')->nullable();
            $table->decimal('actual_item_price', 10, 2)->nullable();
            $table->boolean('is_substituted')->default(false);
            $table->foreignId('substituted_with_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->text('customerNotes_snapshot')->nullable();
            $table->text('vendor_fulfillment_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
