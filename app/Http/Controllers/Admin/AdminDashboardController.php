<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Rider;
use App\Models\RiderApplication;
use App\Models\VendorApplication;
use App\Models\Payment;
use App\Models\Category;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
 /**
     * Display the main admin dashboard with key metrics and overview data
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get current date ranges for comparisons
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Key Performance Indicators (KPIs)
        $kpis = [
            // User Statistics
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', $today)->count(),
            'new_users_this_week' => User::where('created_at', '>=', $thisWeek)->count(),
            'active_users' => User::where('is_active', true)->count(),
            
            // User Role Distribution
            'customers' => User::where('role', 'customer')->count(),
            'vendors' => User::where('role', 'vendor')->count(),
            'riders' => User::where('role', 'rider')->count(),
            'admins' => User::where('role', 'admin')->count(),
            
            // Order Statistics
            'total_orders' => Order::count(),
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            'orders_this_week' => Order::where('created_at', '>=', $thisWeek)->count(),
            'orders_this_month' => Order::where('created_at', '>=', $thisMonth)->count(),
            'orders_last_month' => Order::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count(),
            
            // Revenue Statistics
            'total_revenue' => Payment::where('status', 'completed')->sum('amount_paid'),
            'revenue_today' => Payment::where('status', 'completed')
                ->whereDate('created_at', $today)->sum('amount_paid'),
            'revenue_this_month' => Payment::where('status', 'completed')
                ->where('created_at', '>=', $thisMonth)->sum('amount_paid'),
            'revenue_last_month' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('amount_paid'),
            
            // Application Statistics
            'pending_vendor_applications' => VendorApplication::where('status', 'pending')->count(),
            'pending_rider_applications' => RiderApplication::where('status', 'pending')->count(),
            
            // Product Statistics
            'total_products' => Product::count(),
            'active_products' => Product::where('is_available', true)->count(),
            'out_of_stock_products' => Product::where('quantity_in_stock', 0)->count(),
            
            // Vendor and Rider Statistics
            'active_vendors' => Vendor::where('is_active', true)->count(),
            'accepting_order_vendors' => Vendor::where('is_accepting_orders', true)->count(),
            'available_riders' => Rider::where('is_available', true)->count(),
            'verified_riders' => Rider::where('verification_status', 'verified')->count(),
        ];

        // Calculate growth percentages for key metrics
        $growthMetrics = [
            'orders_growth' => $this->calculateGrowthPercentage($kpis['orders_this_month'], $kpis['orders_last_month']),
            'revenue_growth' => $this->calculateGrowthPercentage($kpis['revenue_this_month'], $kpis['revenue_last_month']),
            'users_growth' => $this->calculateGrowthPercentage(
                User::where('created_at', '>=', $thisMonth)->count(),
                User::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count()
            ),
        ];

        // Recent Orders (last 10 orders with related data)
        $recentOrders = Order::with(['customer:id,first_name,last_name,email', 'rider:id,first_name,last_name'])
            ->select('id', 'customer_user_id', 'rider_user_id', 'status', 'final_total_amount', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Order Status Distribution for pie chart
        $orderStatusStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Payment Method Distribution
        $paymentMethodStats = Payment::select('payment_method_used', DB::raw('count(*) as count'))
            ->groupBy('payment_method_used')
            ->get()
            ->pluck('count', 'payment_method_used');

        // Daily Orders Chart Data (last 30 days)
        $dailyOrders = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count'),
                DB::raw('sum(final_total_amount) as revenue')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Categories by Product Count
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        // System Health Indicators
        $systemHealth = [
            'total_districts' => District::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'failed_payments' => Payment::where('status', 'failed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.dashboard.index', compact(
            'kpis',
            'growthMetrics',
            'recentOrders',
            'orderStatusStats',
            'paymentMethodStats',
            'dailyOrders',
            'topCategories',
            'systemHealth'
        ));
    }

    /**
     * Get dashboard data via AJAX for real-time updates
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardData(Request $request)
    {
        $type = $request->get('type', 'overview');

        switch ($type) {
            case 'orders_chart':
                return $this->getOrdersChartData($request);
            case 'revenue_chart':
                return $this->getRevenueChartData($request);
            case 'recent_activities':
                return $this->getRecentActivities();
            default:
                return response()->json(['error' => 'Invalid data type'], 400);
        }
    }

    /**
     * Get orders chart data for specified period
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function getOrdersChartData(Request $request)
    {
        $period = $request->get('period', '7'); // Default to 7 days
        $days = (int) $period;

        $data = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }

    /**
     * Get revenue chart data for specified period
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function getRevenueChartData(Request $request)
    {
        $period = $request->get('period', '7');
        $days = (int) $period;

        $data = Payment::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('sum(amount_paid) as revenue')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }

    /**
     * Get recent activities for the activity feed
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    private function getRecentActivities()
    {
        $activities = [];

        // Recent orders
        $recentOrders = Order::with('customer:id,first_name,last_name')
            ->select('id', 'customer_user_id', 'status', 'final_total_amount', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentOrders as $order) {
            $activities[] = [
                'type' => 'order',
                'message' => "New order #{$order->id} from {$order->customer->first_name} {$order->customer->last_name}",
                'amount' => $order->final_total_amount,
                'time' => $order->created_at->diffForHumans(),
                'status' => $order->status
            ];
        }

        // Recent applications
        $recentApplications = VendorApplication::where('status', 'pending')
            ->select('id', 'applicant_first_name', 'applicant_last_name', 'proposed_vendor_name', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentApplications as $app) {
            $activities[] = [
                'type' => 'application',
                'message' => "New vendor application from {$app->applicant_first_name} {$app->applicant_last_name}",
                'vendor_name' => $app->proposed_vendor_name,
                'time' => $app->created_at->diffForHumans(),
                'status' => 'pending'
            ];
        }

        // Sort activities by time
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return response()->json(array_slice($activities, 0, 10));
    }

    /**
     * Calculate growth percentage between two values
     * 
     * @param float $current
     * @param float $previous
     * @return array
     */
    private function calculateGrowthPercentage($current, $previous)
    {
        if ($previous == 0) {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'direction' => $current > 0 ? 'up' : 'neutral'
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;
        
        return [
            'percentage' => round(abs($percentage), 1),
            'direction' => $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'neutral')
        ];
    }

    /**
     * Export dashboard data as CSV
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportData(Request $request)
    {
        $type = $request->get('export_type', 'orders');
        
        $filename = $type . '_export_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');

            switch ($type) {
                case 'orders':
                    fputcsv($file, ['Order ID', 'Customer', 'Status', 'Amount', 'Date']);
                    Order::with('customer')->chunk(1000, function($orders) use ($file) {
                        foreach ($orders as $order) {
                            fputcsv($file, [
                                $order->id,
                                $order->customer->first_name . ' ' . $order->customer->last_name,
                                $order->status,
                                $order->final_total_amount,
                                $order->created_at->format('Y-m-d H:i:s')
                            ]);
                        }
                    });
                    break;

                case 'users':
                    fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Registered']);
                    User::chunk(1000, function($users) use ($file) {
                        foreach ($users as $user) {
                            fputcsv($file, [
                                $user->id,
                                $user->first_name . ' ' . $user->last_name,
                                $user->email,
                                $user->role,
                                $user->is_active ? 'Active' : 'Inactive',
                                $user->created_at->format('Y-m-d H:i:s')
                            ]);
                        }
                    });
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
