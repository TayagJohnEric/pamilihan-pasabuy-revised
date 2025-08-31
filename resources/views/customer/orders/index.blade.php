@extends('layout.customer')

@section('title', 'My Orders')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">My Orders</h2>
                <div class="text-sm text-gray-500">
                    {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('customer.orders.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="awaiting_rider_assignment" {{ request('status') === 'awaiting_rider_assignment' ? 'selected' : '' }}>Awaiting Rider</option>
                            <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('customer.orders.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Clear
                        </a>
                    </div>
                </div>
            </form>

            <!-- Status Counts -->
            @if(!empty($statusCounts))
            <div class="flex flex-wrap gap-3 mb-6">
                @foreach($statusCounts as $status => $count)
                <div class="flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full text-sm">
                    <span class="font-medium">{{ ucwords(str_replace('_', ' ', $status)) }}:</span>
                    <span class="text-gray-600">{{ $count }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Orders List -->
            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Order #{{ $order->id }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        Placed on {{ $order->order_date->format('M d, Y \a\t h:i A') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @switch($order->status)
                                            @case('pending_payment')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('processing')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('awaiting_rider_assignment')
                                                bg-orange-100 text-orange-800
                                                @break
                                            @case('out_for_delivery')
                                                bg-purple-100 text-purple-800
                                                @break
                                            @case('delivered')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('cancelled')
                                                bg-red-100 text-red-800
                                                @break
                                            @case('failed')
                                                bg-gray-100 text-gray-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Summary -->
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order->orderItems->take(3) as $item)
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 rounded text-sm text-gray-700">
                                            {{ $item->product_name_snapshot }}
                                            <span class="ml-1 text-gray-500">x{{ $item->quantity_requested }}</span>
                                        </span>
                                    @endforeach
                                    @if($order->orderItems->count() > 3)
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-200 rounded text-sm text-gray-600">
                                            +{{ $order->orderItems->count() - 3 }} more items
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Total Amount:</span>
                                    <span class="text-gray-900">₱{{ number_format($order->final_total_amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Payment Method:</span>
                                    <span class="text-gray-900">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Payment Status:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @switch($order->payment_status)
                                            @case('pending')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('paid')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('failed')
                                                bg-red-100 text-red-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Delivery Info -->
                            @if($order->deliveryAddress)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">Delivery Address:</span>
                                    <span class="text-gray-600">{{ $order->deliveryAddress->address_line_1 }}, {{ $order->deliveryAddress->city }}</span>
                                </div>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <div>
                                    @if($order->rider)
                                        <span class="text-sm text-gray-600">
                                            Rider: {{ $order->rider->first_name }} {{ $order->rider->last_name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('customer.orders.show', $order) }}" 
                                       class="px-4 py-2 text-blue-600 border border-blue-600 rounded-md hover:bg-blue-50 transition-colors text-sm">
                                        View Details
                                    </a>
                                    @if($order->status === 'pending_payment')
                                        <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm">
                                            Pay Now
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request()->hasAny(['status', 'date_from', 'date_to']))
                            Try adjusting your filters or 
                            <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:text-blue-500">clear all filters</a>
                        @else
                            You haven't placed any orders yet. Start shopping to see your orders here.
                        @endif
                    </p>
                    @if(!request()->hasAny(['status', 'date_from', 'date_to']))
                        <a href="{{ route('customer.products.index') ?? '#' }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Start Shopping
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection