@extends('layout.rider')
@section('title', 'Payout History')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Payout History</h1>
                <p class="text-gray-600 mt-1">Track your payment history and status</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="{{ route('rider.earnings') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    Back to Earnings
                </a>
                <a href="{{ route('rider.ratings') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    View Ratings
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Paid</p>
                    <p class="text-3xl font-bold">₱{{ number_format($totalPaid, 2) }}</p>
                    <p class="text-green-100 text-sm">All completed payouts</p>
                </div>
                <div class="bg-green-500 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Pending Amount</p>
                    <p class="text-3xl font-bold">₱{{ number_format($totalPending, 2) }}</p>
                    <p class="text-yellow-100 text-sm">Awaiting processing</p>
                </div>
                <div class="bg-yellow-400 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                <select name="status" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending_payment" {{ $status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="pending_calculation" {{ $status === 'pending_calculation' ? 'selected' : '' }}>Pending Calculation</option>
                    <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Period Filter</label>
                <select name="period" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="current_month" {{ $period === 'current_month' ? 'selected' : '' }}>Current Month</option>
                    <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="last_3_months" {{ $period === 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Payout Records</h3>
        </div>
        
        @if($payouts->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payout Period
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Earnings Breakdown
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payouts as $payout)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $payout->payout_period_start_date->format('M j') }} - 
                                        {{ $payout->payout_period_end_date->format('M j, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $payout->payout_period_start_date->diffInDays($payout->payout_period_end_date) + 1 }} days
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Delivery Fees:</span>
                                        <span class="font-medium">₱{{ number_format($payout->total_delivery_fees_earned, 2) }}</span>
                                    </div>
                                    @if($payout->total_incentives_earned > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Incentives:</span>
                                        <span class="font-medium text-green-600">₱{{ number_format($payout->total_incentives_earned, 2) }}</span>
                                    </div>
                                    @endif
                                    @if($payout->adjustments_amount != 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Adjustments:</span>
                                        <span class="font-medium {{ $payout->adjustments_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $payout->adjustments_amount > 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-lg font-bold text-gray-900">₱{{ number_format($payout->total_payout_amount, 2) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    {{ $payout->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payout->status === 'pending_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $payout->status === 'pending_calculation' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $payout->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($payout->paid_at)
                                    <div>
                                        <p>{{ $payout->paid_at->format('M j, Y') }}</p>
                                        <p class="text-xs">{{ $payout->paid_at->format('g:i A') }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-400">Not paid</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($payouts as $payout)
                <div class="p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $payout->payout_period_start_date->format('M j') }} - 
                                {{ $payout->payout_period_end_date->format('M j, Y') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $payout->payout_period_start_date->diffInDays($payout->payout_period_end_date) + 1 }} days
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $payout->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $payout->status === 'pending_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $payout->status === 'pending_calculation' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $payout->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Delivery Fees:</span>
                            <span class="font-medium">₱{{ number_format($payout->total_delivery_fees_earned, 2) }}</span>
                        </div>
                        @if($payout->total_incentives_earned > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Incentives:</span>
                            <span class="font-medium text-green-600">₱{{ number_format($payout->total_incentives_earned, 2) }}</span>
                        </div>
                        @endif
                        @if($payout->adjustments_amount != 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Adjustments:</span>
                            <span class="font-medium {{ $payout->adjustments_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $payout->adjustments_amount > 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg font-bold text-gray-900">₱{{ number_format($payout->total_payout_amount, 2) }}</p>
                            @if($payout->transaction_reference)
                                <p class="text-xs text-gray-500">Ref: {{ $payout->transaction_reference }}</p>
                            @endif
                        </div>
                        @if($payout->paid_at)
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $payout->paid_at->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $payout->paid_at->format('g:i A') }}</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($payout->adjustments_notes)
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 font-medium">Adjustment Notes:</p>
                            <p class="text-sm text-gray-700 mt-1">{{ $payout->adjustments_notes }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($payouts->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $payouts->appends(request()->query())->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No payouts found</h3>
                <p class="text-gray-600 mb-4">
                    @if(request()->has('status') && request('status') !== 'all')
                        No payouts found with the selected filters.
                    @else
                        You don't have any payout records yet.
                    @endif
                </p>
                @if(request()->has('status') || request()->has('period'))
                    <a href="{{ route('rider.payouts') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection