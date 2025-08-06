<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Notification;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorDashboardController extends Controller
{
   public function index()
{
    $vendor = Auth::user()->vendor;

    if (!$vendor) {
        return redirect()->route('vendor.setup')->with('error', 'Please complete your vendor profile setup.');
    }

    $averageRating  = $this->calculateAverageRating($vendor->id);
    $stats          = $this->getQuickStats($vendor, $averageRating);
    $urgentOrders   = $this->getUrgentOrders($vendor);
    $recentActivity = $this->getRecentActivity($vendor);

    return view('vendor.dashboard.index', compact('vendor', 'stats', 'urgentOrders', 'recentActivity', 'averageRating'));
}

     private function calculateAverageRating($vendorId)
{
    $average = Rating::where('rateable_type', Vendor::class)
                     ->where('rateable_id', $vendorId)
                     ->avg('rating_value');

    return $average ? round($average, 1) : 0;
}

    private function getQuickStats($vendor, $averageRating)
{
    $productIds = $vendor->products()->pluck('id');

    $pendingOrdersCount = OrderItem::whereIn('product_id', $productIds)
        ->whereHas('order', fn($query) => $query->where('status', 'pending'))
        ->count();

    $salesToday = OrderItem::whereIn('product_id', $productIds)
        ->whereHas('order', fn($query) => $query->where('status', 'delivered')
                                               ->whereDate('updated_at', Carbon::today()))
        ->sum('actual_item_price');

    $unsettledEarnings = OrderItem::whereIn('product_id', $productIds)
        ->whereHas('order', fn($query) => $query->where('status', 'delivered'))
        ->sum('actual_item_price');

    return [
        'pending_orders'      => $pendingOrdersCount,
        'sales_today'         => $salesToday ?? 0,
        'shop_rating'         => $averageRating,
        'unsettled_earnings'  => $unsettledEarnings ?? 0,
    ];
}

    private function getUrgentOrders($vendor)
    {
        $productIds = $vendor->products()->pluck('id');

        return Order::where('status', 'pending')
            ->whereHas('orderItems', function($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })
            ->with(['orderItems' => function($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            }])
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'created_at' => $order->created_at,
                    'time_elapsed' => $order->created_at->diffForHumans(),
                    'items_count' => $order->orderItems->count(),
                ];
            });
    }

    private function getRecentActivity($vendor)
    {
        $activities = collect();

        // Recent ratings
        $recentRatings = Rating::where('rateable_type', 'App\Models\Vendor')
            ->where('rateable_id', $vendor->id)
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($rating) {
                return [
                    'type' => 'rating',
                    'message' => "New {$rating->rating_value}-star rating on Order #{$rating->order_id}",
                    'created_at' => $rating->created_at,
                    'icon' => 'star',
                ];
            });

        // Recent notifications
        $recentNotifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($notification) {
                return [
                    'type' => 'notification',
                    'message' => $notification->title ?? $notification->message['message'] ?? 'New notification',
                    'created_at' => $notification->created_at,
                    'icon' => 'bell',
                ];
            });

        // Combine and sort by date
        $activities = $recentRatings->concat($recentNotifications)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return $activities;
    }

        //Toggel Swith for is_accepting_orders
    public function toggleAcceptingOrders(Request $request)
{
    $request->validate([
        'is_accepting_orders' => 'required|boolean'
    ]);

    $vendor = Vendor::where('user_id', Auth::id())->first();

    if (!$vendor) {
        return response()->json(['message' => 'Vendor not found.'], 404);
    }

    $vendor->is_accepting_orders = $request->is_accepting_orders;
    $vendor->save();

    return response()->json(['message' => 'Availability updated successfully.']);
}

}
