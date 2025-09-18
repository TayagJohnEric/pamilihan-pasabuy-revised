@extends('layout.vendor')
@section('title', 'Earnings & Payouts')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Earnings & Payouts</h2>
                <p class="text-gray-600">Track your sales performance and payout history</p>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Earnings -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-green-600 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Earnings</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ₱{{ number_format($totalStats->total_earned ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50  p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-green-600 lucide lucide-chart-line-icon lucide-chart-line"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Sales</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ₱{{ number_format($totalStats->total_sales ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Commission Paid -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50  p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Commission Paid</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ₱{{ number_format($totalStats->total_commission ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Payouts -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50  p-6 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2m0 0V5a2 2 0 012-2h14a2 2 0 012 2v4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Total Payouts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStats->total_payouts ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Period Sales -->
        @if($currentPeriodSales && $currentPeriodSales->total_sales > 0)
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Period Sales</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $currentPeriodSales->order_count }}</p>
                    <p class="text-sm text-gray-600">Orders</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($currentPeriodSales->total_sales, 2) }}</p>
                    <p class="text-sm text-gray-600">Gross Sales</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($currentPeriodSales->estimated_commission, 2) }}</p>
                    <p class="text-sm text-gray-600">Est. Commission</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-center">
                Period: {{ $currentPeriodStart->format('M j, Y') }} - Present
            </p>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Payouts</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status_filter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" {{ request('status_filter', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="pending_calculation" {{ request('status_filter') == 'pending_calculation' ? 'selected' : '' }}>Pending Calculation</option>
                    <option value="pending_payment" {{ request('status_filter') == 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="paid" {{ request('status_filter') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('status_filter') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Payout History -->
    @if($payouts->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Payout History</h3>
        </div>
        
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adjustments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payout</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($payouts as $payout)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $payout->payout_period_start_date->format('M j') }} - {{ $payout->payout_period_end_date->format('M j, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₱{{ number_format($payout->total_sales_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">₱{{ number_format($payout->platform_commission_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($payout->adjustments_amount != 0)
                                    <span class="{{ $payout->adjustments_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $payout->adjustments_amount > 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">₱{{ number_format($payout->total_payout_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $payout->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($payout->status == 'pending_payment' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($payout->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('vendor.earnings.payout.details', $payout->id) }}" 
                               class="text-blue-600 hover:text-blue-900">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-gray-200">
            @foreach($payouts as $payout)
            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-medium text-gray-900">
                            {{ $payout->payout_period_start_date->format('M j') }} - {{ $payout->payout_period_end_date->format('M j, Y') }}
                        </p>
                        <p class="text-sm text-gray-500">Payout Period</p>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $payout->status == 'paid' ? 'bg-green-100 text-green-800' : 
                           ($payout->status == 'pending_payment' ? 'bg-yellow-100 text-yellow-800' : 
                           ($payout->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Sales</p>
                        <p class="text-lg font-semibold text-gray-900">₱{{ number_format($payout->total_sales_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Payout</p>
                        <p class="text-lg font-semibold text-gray-900">₱{{ number_format($payout->total_payout_amount, 2) }}</p>
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <div class="text-sm">
                        <span class="text-gray-500">Commission: </span>
                        <span class="text-gray-900">₱{{ number_format($payout->platform_commission_amount, 2) }}</span>
                        @if($payout->adjustments_amount != 0)
                            <span class="text-gray-500"> • Adj: </span>
                            <span class="{{ $payout->adjustments_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $payout->adjustments_amount > 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('vendor.earnings.payout.details', $payout->id) }}" 
                       class="text-blue-600 hover:text-blue-900 text-sm font-medium">View Details</a>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($payouts->hasPages())
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $payouts->withQueryString()->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-8">
        <div class="text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-gray-600 text-lg mb-2">No payouts yet</p>
            <p class="text-gray-500">Your payout history will appear here once processed</p>
        </div>
    </div>
    @endif
</div>
@endsection