@extends('layout.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-[90rem] mx-auto space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-xl shadow-xl p-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">Dashboard Overview</h1>
                <p class="text-blue-100 text-lg">Monitor your marketplace performance at a glance</p>
                <div class="flex items-center space-x-4 mt-3">
                    <span class="bg-blue-500/30 px-3 py-1 rounded-full text-sm">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
                    <span class="bg-blue-500/30 px-3 py-1 rounded-full text-sm">{{ \Carbon\Carbon::now()->format('l') }}</span>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex flex-wrap gap-2">
                    <button onclick="refreshDashboard()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg backdrop-blur transition duration-200 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                    <button onclick="exportData('orders')" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg backdrop-blur transition duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users KPI -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($kpis['total_users']) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-green-600 text-sm font-semibold">+{{ $kpis['new_users_today'] }}</span>
                        <span class="text-gray-500 text-sm ml-2">today</span>
                    </div>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Orders KPI -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($kpis['total_orders']) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-{{ $growthMetrics['orders_growth']['direction'] === 'up' ? 'green' : ($growthMetrics['orders_growth']['direction'] === 'down' ? 'red' : 'gray') }}-600 text-sm font-semibold flex items-center">
                            <i class="fas fa-arrow-{{ $growthMetrics['orders_growth']['direction'] === 'up' ? 'up' : ($growthMetrics['orders_growth']['direction'] === 'down' ? 'down' : 'right') }} mr-1 text-xs"></i>
                            {{ $growthMetrics['orders_growth']['percentage'] }}%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue KPI -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">₱{{ number_format($kpis['total_revenue'], 2) }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-{{ $growthMetrics['revenue_growth']['direction'] === 'up' ? 'green' : ($growthMetrics['revenue_growth']['direction'] === 'down' ? 'red' : 'gray') }}-600 text-sm font-semibold flex items-center">
                            <i class="fas fa-arrow-{{ $growthMetrics['revenue_growth']['direction'] === 'up' ? 'up' : ($growthMetrics['revenue_growth']['direction'] === 'down' ? 'down' : 'right') }} mr-1 text-xs"></i>
                            {{ $growthMetrics['revenue_growth']['percentage'] }}%
                        </span>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <i class="fas fa-peso-sign text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Applications KPI -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Applications</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $kpis['pending_vendor_applications'] + $kpis['pending_rider_applications'] }}</p>
                    <div class="flex items-center mt-2 space-x-3">
                        <span class="text-blue-600 text-sm">{{ $kpis['pending_vendor_applications'] }} vendors</span>
                        <span class="text-purple-600 text-sm">{{ $kpis['pending_rider_applications'] }} riders</span>
                    </div>
                </div>
                <div class="bg-red-100 p-4 rounded-full">
                    <i class="fas fa-clipboard-check text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Orders Trend Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Daily Orders Trend</h3>
                    <p class="text-gray-600 text-sm">Track order volume over time</p>
                </div>
                <select id="ordersChartPeriod" onchange="updateOrdersChart(this.value)" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="7">Last 7 days</option>
                    <option value="14">Last 14 days</option>
                    <option value="30" selected>Last 30 days</option>
                </select>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Daily Revenue Trend Chart -->
        <div class="bg-white rounded-xl shadow   p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Daily Revenue Trend</h3>
                    <p class="text-gray-600 text-sm">Monitor revenue performance</p>
                </div>
                <select id="revenueChartPeriod" onchange="updateRevenueChart(this.value)" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="7">Last 7 days</option>
                    <option value="14">Last 14 days</option>
                    <option value="30" selected>Last 30 days</option>
                </select>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Distribution Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Order Status Distribution -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900">Order Status Distribution</h3>
                <p class="text-gray-600 text-sm">Current status breakdown of all orders</p>
            </div>
            <div style="height: 200px; position: relative;">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900">Payment Method Distribution</h3>
                <p class="text-gray-600 text-sm">How customers prefer to pay</p>
            </div>
            <div style="height: 200px; position: relative;">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Business Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Vendors -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Active Vendors</p>
                    <p class="text-2xl font-bold mt-1">{{ $kpis['active_vendors'] }}</p>
                    <p class="text-purple-200 text-xs mt-1">{{ $kpis['accepting_order_vendors'] }} accepting orders</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-store text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Available Riders -->
        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-sm font-medium">Available Riders</p>
                    <p class="text-2xl font-bold mt-1">{{ $kpis['available_riders'] }}</p>
                    <p class="text-cyan-200 text-xs mt-1">{{ $kpis['verified_riders'] }} verified</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-motorcycle text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Total Online Customers</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($kpis['customers']) }}</p>
                    <p class="text-emerald-200 text-xs mt-1">registered users</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-user-friends text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Products Overview -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium">Total Products</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($kpis['total_products']) }}</p>
                    <p class="text-indigo-200 text-xs mt-1">{{ $kpis['out_of_stock_products'] }} out of stock</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-box text-white text-xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Recent Orders</h3>
                    <p class="text-gray-600 text-sm">Latest customer orders and their status</p>
                </div>
                <a href="/admin/orders" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                    View All Orders
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rider</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $order->customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                @switch($order->status)
                                    @case('delivered')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('cancelled')
                                    @case('failed')
                                        bg-red-100 text-red-800
                                        @break
                                    @case('processing')
                                    @case('assigned')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('out_for_delivery')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">₱{{ number_format($order->final_total_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->rider)
                                <div class="text-sm text-gray-900">{{ $order->rider->first_name }} {{ $order->rider->last_name }}</div>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded">Not assigned</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Categories and Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Product Categories -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900">Top Product Categories</h3>
                <p class="text-gray-600 text-sm">Most popular product categories by count</p>
            </div>
            <div class="space-y-4">
                @foreach($topCategories as $index => $category)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $category->category_name }}</p>
                            <p class="text-xs text-gray-500">Category</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-blue-600">{{ $category->products_count }}</p>
                        <p class="text-xs text-gray-500">products</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Recent Activities</h3>
                    <p class="text-gray-600 text-sm">Latest system activities and updates</p>
                </div>
                <button onclick="loadRecentActivities()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
            </div>
            <div id="activities-list" class="space-y-4">
                <!-- Loading placeholder -->
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700 font-medium">Processing...</span>
    </div>
</div>

<!-- External Resources -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard components
    initializeDashboard();
    
    // Set up auto-refresh every 5 minutes
    setInterval(refreshDashboardData, 300000);
});

let ordersChart, revenueChart, orderStatusChart, paymentMethodChart;

/**
 * Initialize the complete dashboard
 */
function initializeDashboard() {
    try {
        initializeCharts();
        loadRecentActivities();
    } catch (error) {
        console.error('Dashboard initialization error:', error);
    }
}

/**
 * Initialize all charts with data
 */
function initializeCharts() {
    // Prepare data from Laravel
    const ordersData = @json($dailyOrders);
    const orderStatusData = @json($orderStatusStats);
    const paymentMethodData = @json($paymentMethodStats);

    // Initialize each chart
    createOrdersChart(ordersData);
    createRevenueChart(ordersData);
    createOrderStatusChart(orderStatusData);
    createPaymentMethodChart(paymentMethodData);
}

/**
 * Create the daily orders line chart
 */
function createOrdersChart(data) {
    const ctx = document.getElementById('ordersChart').getContext('2d');
    
    ordersChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Orders',
                data: data.map(item => item.count),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                },
                x: {
                    ticks: {
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                }
            }
        }
    });
}

/**
 * Create the daily revenue line chart
 */
function createRevenueChart(data) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Revenue (₱)',
                data: data.map(item => parseFloat(item.revenue) || 0),
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(16, 185, 129)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: ₱' + context.parsed.y.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        },
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                },
                x: {
                    ticks: {
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#F3F4F6'
                    }
                }
            }
        }
    });
}

/**
 * Create the order status doughnut chart
 */
function createOrderStatusChart(data) {
    const ctx = document.getElementById('orderStatusChart').getContext('2d');
    
    const colors = {
        'pending': '#6B7280',
        'pending_payment': '#F59E0B',
        'processing': '#3B82F6',
        'awaiting_rider_assignment': '#8B5CF6',
        'assigned': '#06B6D4',
        'pickup_confirmed': '#10B981',
        'out_for_delivery': '#F97316',
        'delivered': '#22C55E',
        'cancelled': '#EF4444',
        'failed': '#DC2626'
    };
    
    orderStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(data).map(method => 
                method.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
            ),
            datasets: [{
                data: Object.values(data),
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B'],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            }
        }
    });
}

/**
 * Update orders chart with new period data
 */
function updateOrdersChart(period) {
    showLoading();
    
    fetch(`{{ route('admin.dashboard.data') }}?type=orders_chart&period=${period}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            ordersChart.data.labels = data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            ordersChart.data.datasets[0].data = data.map(item => item.count);
            ordersChart.update('active');
            hideLoading();
        })
        .catch(error => {
            console.error('Error updating orders chart:', error);
            showNotification('Error updating orders chart', 'error');
            hideLoading();
        });
}

/**
 * Update revenue chart with new period data
 */
function updateRevenueChart(period) {
    showLoading();
    
    fetch(`{{ route('admin.dashboard.data') }}?type=revenue_chart&period=${period}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            revenueChart.data.labels = data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
            revenueChart.data.datasets[0].data = data.map(item => parseFloat(item.revenue) || 0);
            revenueChart.update('active');
            hideLoading();
        })
        .catch(error => {
            console.error('Error updating revenue chart:', error);
            showNotification('Error updating revenue chart', 'error');
            hideLoading();
        });
}

/**
 * Load recent activities via AJAX
 */
function loadRecentActivities() {
    const activitiesContainer = document.getElementById('activities-list');
    
    // Show loading state
    activitiesContainer.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>
    `;
    
    fetch(`{{ route('admin.dashboard.data') }}?type=recent_activities`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(activities => {
            if (activities.length === 0) {
                activitiesContainer.innerHTML = `
                    <div class="text-center py-8">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                            <i class="fas fa-inbox text-gray-400 text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">No recent activities found</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            activities.forEach((activity, index) => {
                const iconClass = activity.type === 'order' ? 'fa-shopping-cart' : 'fa-file-alt';
                const bgColor = activity.type === 'order' ? 'bg-blue-100' : 'bg-orange-100';
                const iconColor = activity.type === 'order' ? 'text-blue-600' : 'text-orange-600';
                
                html += `
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition duration-200">
                        <div class="flex-shrink-0">
                            <div class="${bgColor} p-2 rounded-full">
                                <i class="fas ${iconClass} ${iconColor} text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 font-medium">${activity.message}</p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500">${activity.time}</p>
                                ${activity.amount ? `<span class="text-sm font-semibold text-green-600">₱${parseFloat(activity.amount).toLocaleString()}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            activitiesContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading activities:', error);
            activitiesContainer.innerHTML = `
                <div class="text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                    <p class="mt-2 text-sm text-red-500">Error loading activities</p>
                    <button onclick="loadRecentActivities()" class="mt-2 text-xs text-blue-600 hover:text-blue-800">
                        Try again
                    </button>
                </div>
            `;
        });
}

/**
 * Refresh all dashboard data
 */
function refreshDashboard() {
    showLoading();
    showNotification('Refreshing dashboard data...', 'info');
    
    // Reload the page after a short delay
    setTimeout(() => {
        window.location.reload();
    }, 800);
}

/**
 * Refresh dashboard data without full page reload
 */
function refreshDashboardData() {
    loadRecentActivities();
    // You can add more specific data refresh calls here
}

/**
 * Export data functionality
 */
function exportData(type) {
    showLoading();
    
    const url = `{{ route('admin.dashboard.export') }}?export_type=${type}`;
    
    // Create temporary download link
    const link = document.createElement('a');
    link.href = url;
    link.download = '';
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification(`Exporting ${type} data...`, 'success');
    
    setTimeout(() => {
        hideLoading();
    }, 1500);
}

/**
 * Show loading overlay
 */
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

/**
 * Hide loading overlay
 */
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

/**
 * Show notification message
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full`;
    
    // Set background color based on type
    const bgColors = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'info': 'bg-blue-500',
        'warning': 'bg-yellow-500'
    };
    
    notification.classList.add(bgColors[type] || bgColors['info']);
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Utility function to format numbers
 */
function formatNumber(num) {
    return new Intl.NumberFormat('en-US').format(num);
}

/**
 * Utility function to format currency
 */
function formatCurrency(amount) {
    return '₱' + parseFloat(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/**
 * Utility function to format dates
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

/**
 * Create the payment method doughnut chart
 */
function createPaymentMethodChart(data) {
    const ctx = document.getElementById('paymentMethodChart').getContext('2d');
    
    const colors = {
        'online_payment': '#3B82F6',
        'cod': '#10B981'
    };
    
    paymentMethodChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(data).map(method => 
                method.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
            ),
            datasets: [{
                data: Object.values(data),
                backgroundColor: Object.keys(data).map(method => colors[method] || '#6B7280'),
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverBorderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff'
                }
            }
        }
    });
}

/**
 * Handle chart responsiveness on window resize
 */
window.addEventListener('resize', function() {
    if (ordersChart) ordersChart.resize();
    if (revenueChart) revenueChart.resize();
    if (orderStatusChart) orderStatusChart.resize();
    if (paymentMethodChart) paymentMethodChart.resize();
});
</script>
@endsection