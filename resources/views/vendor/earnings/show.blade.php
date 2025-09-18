@extends('layout.vendor')
@section('title', 'Payout Details')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('vendor.earnings.index') }}" class="text-gray-600 hover:text-blue-600 text-sm">
                    Earnings
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-500 text-sm ml-1">Payout Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Payout Summary -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Payout Details</h2>
                <p class="text-gray-600">
                    Period: {{ $payout->payout_period_start_date->format('M j, Y') }} - {{ $payout->payout_period_end_date->format('M j, Y') }}
                </p>
            </div>
            <div class="mt-4 lg:mt-0">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    {{ $payout->status == 'paid' ? 'bg-green-100 text-green-800' : 
                       ($payout->status == 'pending_payment' ? 'bg-yellow-100 text-yellow-800' : 
                       ($payout->status == 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ ucfirst(str_replace('_', ' ', $payout->status)) }}
                </span>
            </div>
        </div>

        <!-- Payout Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="text-center">
                    <p class="text-sm font-medium text-green-600">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($payout->total_sales_amount, 2) }}</p>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="text-center">
                    <p class="text-sm font-medium text-green-600">Platform Commission</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($payout->platform_commission_amount, 2) }}</p>
                </div>
            </div>
            <div class="bg-{{ $payout->adjustments_amount >= 0 ? 'green' : 'red' }}-50 p-4 rounded-lg border border-{{ $payout->adjustments_amount >= 0 ? 'green' : 'red' }}-200">
                <div class="text-center">
                    <p class="text-sm font-medium text-{{ $payout->adjustments_amount >= 0 ? 'green' : 'red' }}-600">Adjustments</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $payout->adjustments_amount >= 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                    </p>
                </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="text-center">
                    <p class="text-sm font-medium text-green-600">Total Payout</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($payout->total_payout_amount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($payout->paid_at)
                <div>
                    <p class="text-sm font-medium text-gray-500">Payment Date</p>
                    <p class="text-gray-900">{{ $payout->paid_at->format('M j, Y g:i A') }}</p>
                </div>
                @endif
                @if($payout->transaction_reference)
                <div>
                    <p class="text-sm font-medium text-gray-500">Transaction Reference</p>
                    <p class="text-gray-900 font-mono">{{ $payout->transaction_reference }}</p>
                </div>
                @endif
                @if($payout->adjustments_notes)
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Adjustment Notes</p>
                    <p class="text-gray-900">{{ $payout->adjustments_notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Orders Included in Payout -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Orders Included in This Payout</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $orders->count() }} orders from this period</p>
        </div>
        
        @if($orders->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    @php
                        $vendorItems = $order->orderItems->filter(function($item) {
                            return $item->product && $item->product->vendor_id == Auth::user()->vendor->id;
                        });
                        $vendorTotal = $vendorItems->sum(function($item) {
                            return $item->actual_item_price * $item->quantity_requested;
                        });
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('M j, Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @foreach($vendorItems as $item)
                                <div class="mb-1">
                                    {{ $item->quantity_requested }}x {{ $item->product_name_snapshot }}
                                    <span class="text-gray-500">(₱{{ number_format($item->actual_item_price, 2) }})</span>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">₱{{ number_format($vendorTotal, 2) }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-gray-200">
            @foreach($orders as $order)
            @php
                $vendorItems = $order->orderItems->filter(function($item) {
                    return $item->product && $item->product->vendor_id == Auth::user()->vendor->id;
                });
                $vendorTotal = $vendorItems->sum(function($item) {
                    return $item->actual_item_price * $item->quantity_requested;
                });
            @endphp
            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₱{{ number_format($vendorTotal, 2) }}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-500">Customer</p>
                    <p class="text-sm text-gray-900">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Items</p>
                    @foreach($vendorItems as $item)
                    <div class="text-sm text-gray-900 mb-1">
                        {{ $item->quantity_requested }}x {{ $item->product_name_snapshot }}
                        <span class="text-gray-500">(₱{{ number_format($item->actual_item_price, 2) }})</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-600">No orders found for this payout period</p>
        </div>
        @endif
    </div>
</div>
@endsection