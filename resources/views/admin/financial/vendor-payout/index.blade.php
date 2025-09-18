@extends('layout.admin')

@section('title', 'Vendor Payouts Management')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Vendor Payouts</h2>
                    <p class="text-gray-600">Manage and process vendor sales payouts</p>
                </div>
                <div class="text-sm text-gray-500">
                    Total Records: {{ $payouts->total() }}
                </div>
            </div>

            <!-- Filter and Search Section -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <form method="GET" action="{{ route('admin.payouts.vendors') }}" class="space-y-4">
                    <!-- Status Filter Tabs -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('admin.payouts.vendors', array_merge(request()->query(), ['status' => 'all'])) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ (request('status', 'all') === 'all') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            All ({{ $statusCounts['all'] }})
                        </a>
                        <a href="{{ route('admin.payouts.vendors', array_merge(request()->query(), ['status' => 'pending_payment'])) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ (request('status') === 'pending_payment') ? 'bg-yellow-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            Pending Payment ({{ $statusCounts['pending_payment'] }})
                        </a>
                        <a href="{{ route('admin.payouts.vendors', array_merge(request()->query(), ['status' => 'paid'])) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ (request('status') === 'paid') ? 'bg-green-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            Paid ({{ $statusCounts['paid'] }})
                        </a>
                        <a href="{{ route('admin.payouts.vendors', array_merge(request()->query(), ['status' => 'failed'])) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                                  {{ (request('status') === 'failed') ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            Failed ({{ $statusCounts['failed'] }})
                        </a>
                    </div>

                    <!-- Date Range Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Period Start Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Period End Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.payouts.vendors') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Payouts Table -->
            @if($payouts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vendor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payout Period
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Sales
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Commission
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Adjustments
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payouts as $payout)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($payout->vendor && $payout->vendor->vendor && $payout->vendor->vendor->shop_logo_url)
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ $payout->vendor->vendor->shop_logo_url }}" alt="">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ $payout->vendor && $payout->vendor->vendor ? substr($payout->vendor->vendor->vendor_name, 0, 1) : 'V' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $payout->vendor && $payout->vendor->vendor ? $payout->vendor->vendor->vendor_name : 'Unknown Vendor' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $payout->vendor ? $payout->vendor->first_name . ' ' . $payout->vendor->last_name : 'N/A' }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $payout->vendor ? $payout->vendor->email : 'No email' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payout->payout_period_start_date->format('M d') }} - {{ $payout->payout_period_end_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ₱{{ number_format($payout->total_sales_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                        -₱{{ number_format($payout->platform_commission_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($payout->adjustments_amount != 0)
                                            <span class="{{ $payout->adjustments_amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $payout->adjustments_amount > 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">₱0.00</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₱{{ number_format($payout->total_payout_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($payout->status)
                                            @case('pending_payment')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending Payment
                                                </span>
                                                @break
                                            @case('paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Paid
                                                </span>
                                                @if($payout->paid_at)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $payout->paid_at->format('M d, Y') }}
                                                    </div>
                                                @endif
                                                @break
                                            @case('failed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Failed
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($payout->status) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.payouts.vendors.show', $payout->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 hover:underline">
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
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Showing {{ $payouts->firstItem() }} to {{ $payouts->lastItem() }} of {{ $payouts->total() }} results
                        </div>
                        <div>
                            {{ $payouts->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No payouts found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['status', 'date_from', 'date_to']))
                            No payouts match your current filters.
                        @else
                            No vendor payouts have been generated yet.
                        @endif
                    </p>
                    @if(request()->hasAny(['status', 'date_from', 'date_to']))
                        <div class="mt-6">
                            <a href="{{ route('admin.payouts.vendors') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection