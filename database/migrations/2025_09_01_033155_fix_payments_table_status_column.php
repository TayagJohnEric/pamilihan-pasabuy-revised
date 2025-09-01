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
        // Fix the payments table status column to include 'pending' status
        Schema::table('payments', function (Blueprint $table) {
            // Drop the existing enum constraint
            DB::statement("ALTER TABLE payments MODIFY COLUMN status VARCHAR(20)");
        });
        
        // Add a check constraint to ensure only valid statuses are allowed
        DB::statement("ALTER TABLE payments ADD CONSTRAINT chk_payments_status CHECK (status IN ('pending', 'completed', 'failed', 'refunded'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the check constraint
        DB::statement("ALTER TABLE payments DROP CONSTRAINT chk_payments_status");
        
        // Revert to enum
        Schema::table('payments', function (Blueprint $table) {
            DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('completed', 'failed', 'refunded') DEFAULT 'completed'");
        });
    }
};
