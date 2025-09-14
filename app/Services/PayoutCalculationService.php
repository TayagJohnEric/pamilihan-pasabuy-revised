<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RiderPayout;
use App\Models\VendorPayout;
use App\Models\User;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutCalculationService
{
    /**
     * Calculate and generate payouts for a specific period
     * Only processes orders with online payments that are completed/delivered
     */
    public function calculatePayoutsForPeriod(Carbon $startDate, Carbon $endDate)
    {
        try {
            DB::beginTransaction();

            // Get completed orders with online payments in the period
            $completedOrders = Order::with(['rider', 'orderItems.product.vendor', 'payment'])
                ->where('status', 'delivered')
                ->where('payment_method', 'online')
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereHas('payment', function($query) {
                    $query->where('status', 'completed');
                })
                ->get();

            // Calculate rider payouts
            $riderPayouts = $this->calculateRiderPayouts($completedOrders, $startDate, $endDate);
            
            // Calculate vendor payouts
            $vendorPayouts = $this->calculateVendorPayouts($completedOrders, $startDate, $endDate);

            DB::commit();

            return [
                'success' => true,
                'rider_payouts' => $riderPayouts,
                'vendor_payouts' => $vendorPayouts,
                'orders_processed' => $completedOrders->count(),
                'period' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d')
                ]
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payout calculation failed: ' . $e->getMessage(), [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to calculate payouts: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate rider payouts from completed orders
     */
    private function calculateRiderPayouts($orders, Carbon $startDate, Carbon $endDate)
    {
        $riderEarnings = [];

        foreach ($orders as $order) {
            if (!$order->rider_user_id) continue;

            $riderId = $order->rider_user_id;
            
            if (!isset($riderEarnings[$riderId])) {
                $riderEarnings[$riderId] = [
                    'rider' => $order->rider,
                    'total_delivery_fees' => 0,
                    'delivery_count' => 0,
                    'orders' => []
                ];
            }

            $riderEarnings[$riderId]['total_delivery_fees'] += $order->delivery_fee;
            $riderEarnings[$riderId]['delivery_count']++;
            $riderEarnings[$riderId]['orders'][] = $order->id;
        }

        $createdPayouts = [];

        foreach ($riderEarnings as $riderId => $earnings) {
            // Calculate incentives based on delivery count
            $incentives = $this->calculateRiderIncentives($earnings['delivery_count']);
            
            $totalPayout = $earnings['total_delivery_fees'] + $incentives;

            // Check if payout already exists for this period
            $existingPayout = RiderPayout::where('rider_user_id', $riderId)
                ->where('payout_period_start_date', $startDate->format('Y-m-d'))
                ->where('payout_period_end_date', $endDate->format('Y-m-d'))
                ->first();

            // Get minimum payout amount from settings
            $minimumPayoutAmount = (float) SystemSetting::where('setting_key', 'payout_minimum_amount')
                ->first()?->setting_value ?? 100.00;

            if (!$existingPayout && $totalPayout >= $minimumPayoutAmount) {
                $payout = RiderPayout::create([
                    'rider_user_id' => $riderId,
                    'payout_period_start_date' => $startDate,
                    'payout_period_end_date' => $endDate,
                    'total_delivery_fees_earned' => $earnings['total_delivery_fees'],
                    'total_incentives_earned' => $incentives,
                    'total_payout_amount' => $totalPayout,
                    'status' => 'pending_payment'
                ]);

                $createdPayouts[] = $payout;
            }
        }

        return $createdPayouts;
    }

    /**
     * Calculate vendor payouts from completed orders
     */
    private function calculateVendorPayouts($orders, Carbon $startDate, Carbon $endDate)
    {
        $vendorEarnings = [];
        $platformCommissionRate = $this->getPlatformCommissionRate();

        foreach ($orders as $order) {
            foreach ($order->orderItems as $orderItem) {
                if (!$orderItem->product || !$orderItem->product->vendor) continue;

                $vendorId = $orderItem->product->vendor_user_id;
                
                if (!isset($vendorEarnings[$vendorId])) {
                    $vendorEarnings[$vendorId] = [
                        'vendor' => $orderItem->product->vendor,
                        'total_sales' => 0,
                        'order_items' => []
                    ];
                }

                // Use actual_item_price if available, otherwise use unit_price_snapshot * quantity
                $itemTotal = $orderItem->actual_item_price ?? 
                           ($orderItem->unit_price_snapshot * $orderItem->quantity_requested);

                // For budget-based items, use customer_budget_requested
                if ($orderItem->customer_budget_requested) {
                    $itemTotal = $orderItem->customer_budget_requested;
                }

                $vendorEarnings[$vendorId]['total_sales'] += $itemTotal;
                $vendorEarnings[$vendorId]['order_items'][] = $orderItem->id;
            }
        }

        $createdPayouts = [];

        foreach ($vendorEarnings as $vendorId => $earnings) {
            $totalSales = $earnings['total_sales'];
            $platformCommission = $totalSales * ($platformCommissionRate / 100);
            $vendorPayout = $totalSales - $platformCommission;

            // Get minimum payout amount from settings
            $minimumPayoutAmount = (float) SystemSetting::where('setting_key', 'payout_minimum_amount')
                ->first()?->setting_value ?? 100.00;

            // Check if payout already exists for this period
            $existingPayout = VendorPayout::where('vendor_user_id', $vendorId)
                ->where('payout_period_start_date', $startDate->format('Y-m-d'))
                ->where('payout_period_end_date', $endDate->format('Y-m-d'))
                ->first();

            if (!$existingPayout && $vendorPayout >= $minimumPayoutAmount) {
                $payout = VendorPayout::create([
                    'vendor_user_id' => $vendorId,
                    'payout_period_start_date' => $startDate,
                    'payout_period_end_date' => $endDate,
                    'total_sales_amount' => $totalSales,
                    'platform_commission_amount' => $platformCommission,
                    'total_payout_amount' => $vendorPayout,
                    'status' => 'pending_payment'
                ]);

                $createdPayouts[] = $payout;
            }
        }

        return $createdPayouts;
    }

    /**
     * Calculate rider incentives based on delivery count
     */
    private function calculateRiderIncentives($deliveryCount)
    {
        $bonusTarget = $this->getRiderBonusTarget();
        $bonusAmount = $this->getRiderBonusAmount();

        // Calculate how many bonus targets were achieved
        $bonusesEarned = intval($deliveryCount / $bonusTarget);
        
        return $bonusesEarned * $bonusAmount;
    }

    /**
     * Get platform commission rate from system settings
     */
    private function getPlatformCommissionRate()
    {
        $setting = SystemSetting::where('setting_key', 'platform_commission_rate')->first();
        return $setting ? floatval($setting->setting_value) : 0.10; // Default 10%
    }

    /**
     * Get rider bonus target from system settings
     */
    private function getRiderBonusTarget()
    {
        $setting = SystemSetting::where('setting_key', 'rider_bonus_deliveries_target')->first();
        return $setting ? intval($setting->setting_value) : 10; // Default 10 deliveries
    }

    /**
     * Get rider bonus amount from system settings
     */
    private function getRiderBonusAmount()
    {
        $setting = SystemSetting::where('setting_key', 'rider_bonus_amount')->first();
        return $setting ? floatval($setting->setting_value) : 150.00; // Default ₱150
    }

    /**
     * Generate payouts for the previous week
     */
    public function generateWeeklyPayouts()
    {
        $endDate = Carbon::now()->subWeek()->endOfWeek();
        $startDate = $endDate->copy()->startOfWeek();

        return $this->calculatePayoutsForPeriod($startDate, $endDate);
    }

    /**
     * Generate payouts for the previous month
     */
    public function generateMonthlyPayouts()
    {
        $endDate = Carbon::now()->subMonth()->endOfMonth();
        $startDate = $endDate->copy()->startOfMonth();

        return $this->calculatePayoutsForPeriod($startDate, $endDate);
    }

    /**
     * Get payout summary for admin dashboard
     */
    public function getPayoutSummary()
    {
        // Count pending payouts
        $pendingRiderCount = RiderPayout::where('status', 'pending_payment')->count();
        $pendingVendorCount = VendorPayout::where('status', 'pending_payment')->count();
        
        // Sum pending amounts
        $pendingRiderAmount = RiderPayout::where('status', 'pending_payment')->sum('total_payout_amount');
        $pendingVendorAmount = VendorPayout::where('status', 'pending_payment')->sum('total_payout_amount');
        $totalPendingAmount = $pendingRiderAmount + $pendingVendorAmount;
        
        // Count orders that could be processed into payouts (completed orders with online payments)
        $ordersToProcess = Order::where('status', 'delivered')
            ->whereHas('payment', function($query) {
                $query->where('payment_method', '!=', 'cash_on_delivery')
                      ->where('payment_status', 'completed');
            })
            ->whereDoesntHave('riderPayouts') // Orders not yet included in any rider payout
            ->count();

        return [
            'pending_rider_payouts' => $pendingRiderCount,
            'pending_vendor_payouts' => $pendingVendorCount,
            'total_pending_amount' => number_format($totalPendingAmount, 2),
            'orders_to_process' => $ordersToProcess
        ];
    }
}
