@extends('layout.rider')
@section('title', 'Earnings Dashboard')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Earnings Dashboard</h1>
                <p class="text-gray-600 mt-1">Track your delivery earnings and performance</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Current Month Earnings -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">This Month</p>
                    <p class="text-2xl font-bold">₱{{ number_format($currentMonthEarnings, 2) }}</p>
                    <p class="text-blue-100 text-sm">{{ $currentMonthDeliveries }} deliveries</p>
                </div>
                <div class="bg-blue-500 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Incentives -->
        <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Incentives</p>
                    <p class="text-2xl font-bold">₱{{ number_format($currentMonthIncentives, 2) }}</p>
                    <p class="text-green-100 text-sm">This month</p>
                </div>
                <div class="bg-emerald-500 p-3 rounded-full">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 lucide lucide-hand-coins-icon lucide-hand-coins"><path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/><path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/><path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/></svg>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Earnings</p>
                    <p class="text-2xl font-bold">₱{{ number_format($totalEarnings, 2) }}</p>
                    <p class="text-purple-100 text-sm">All time</p>
                </div>
                <div class="bg-purple-500 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 lucide lucide-philippine-peso-icon lucide-philippine-peso"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Rating</p>
                    <div class="flex items-center">
                        <p class="text-2xl font-bold">{{ number_format($riderStats->average_rating ?? 0, 1) }}</p>
                        <svg class="w-5 h-5 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <p class="text-orange-100 text-sm">{{ $riderStats->total_deliveries ?? 0 }} total deliveries</p>
                </div>
                <div class="bg-yellow-400 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Earnings Chart & Pending Payout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Daily Earnings (Last 7 Days) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Daily Earnings (Last 7 Days)</h3>
            <div class="h-64">
                <div class="flex items-end justify-between h-full space-x-2">
                    @foreach($dailyEarnings as $day)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="bg-blue-500 rounded-t w-full transition-all hover:bg-blue-600" 
                             style="height: {{ $day['earnings'] > 0 ? ($day['earnings'] / max(array_column($dailyEarnings, 'earnings'))) * 200 : 2 }}px;">
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-xs text-gray-600">{{ $day['date'] }}</p>
                            <p class="text-sm font-medium">₱{{ number_format($day['earnings'], 0) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pending Payout -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pending Payout</h3>
            @if($pendingPayout)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-yellow-800">{{ ucfirst($pendingPayout->status) }}</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-800 mb-2">₱{{ number_format($pendingPayout->total_payout_amount, 2) }}</p>
                    <p class="text-sm text-gray-600">
                        Period: {{ $pendingPayout->payout_period_start_date->format('M j') }} - 
                        {{ $pendingPayout->payout_period_end_date->format('M j, Y') }}
                    </p>
                    @if($pendingPayout->total_incentives_earned > 0)
                        <p class="text-xs text-green-600 mt-1">
                            +₱{{ number_format($pendingPayout->total_incentives_earned, 2) }} incentives
                        </p>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">No pending payouts</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Payouts -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Payouts</h3>
            <a href="{{ route('rider.payouts') }}" 
               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                View All →
            </a>
        </div>
        
        @if($recentPayouts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Period</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Amount</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Status</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayouts as $payout)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3">
                                <p class="text-sm text-gray-800">
                                    {{ $payout->payout_period_start_date->format('M j') }} - 
                                    {{ $payout->payout_period_end_date->format('M j') }}
                                </p>
                            </td>
                            <td class="py-3">
                                <p class="text-sm font-medium text-gray-800">
                                    ₱{{ number_format($payout->total_payout_amount, 2) }}
                                </p>
                                @if($payout->total_incentives_earned > 0)
                                    <p class="text-xs text-green-600">
                                        +₱{{ number_format($payout->total_incentives_earned, 2) }} incentives
                                    </p>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $payout->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payout->status === 'pending_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $payout->status === 'pending_calculation' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $payout->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <p class="text-sm text-gray-600">
                                    {{ $payout->created_at->format('M j, Y') }}
                                </p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3h4v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm2.707 4.707a1 1 0 00-1.414-1.414L4 9.586l-.293-.293a1 1 0 10-1.414 1.414l1 1a1 1 0 001.414 0l2-2z" clip-rule="evenodd"/>
                </svg>
                <p class="text-gray-500">No payout history available</p>
            </div>
        @endif
    </div>
</div>
@endsection