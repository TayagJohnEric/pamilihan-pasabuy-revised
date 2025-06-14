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
        Schema::create('vendor_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_user_id')->constrained('users')->onDelete('restrict');
            $table->date('payout_period_start_date');
            $table->date('payout_period_end_date');
            $table->decimal('total_sales_amount', 8, 2);
            $table->decimal('platform_commission_amount', 8, 2);
            $table->decimal('adjustments_amount', 8, 2)->default(0);
            $table->text('adjustments_notes')->nullable();
            $table->decimal('total_payout_amount', 8, 2);
            $table->enum('status', ['pending_calculation', 'pending_payment', 'paid', 'failed'])->default('pending_calculation');
            $table->timestamp('paid_at')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payouts');
    }
};
