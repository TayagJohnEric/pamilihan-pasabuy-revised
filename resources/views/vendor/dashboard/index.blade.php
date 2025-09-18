@extends('layout.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="max-w-[90rem] mx-auto space-y-6">
    
    <!-- Header Section with Shop Info and Order Acceptance Toggle -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $dashboardStats['vendor_info']['shop_name'] }}</h1>
                <p class="text-gray-600 mt-1">Welcome back, {{ $dashboardStats['vendor_info']['name'] }}!</p>
                <div class="flex items-center space-x-4 mt-2">
                    @if($dashboardStats['vendor_info']['stall_number'])
                        <span class="text-sm text-gray-500">Stall: {{ $dashboardStats['vendor_info']['stall_number'] }}</span>
                    @endif
                    @if($dashboardStats['vendor_info']['market_section'])
                        <span class="text-sm text-gray-500">{{ $dashboardStats['vendor_info']['market_section'] }}</span>
                    @endif
                    @if($dashboardStats['vendor_info']['business_hours'])
                        <span class="text-sm text-gray-500">Hours: {{ $dashboardStats['vendor_info']['business_hours'] }}</span>
                    @endif
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <!-- Order Acceptance Toggle -->
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-700">Accepting Orders:</span>
                    <button 
                        id="order-acceptance-toggle" 
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'bg-green-500' : 'bg-gray-300' }}"
                        data-accepting="{{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'true' : 'false' }}"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
                
                <!-- Status Badge -->
                <div class="flex items-center space-x-2">
                    <div class="h-3 w-3 rounded-full {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                    <span class="text-sm font-medium {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'Open' : 'Closed' }}
                    </span>
                </div>

                <!-- Verification Status -->
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($dashboardStats['vendor_info']['verification_status'] == 'verified') bg-green-100 text-green-800
                        @elseif($dashboardStats['vendor_info']['verification_status'] == 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($dashboardStats['vendor_info']['verification_status']) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Pending Orders Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $dashboardStats['today_stats']['pending_orders'] }}</p>
                </div>
            </div>
        </div>

        <!-- Sales Today Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-green-600 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sales Today</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($dashboardStats['today_stats']['sales_amount'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Shop Rating Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Shop Rating</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($dashboardStats['vendor_info']['rating'], 1) }}
                        <span class="text-sm text-gray-500">/5.0</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Unsettled Earnings Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unsettled Earnings</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($dashboardStats['financial_overview']['unsettled_earnings'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Today's Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Summary</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Orders</span>
                    <span class="font-medium text-gray-900">{{ $dashboardStats['today_stats']['orders_count'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Orders Completed</span>
                    <span class="font-medium text-gray-900">{{ $dashboardStats['today_stats']['completed_orders'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Items Sold</span>
                    <span class="font-medium text-gray-900">{{ $dashboardStats['today_stats']['items_sold'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Average Order Value</span>
                    <span class="font-medium text-gray-900">
                        ₱{{ $dashboardStats['today_stats']['orders_count'] > 0 ? number_format($dashboardStats['today_stats']['sales_amount'] / $dashboardStats['today_stats']['orders_count'], 2) : '0.00' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Weekly Overview -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">This Week</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Sales</span>
                    <span class="font-medium text-gray-900">₱{{ number_format($dashboardStats['weekly_stats']['sales_amount'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Orders Received</span>
                    <span class="font-medium text-gray-900">{{ $dashboardStats['weekly_stats']['orders_count'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Average Order Value</span>
                    <span class="font-medium text-gray-900">₱{{ number_format($dashboardStats['weekly_stats']['average_order_value'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Customer Rating</span>
                    <span class="font-medium text-gray-900">{{ number_format($dashboardStats['weekly_stats']['customer_rating'], 1) }}/5.0</span>
                </div>
            </div>
        </div>

        <!-- Monthly Overview -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">This Month</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Sales</span>
                    <span class="font-medium text-gray-900">₱{{ number_format($dashboardStats['monthly_stats']['sales_amount'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Orders</span>
                    <span class="font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['orders_count'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Unique Customers</span>
                    <span class="font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['unique_customers'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Fulfillment Rate</span>
                    <span class="font-medium text-gray-900">{{ $dashboardStats['monthly_stats']['fulfillment_rate'] }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Product & Inventory Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory Status</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $dashboardStats['product_stats']['total_products'] }}</div>
                <p class="text-sm text-gray-600">Total Products</p>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $dashboardStats['product_stats']['active_products'] }}</div>
                <p class="text-sm text-gray-600">Active Products</p>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ $dashboardStats['product_stats']['out_of_stock'] }}</div>
                <p class="text-sm text-gray-600">Out of Stock</p>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $dashboardStats['product_stats']['low_stock'] }}</div>
                <p class="text-sm text-gray-600">Low Stock</p>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('vendor.products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                Manage Inventory
            </a>
        </div>
    </div>

    <!-- Pending Orders and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pending Orders</h3>
                <a href="{{ route('vendor.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            
            @if($dashboardStats['pending_orders']->count() > 0)
                <div class="space-y-3">
                    @foreach($dashboardStats['pending_orders']->take(5) as $order)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-medium text-gray-900">Order #{{ $order['id'] }}</span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($order['status'] == 'processing') bg-yellow-100 text-yellow-800
                                            @elseif($order['status'] == 'awaiting_rider_assignment') bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order['status'])) }}
                                        </span>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($order['payment_status'] == 'paid') bg-green-100 text-green-800
                                            @elseif($order['payment_status'] == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order['payment_status']) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $order['customer_name'] }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-sm text-gray-500">{{ $order['items_count'] }} items</span>
                                        <span class="text-sm font-medium text-gray-900">₱{{ number_format($order['total_amount'], 2) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $order['time_ago'] }}</p>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('vendor.orders.show', $order['id']) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                        Process
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No pending orders</p>
                    <p class="text-xs text-gray-400">{{ $dashboardStats['vendor_info']['is_accepting_orders'] ? 'All caught up!' : 'Turn on order acceptance to receive orders' }}</p>
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
                <a href="{{ route('vendor.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View History</a>
            </div>
            
            @if($dashboardStats['recent_activity']->count() > 0)
                <div class="space-y-3">
                    @foreach($dashboardStats['recent_activity']->take(6) as $activity)
                        <div class="flex items-center space-x-3 py-2">
                            <div class="flex-shrink-0">
                                <div class="h-2 w-2 rounded-full 
                                    @if($activity['status'] == 'delivered') bg-green-400
                                    @elseif($activity['status'] == 'out_for_delivery') bg-blue-400
                                    @elseif($activity['status'] == 'processing') bg-yellow-400
                                    @elseif($activity['status'] == 'cancelled') bg-red-400
                                    @else bg-gray-400
                                    @endif">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        Order #{{ $activity['id'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                                <p class="text-sm text-gray-600">{{ $activity['customer_name'] }}</p>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($activity['status'] == 'delivered') bg-green-100 text-green-800
                                        @elseif($activity['status'] == 'out_for_delivery') bg-blue-100 text-blue-800
                                        @elseif($activity['status'] == 'processing') bg-yellow-100 text-yellow-800
                                        @elseif($activity['status'] == 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                                    </span>
                                    <span class="text-xs font-medium text-gray-900">{{ $activity['items_count'] }} items · ₱{{ number_format($activity['total_amount'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No recent activity</p>
                    <p class="text-xs text-gray-400">Your order history will appear here</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Top Products and Financial Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Top Products</h3>
                <a href="{{ route('vendor.products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Manage Products</a>
            </div>
            
            @if($dashboardStats['top_products']->count() > 0)
                <div class="space-y-3">
                    @foreach($dashboardStats['top_products'] as $product)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $product['name'] }}</p>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="text-xs text-gray-500">{{ $product['sales_count'] }} sold</span>
                                    <span class="text-xs text-gray-500">₱{{ number_format($product['price'], 2) }}</span>
                                    <span class="text-xs text-gray-500">{{ $product['stock'] }} in stock</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ $product['sales_count'] }}</div>
                                <div class="text-xs text-gray-500">orders</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No sales data yet</p>
                    <p class="text-xs text-gray-400">Top selling products will appear here</p>
                </div>
            @endif
        </div>

        <!-- Financial Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Financial Overview</h3>
                <a href="{{ route('vendor.earnings.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</a>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Lifetime Sales</span>
                    <span class="font-medium text-gray-900">₱{{ number_format($dashboardStats['financial_overview']['total_lifetime_sales'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pending Payout</span>
                    <span class="font-medium text-gray-900">₱{{ number_format($dashboardStats['financial_overview']['pending_payout_amount'], 2) }}</span>
                </div>
                @if($dashboardStats['financial_overview']['last_payout'])
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Last Payout</span>
                    <div class="text-right">
                        <div class="font-medium text-gray-900">₱{{ number_format($dashboardStats['financial_overview']['last_payout']['amount'], 2) }}</div>
                        <div class="text-xs text-gray-500">{{ $dashboardStats['financial_overview']['last_payout']['date'] }}</div>
                    </div>
                </div>
                @endif
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-900">Available for Withdrawal</span>
                        <span class="font-bold text-green-600">₱{{ number_format($dashboardStats['financial_overview']['unsettled_earnings'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    @if($dashboardStats['recent_notifications']->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Notifications</h3>
            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Mark All Read</button>
        </div>
        
        <div class="space-y-3">
            @foreach($dashboardStats['recent_notifications']->take(4) as $notification)
                <div class="flex items-start space-x-3 p-3 rounded-lg {{ $notification['read'] ? 'bg-gray-50' : 'bg-blue-50' }}">
                    <div class="flex-shrink-0 mt-1">
                        <div class="h-2 w-2 rounded-full {{ $notification['read'] ? 'bg-gray-400' : 'bg-blue-500' }}"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ $notification['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500">{{ $notification['time'] }}</p>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Order Acceptance Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('order-acceptance-toggle');
    const toggleSpan = toggleButton.querySelector('span');
    
    toggleButton.addEventListener('click', function() {
        const currentStatus = this.getAttribute('data-accepting') === 'true';
        
        // Disable button during request
        this.disabled = true;
        this.classList.add('opacity-50');
        
        // Send AJAX request
        fetch('{{ route("vendor.toggle-order-acceptance") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button appearance
                const newStatus = data.is_accepting_orders;
                this.setAttribute('data-accepting', newStatus);
                
                if (newStatus) {
                    this.classList.remove('bg-gray-300');
                    this.classList.add('bg-green-500');
                    toggleSpan.classList.remove('translate-x-1');
                    toggleSpan.classList.add('translate-x-6');
                } else {
                    this.classList.remove('bg-green-500');
                    this.classList.add('bg-gray-300');
                    toggleSpan.classList.remove('translate-x-6');
                    toggleSpan.classList.add('translate-x-1');
                }
                
                // Update status indicator
                const statusDot = document.querySelector('.h-3.w-3.rounded-full');
                const statusText = statusDot.nextElementSibling;
                
                if (newStatus) {
                    statusDot.classList.remove('bg-gray-400');
                    statusDot.classList.add('bg-green-400');
                    statusText.classList.remove('text-gray-600');
                    statusText.classList.add('text-green-600');
                    statusText.textContent = 'Open';
                } else {
                    statusDot.classList.remove('bg-green-400');
                    statusDot.classList.add('bg-gray-400');
                    statusText.classList.remove('text-green-600');
                    statusText.classList.add('text-gray-600');
                    statusText.textContent = 'Closed';
                }
                
                // Show success message
                showNotification(data.message, 'success');
            } else {
                showNotification('Failed to update order acceptance status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while updating status', 'error');
        })
        .finally(() => {
            // Re-enable button
            this.disabled = false;
            this.classList.remove('opacity-50');
        });
    });
    
    // Simple notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Auto-refresh pending orders count every 30 seconds
    setInterval(function() {
        if (document.querySelector('[data-accepting="true"]')) {
            // Only refresh if accepting orders
            fetch('{{ route("vendor.dashboard") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Update pending orders count (you could implement a more sophisticated update)
                console.log('Dashboard refreshed');
            })
            .catch(error => {
                console.log('Auto-refresh error:', error);
            });
        }
    }, 30000); // 30 seconds
});
</script>

@endsection