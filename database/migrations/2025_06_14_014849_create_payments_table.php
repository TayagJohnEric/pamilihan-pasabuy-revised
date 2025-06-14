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
        Schema::create('payments', function (Blueprint $table) {
           $table->id();
            $table->foreignId('order_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 8, 2);
            $table->enum('payment_method_used', ['online_payment', 'cod']);
            $table->enum('status', ['completed', 'failed', 'refunded'])->default('completed');
            $table->string('gateway_transaction_id')->nullable();
            $table->json('payment_gateway_response')->nullable();
            $table->timestamp('payment_processed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
