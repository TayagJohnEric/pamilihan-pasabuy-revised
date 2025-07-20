<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VendorPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VendorEarningController extends Controller
{
     /**
     * Display vendor earnings and payouts
     */
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        $payoutsQuery = VendorPayout::where('vendor_user_id', Auth::id())
            ->orderBy('payout_period_end_date', 'desc');

        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $payoutsQuery->where('status', $request->status_filter);
        }

        if ($request->filled('date_from')) {
            $payoutsQuery->where('payout_period_end_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $payoutsQuery->where('payout_period_start_date', '<=', $request->date_to);
        }

        $payouts = $payoutsQuery->paginate(10);

        $lastPayoutDate = VendorPayout::where('vendor_user_id', Auth::id())
            ->max('payout_period_end_date');

        $currentPeriodStart = $lastPayoutDate
            ? Carbon::parse($lastPayoutDate)->addDay()
            : Carbon::now()->startOfMonth();

        $currentPeriodSales = $this->calculateCurrentPeriodSales($vendor->id, $currentPeriodStart);

        $totalStats = VendorPayout::where('vendor_user_id', Auth::id())
            ->selectRaw('
                SUM(total_payout_amount) as total_earned,
                SUM(total_sales_amount) as total_sales,
                SUM(platform_commission_amount) as total_commission,
                COUNT(*) as total_payouts
            ')
            ->first();

        return view('vendor.earnings.index', compact(
            'payouts',
            'currentPeriodSales',
            'totalStats',
            'currentPeriodStart'
        ));
    }

    /**
     * Show detailed payout information
     */
    public function showPayout($id)
    {
        $payout = VendorPayout::where('vendor_user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $orders = Order::with(['orderItems.product', 'customer'])
            ->whereHas('orderItems.product', function ($query) {
                $query->where('vendor_id', Auth::user()->vendor->id);
            })
            ->where('status', 'delivered')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [
                $payout->payout_period_start_date,
                $payout->payout_period_end_date
            ])
            ->get();

        return view('vendor.earnings.show', compact('payout', 'orders'));
    }

    /**
     * Calculate current period sales
     */
    private function calculateCurrentPeriodSales($vendorId, $startDate)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->where('orders.status', 'delivered')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('
                COUNT(DISTINCT orders.id) as order_count,
                SUM(order_items.actual_item_price * order_items.quantity_requested) as total_sales,
                SUM(order_items.actual_item_price * order_items.quantity_requested * 0.10) as estimated_commission
            ')
            ->first();
    }
}
