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
        $tableName = 'order_status_history';
        
        // Check if the table exists first
        if (!Schema::hasTable($tableName)) {
            return; // Skip this migration if table doesn't exist yet
        }
        
        // First, let's check what status values currently exist
        $existingStatuses = DB::table($tableName)->distinct()->pluck('status')->toArray();
        
        // Convert any invalid statuses to valid ones
        foreach ($existingStatuses as $status) {
            if (!in_array($status, ['pending', 'pending_payment', 'processing', 'awaiting_rider_assignment', 'out_for_delivery', 'delivered', 'cancelled', 'failed'])) {
                // Map invalid statuses to valid ones
                $newStatus = $this->mapStatus($status);
                DB::table($tableName)->where('status', $status)->update(['status' => $newStatus]);
            }
        }
        
        // Now safely modify the column to VARCHAR first
        DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN status VARCHAR(50)");
        
        // Add a check constraint to ensure only valid statuses are allowed
        $constraintExists = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$tableName}' AND CONSTRAINT_NAME = 'chk_status'");
        
        if (empty($constraintExists)) {
            DB::statement("ALTER TABLE {$tableName} ADD CONSTRAINT chk_status CHECK (status IN ('pending', 'pending_payment', 'processing', 'awaiting_rider_assignment', 'out_for_delivery', 'delivered', 'cancelled', 'failed'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = 'order_status_history';
        
        // Check if the table exists first
        if (!Schema::hasTable($tableName)) {
            return; // Skip this migration if table doesn't exist yet
        }
        
        // Check if the check constraint exists before trying to drop it
        $constraintExists = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$tableName}' AND CONSTRAINT_NAME = 'chk_status'");
        
        if (!empty($constraintExists)) {
            // Remove the check constraint
            DB::statement("ALTER TABLE {$tableName} DROP CONSTRAINT chk_status");
        }
        
        // Convert any extended statuses back to basic ones
        $statusMapping = [
            'pending_payment' => 'pending',
            'awaiting_rider_assignment' => 'processing',
            'failed' => 'cancelled'
        ];
        
        foreach ($statusMapping as $oldStatus => $newStatus) {
            DB::table($tableName)->where('status', $oldStatus)->update(['status' => $newStatus]);
        }
        
        // Revert to the original enum
        DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN status ENUM('pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled')");
    }
    
    /**
     * Map invalid statuses to valid ones
     */
    private function mapStatus($status)
    {
        $mapping = [
            'pending_payment' => 'pending',
            'awaiting_rider_assignment' => 'processing',
            'failed' => 'cancelled',
            'shipped' => 'out_for_delivery',
            'completed' => 'delivered',
            'returned' => 'cancelled',
            'refunded' => 'cancelled'
        ];
        
        return $mapping[$status] ?? 'pending';
    }
};
