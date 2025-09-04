@extends('layout.vendor')

@section('title', 'Order Management')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
                    <p class="text-gray-600">View and manage orders containing your products</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Status Legend -->
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                            <span class="text-gray-600">Pending</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                            <span class="text-gray-600">Preparing</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                            <span class="text-gray-600">Ready</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($orders->count() > 0)
                <!-- Orders List -->
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <!-- Order Header -->
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            Order #{{ $order->id }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            @if($order->status === 'processing') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'awaiting_rider_assignment') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'out_for_delivery') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <p><strong>Customer:</strong> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                        <p><strong>Delivery Address:</strong> 
                                            {{ $order->deliveryAddress->address_line1 }}, {{ $order->deliveryAddress->district->name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 lg:mt-0 lg:text-right">
                                    <a href="{{ route('vendor.orders.show', $order) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        View Details
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Order Items Summary -->
                            <div class="border-t pt-4">
                                <h4 class="text-md font-semibold text-gray-700 mb-3">Your Items ({{ $order->orderItems->count() }})</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-800 text-sm">{{ $item->product_name_snapshot }}</p>
                                                <p class="text-xs text-gray-600">
                                                    Qty: {{ $item->quantity_requested }} {{ $item->product->unit }}
                                                    @if($item->customer_budget_requested)
                                                        | Budget: ₱{{ number_format($item->customer_budget_requested, 2) }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="ml-3">
                                                @if($item->status === 'pending')
                                                    <span class="w-3 h-3 bg-yellow-400 rounded-full inline-block"></span>
                                                @elseif($item->status === 'preparing')
                                                    <span class="w-3 h-3 bg-blue-500 rounded-full inline-block"></span>
                                                @elseif($item->status === 'ready_for_pickup')
                                                    <span class="w-3 h-3 bg-green-500 rounded-full inline-block"></span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Orders Found</h3>
                    <p class="text-gray-600 mb-4">You don't have any orders to fulfill at the moment.</p>
                    <p class="text-sm text-gray-500">Orders containing your products will appear here when customers place them.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <script>
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            
            if (successMsg) {
                successMsg.style.opacity = '0';
                setTimeout(() => successMsg.remove(), 300);
            }
            
            if (errorMsg) {
                errorMsg.style.opacity = '0';
                setTimeout(() => errorMsg.remove(), 300);
            }
        }, 5000);
    </script>
@endsection