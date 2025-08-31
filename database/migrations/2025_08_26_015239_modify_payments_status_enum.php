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
        // First, update any existing data to ensure it matches the new enum values
        // Convert any invalid status values to 'completed' as a safe default
        DB::statement("UPDATE payments SET status = 'completed' WHERE status NOT IN ('completed', 'failed', 'refunded')");
        
        Schema::table('payments', function (Blueprint $table) {
            // Drop the existing enum constraint and recreate it with 'pending' status
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update any 'pending' status values to 'completed' before reverting
        DB::statement("UPDATE payments SET status = 'completed' WHERE status = 'pending'");
        
        Schema::table('payments', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('status', ['completed', 'failed', 'refunded'])->default('completed')->change();
        });
    }
};
