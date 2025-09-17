@extends('layout.vendor')

@section('title', 'Order Management')

@section('content')
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header Section -->
            <div class="px-6 py-8 border-b border-gray-100 bg-gradient-to-r from-white to-green-50/30">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-6 md:mb-0">
                        <h1 class="text-3xl font-semibold text-gray-900 mb-2">Order Management</h1>
                        <p class="text-gray-600 text-lg">View and manage orders containing your products</p>
                    </div>
                    
                    <!-- Status Legend -->
                    <div class="bg-white rounded-xl px-6 py-4 shadow-sm border border-gray-100">
                        <div class="flex flex-wrap items-center gap-6 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-amber-400 rounded-full mr-3 shadow-sm"></div>
                                <span class="text-gray-700 font-medium">Pending</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3 shadow-sm"></div>
                                <span class="text-gray-700 font-medium">Preparing</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 shadow-sm"></div>
                                <span class="text-gray-700 font-medium">Ready</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($orders->count() > 0)
                <!-- Orders List -->
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($orders as $order)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-green-200 transition-all duration-300">
                                <!-- Order Header -->
                                <div class="p-6 border-b border-gray-100">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                                        <div class="flex-1">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mb-4">
                                                <h2 class="text-xl font-semibold text-gray-900 mb-2 sm:mb-0">
                                                    Order #{{ $order->id }}
                                                </h2>
                                                <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-full
                                                    @if($order->status === 'processing') bg-amber-50 text-amber-700 border border-amber-200
                                                    @elseif($order->status === 'awaiting_rider_assignment') bg-blue-50 text-blue-700 border border-blue-200
                                                    @elseif($order->status === 'out_for_delivery') bg-purple-50 text-purple-700 border border-purple-200
                                                    @elseif($order->status === 'delivered') bg-green-50 text-green-700 border border-green-200
                                                    @else bg-gray-50 text-gray-700 border border-gray-200
                                                    @endif">
                                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span class="text-gray-600">
                                                        <span class="font-medium text-gray-900">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</span>
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-gray-600">
                                                        <span class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                                        <span class="text-gray-500">at {{ $order->created_at->format('h:i A') }}</span>
                                                    </span>
                                                </div>
                                                <div class="flex items-start">
                                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span class="text-gray-600">
                                                        <span class="font-medium text-gray-900">{{ $order->deliveryAddress->address_line1 }}</span>
                                                        <span class="text-gray-500">, {{ $order->deliveryAddress->district->name }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6 lg:mt-0 lg:ml-6">
                                            <a href="{{ route('vendor.orders.show', $order) }}" 
                                               class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors duration-200 shadow-sm hover:shadow-md">
                                                View Details
                                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Items Section -->
                                <div class="p-6 bg-gray-50/30">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Your Items</h3>
                                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                            {{ $order->orderItems->count() }} {{ Str::plural('item', $order->orderItems->count()) }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                                        @foreach($order->orderItems as $item)
                                            <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-green-200 transition-colors duration-200">
                                                <div class="flex items-start space-x-4">
                                                    <!-- Product Image -->
                                                    <div class="flex-shrink-0">
                                                        @if($item->product && $item->product->image_url)
                                                            <img src="{{ $item->product->image_url }}" 
                                                                 alt="{{ $item->product_name_snapshot }}"
                                                                 class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                                                        @else
                                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Product Details -->
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex-1">
                                                                <h4 class="font-semibold text-gray-900 text-sm leading-5 mb-2">
                                                                    {{ $item->product_name_snapshot }}
                                                                </h4>
                                                                <div class="space-y-1">
                                                                    <p class="text-sm text-gray-600">
                                                                        <span class="font-medium">Qty:</span> {{ $item->quantity_requested }} {{ $item->product->unit }}
                                                                    </p>
                                                                    @if($item->customer_budget_requested)
                                                                        <p class="text-sm text-gray-600">
                                                                            <span class="font-medium">Budget:</span> ₱{{ number_format($item->customer_budget_requested, 2) }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Status Indicator -->
                                                            <div class="ml-3 flex-shrink-0">
                                                                @if($item->status === 'pending')
                                                                    <div class="w-3 h-3 bg-amber-400 rounded-full shadow-sm" title="Pending"></div>
                                                                @elseif($item->status === 'preparing')
                                                                    <div class="w-3 h-3 bg-blue-500 rounded-full shadow-sm" title="Preparing"></div>
                                                                @elseif($item->status === 'ready_for_pickup')
                                                                    <div class="w-3 h-3 bg-green-500 rounded-full shadow-sm" title="Ready"></div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                        <div class="flex justify-center">
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16 px-6">
                    <div class="mx-auto w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mb-6 border border-green-100">
                        <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">No Orders Found</h3>
                    <p class="text-gray-600 mb-4 text-lg">You don't have any orders to fulfill at the moment.</p>
                    <p class="text-sm text-gray-500 max-w-md mx-auto leading-relaxed">
                        Orders containing your products will appear here when customers place them. 
                        Make sure your products are available and well-stocked to receive orders.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-lg z-50 max-w-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-lg z-50 max-w-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
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