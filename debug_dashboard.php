<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\PayoutCalculationService;
use App\Models\RiderPayout;
use App\Models\VendorPayout;

echo "=== Dashboard Debug ===\n";

try {
    // Test direct model queries
    echo "Direct Model Queries:\n";
    echo "- Total Rider Payouts: " . RiderPayout::count() . "\n";
    echo "- Total Vendor Payouts: " . VendorPayout::count() . "\n";
    echo "- Pending Rider Payouts: " . RiderPayout::where('status', 'pending_payment')->count() . "\n";
    echo "- Pending Vendor Payouts: " . VendorPayout::where('status', 'pending_payment')->count() . "\n";
    
    // Test PayoutCalculationService
    echo "\nPayoutCalculationService:\n";
    $service = new PayoutCalculationService();
    $summary = $service->getPayoutSummary();
    
    echo "Summary Data:\n";
    print_r($summary);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
