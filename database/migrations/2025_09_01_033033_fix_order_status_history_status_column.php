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
        // Fix the status column to accept all required status values
        Schema::table('order_status_history', function (Blueprint $table) {
            // Drop the existing enum constraint if it exists
            DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status VARCHAR(50)");
        });
        
        // Add a check constraint to ensure only valid statuses are allowed
        DB::statement("ALTER TABLE order_status_history ADD CONSTRAINT chk_order_status_history_status CHECK (status IN ('pending', 'pending_payment', 'processing', 'awaiting_rider_assignment', 'out_for_delivery', 'delivered', 'cancelled', 'failed'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the check constraint
        DB::statement("ALTER TABLE order_status_history DROP CONSTRAINT chk_order_status_history_status");
        
        // Revert to enum
        Schema::table('order_status_history', function (Blueprint $table) {
            DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM('pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled')");
        });
    }
};
