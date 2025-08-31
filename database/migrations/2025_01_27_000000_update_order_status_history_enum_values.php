<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, we need to drop the enum constraint and recreate it with new values
        // This is a bit complex in MySQL, so we'll use raw SQL
        
        // Get the table name
        $tableName = 'order_status_history';
        
        // Drop the existing enum constraint by modifying the column
        DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN status VARCHAR(50)");
        
        // Add a check constraint to ensure only valid statuses are allowed
        DB::statement("ALTER TABLE {$tableName} ADD CONSTRAINT chk_status CHECK (status IN ('pending', 'pending_payment', 'processing', 'awaiting_rider_assignment', 'out_for_delivery', 'delivered', 'cancelled', 'failed'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = 'order_status_history';
        
        // Remove the check constraint
        DB::statement("ALTER TABLE {$tableName} DROP CONSTRAINT chk_status");
        
        // Revert to the original enum
        DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN status ENUM('pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled')");
    }
};
