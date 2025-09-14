<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\SystemSetting;
use App\Models\Payment;
use Carbon\Carbon;

echo "=== COMPREHENSIVE PAYOUT DEBUG SCRIPT ===\n\n";

// Get today's date range
$today = Carbon::today();
$endOfDay = Carbon::today()->endOfDay();

echo "Checking orders from: {$today->format('Y-m-d H:i:s')}\n";
echo "To: {$endOfDay->format('Y-m-d H:i:s')}\n\n";

// Check all orders today
$allOrders = Order::whereBetween('created_at', [$today, $endOfDay])->get();
echo "📋 Total orders created today: " . $allOrders->count() . "\n";

// Check recent orders (last 7 days) if no orders today
if ($allOrders->count() == 0) {
    $lastWeek = Carbon::today()->subDays(7);
    $recentOrders = Order::whereBetween('created_at', [$lastWeek, $endOfDay])->get();
    echo "📋 Orders in last 7 days: " . $recentOrders->count() . "\n";
    
    if ($recentOrders->count() > 0) {
        echo "\nRecent orders (using for debugging):\n";
        foreach ($recentOrders->take(3) as $order) {
            echo "- Order #{$order->id} (Created: {$order->created_at->format('Y-m-d')})\n";
        }
        $allOrders = $recentOrders; // Use recent orders for debugging
        $today = $lastWeek; // Adjust date range
    }
}

if ($allOrders->count() > 0) {
    echo "\n=== DETAILED ORDER ANALYSIS ===\n";
    foreach ($allOrders->take(5) as $order) {
        echo "\n--- Order #{$order->id} ---\n";
        echo "Status: '{$order->status}'\n";
        echo "Payment Method: '{$order->payment_method}'\n";
        echo "Payment Status: '{$order->payment_status}'\n";
        echo "Total: ₱{$order->total_amount}\n";
        echo "Delivery Fee: ₱{$order->delivery_fee}\n";
        echo "Created: {$order->created_at}\n";
        echo "Rider ID: {$order->rider_user_id}\n";
        
        // Check payment record
        $payment = Payment::where('order_id', $order->id)->first();
        echo "Has Payment Record: " . ($payment ? 'Yes' : 'No') . "\n";
        if ($payment) {
            echo "Payment Record ID: {$payment->id}\n";
            echo "Payment Record Status: '{$payment->status}'\n";
            echo "Payment Record Method: '{$payment->payment_method}'\n";
            echo "Payment Record Amount: ₱{$payment->amount}\n";
        }
        
        // Check rider
        echo "Has Rider: " . ($order->rider ? 'Yes' : 'No') . "\n";
        if ($order->rider) {
            echo "Rider: {$order->rider->first_name} {$order->rider->last_name}\n";
        }
        
        // Check eligibility step by step
        echo "\n--- ELIGIBILITY CHECK ---\n";
        echo "✓ Status = 'delivered'? " . ($order->status === 'delivered' ? 'YES' : "NO (is '{$order->status}')") . "\n";
        echo "✓ Payment Method = 'online_payment'? " . ($order->payment_method === 'online_payment' ? 'YES' : "NO (is '{$order->payment_method}')") . "\n";
        echo "✓ Payment Status = 'paid'? " . ($order->payment_status === 'paid' ? 'YES' : "NO (is '{$order->payment_status}')") . "\n";
        echo "✓ Has Payment Record? " . ($payment ? 'YES' : 'NO') . "\n";
        if ($payment) {
            echo "✓ Payment Record Status = 'completed'? " . ($payment->status === 'completed' ? 'YES' : "NO (is '{$payment->status}')") . "\n";
        }
        echo "✓ Has Rider? " . ($order->rider ? 'YES' : 'NO') . "\n";
        echo "✓ Delivery Fee ≥ ₱100? " . ($order->delivery_fee >= 100 ? 'YES' : "NO (is ₱{$order->delivery_fee})") . "\n";
        
        $isEligible = $order->status === 'delivered' && 
                     $order->payment_method === 'online_payment' && 
                     $order->payment_status === 'paid' && 
                     $payment && 
                     $payment->status === 'completed' && 
                     $order->rider && 
                     $order->delivery_fee >= 100;
        
        echo "\n🎯 OVERALL ELIGIBLE: " . ($isEligible ? 'YES' : 'NO') . "\n";
    }
}

// Check minimum payout amount
$minimumPayout = SystemSetting::where('setting_key', 'payout_minimum_amount')
    ->first()?->setting_value ?? 100.00;

echo "\n💰 Minimum payout amount: ₱{$minimumPayout}\n";

echo "\n=== QUICK FIXES ===\n";
echo "If no orders are eligible, try:\n";
echo "1. Change order status to 'delivered'\n";
echo "2. Change payment_method to 'online_payment'\n";
echo "3. Change payment_status to 'paid'\n";
echo "4. Create Payment record with status 'completed'\n";
echo "5. Assign a rider to the order\n";
echo "6. Set delivery_fee ≥ ₱{$minimumPayout}\n";

echo "\n=== END DEBUG ===\n";
