@extends('layout.admin')

@section('title', 'Rider Payouts Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-[90rem] mx-auto px-4 py-8">
        <!-- Header Section with Gradient Background -->
        <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl shadow-lg p-8 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center text-white">
                <div class="mb-6 lg:mb-0">
                    <h2 class="text-2xl lg:text-2xl font-bold  text-white drop-shadow-sm">
                        Rider Payouts
                    </h2>
                    <p class="text-emerald-100 text-md">
                        Manage and process rider delivery fee payouts
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4 border border-white/20">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <div>
                            <div class="text-2xl font-bold text-white">{{ $payouts->total() }}</div>
                            <div class="text-emerald-100 text-sm">Total Records</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
            <!-- Status Filter Tabs -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-100">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.payouts.riders', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md
                              {{ (request('status', 'all') === 'all') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        All ({{ $statusCounts['all'] }})
                    </a>
                    <a href="{{ route('admin.payouts.riders', array_merge(request()->query(), ['status' => 'pending_payment'])) }}" 
                       class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md
                              {{ (request('status') === 'pending_payment') ? 'bg-yellow-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pending Payment ({{ $statusCounts['pending_payment'] }})
                    </a>
                    <a href="{{ route('admin.payouts.riders', array_merge(request()->query(), ['status' => 'paid'])) }}" 
                       class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md
                              {{ (request('status') === 'paid') ? 'bg-green-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Paid ({{ $statusCounts['paid'] }})
                    </a>
                    <a href="{{ route('admin.payouts.riders', array_merge(request()->query(), ['status' => 'failed'])) }}" 
                       class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md
                              {{ (request('status') === 'failed') ? 'bg-red-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Failed ({{ $statusCounts['failed'] }})
                    </a>
                </div>
            </div>

            <!-- Date Range Filters -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6">
                <form method="GET" action="{{ route('admin.payouts.riders') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Period Start Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Period End Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors duration-200">
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.payouts.riders') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 text-center shadow-md hover:shadow-lg">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Payouts Table -->
            @if($payouts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Rider
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">
                                    Payout Period
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                                    Delivery Fees
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden xl:table-cell">
                                    Adjustments
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Total Amount
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($payouts as $payout)
                                <tr class="hover:bg-gradient-to-r hover:from-emerald-50/50 hover:to-teal-50/50 transition-all duration-200 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center shadow-md">
                                                <span class="text-sm font-bold text-blue-700">
                                                    {{ $payout->rider ? substr($payout->rider->first_name, 0, 1) . substr($payout->rider->last_name, 0, 1) : 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors duration-200">
                                                    {{ $payout->rider ? $payout->rider->first_name . ' ' . $payout->rider->last_name : 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $payout->rider ? $payout->rider->email : 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $payout->payout_period_start_date->format('M d') }} - {{ $payout->payout_period_end_date->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    ₱{{ number_format($payout->total_delivery_fees_earned, 2) }}
                                                </div>
                                                @if($payout->total_incentives_earned > 0)
                                                    <div class="text-xs text-green-600 font-medium">
                                                        +₱{{ number_format($payout->total_incentives_earned, 2) }} incentives
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                        @if($payout->adjustments_amount != 0)
                                            <div class="flex items-center">
                                                @if($payout->adjustments_amount > 0)
                                                    <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                    </svg>
                                                @endif
                                                <span class="text-sm font-semibold {{ $payout->adjustments_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $payout->adjustments_amount > 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">₱0.00</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                            <span class="text-sm font-bold text-gray-900">
                                                ₱{{ number_format($payout->total_payout_amount, 2) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($payout->status)
                                            @case('pending_payment')
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                    Pending Payment
                                                </span>
                                                @break
                                            @case('paid')
                                                <div>
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                        Paid
                                                    </span>
                                                    @if($payout->paid_at)
                                                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            {{ $payout->paid_at->format('M d, Y') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @break
                                            @case('failed')
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                    Failed
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                    <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                                    {{ ucfirst($payout->status) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('admin.payouts.riders.show', $payout->id) }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-emerald-600 hover:text-white hover:bg-emerald-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md border border-emerald-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($payouts->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $payouts->firstItem() }} to {{ $payouts->lastItem() }} of {{ $payouts->total() }} results
                            </div>
                            <div>
                                {{ $payouts->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No payouts found</h3>
                        <p class="text-gray-600 mb-6 max-w-md text-center">
                            @if(request()->hasAny(['status', 'date_from', 'date_to']))
                                No payouts match your current filters.
                            @else
                                No rider payouts have been generated yet.
                            @endif
                        </p>
                        @if(request()->hasAny(['status', 'date_from', 'date_to']))
                            <a href="{{ route('admin.payouts.riders') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection