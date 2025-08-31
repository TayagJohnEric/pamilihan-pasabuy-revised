@extends('layout.customer')

@section('title', 'Order #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600">Placed on {{ $order->order_date->format('F d, Y \a\t h:i A') }}</p>
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium mb-2
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
                    <div class="text-sm text-gray-500">
                        Payment: 
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
            </div>
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('customer.orders.index') }}" 
                   class="inline-flex items-center text-blue-600 hover:text-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Orders
                </a>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                @if($order->status === 'pending_payment')
                    <button class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Pay Now
                    </button>
                @endif
                @if(in_array($order->status, ['pending_payment', 'processing']))
                    <button class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Cancel Order
                    </button>
                @endif
                @if($order->status === 'delivered')
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Rate Order
                    </button>
                @endif
                <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    Download Invoice
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Items</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <!-- Product Image -->
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" 
                                                     alt="{{ $item->product_name_snapshot }}"
                                                     class="w-16 h-16 object-cover rounded-md flex-shrink-0">
                                            @else
                                                <div class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            <!-- Product Details -->
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900">{{ $item->product_name_snapshot }}</h3>
                                                
                                                @if($item->product && $item->product->vendor)
                                                    <p class="text-sm text-gray-500 mb-1">
                                                        Vendor: {{ $item->product->vendor->business_name ?? $item->product->vendor->name }}
                                                    </p>
                                                @endif
                                                
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mt-2">
                                                    <div>
                                                        <span class="font-medium text-gray-700">Quantity:</span>
                                                        <span class="text-gray-900">{{ $item->quantity_requested }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Unit Price:</span>
                                                        <span class="text-gray-900">₱{{ number_format($item->unit_price_snapshot, 2) }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Status:</span>
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                            @switch($item->status)
                                                                @case('pending')
                                                                    bg-yellow-100 text-yellow-800
                                                                    @break
                                                                @case('preparing')
                                                                    bg-blue-100 text-blue-800
                                                                    @break
                                                                @case('ready_for_pickup')
                                                                    bg-green-100 text-green-800
                                                                    @break
                                                                @default
                                                                    bg-gray-100 text-gray-800
                                                            @endswitch">
                                                            {{ ucwords(str_replace('_', ' ', $item->status)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-700">Subtotal:</span>
                                                        <span class="text-gray-900 font-medium">
                                                            ₱{{ number_format(($item->actual_item_price ?? $item->unit_price_snapshot) * $item->quantity_requested, 2) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Customer Budget (if applicable) -->
                                                @if($item->customer_budget_requested)
                                                    <div class="mt-2 text-sm">
                                                        <span class="font-medium text-gray-700">Customer Budget:</span>
                                                        <span class="text-gray-900">₱{{ number_format($item->customer_budget_requested, 2) }}</span>
                                                    </div>
                                                @endif

                                                <!-- Substitution Info -->
                                                @if($item->is_substituted && $item->substitutedProduct)
                                                    <div class="mt-2 p-2 bg-orange-50 border border-orange-200 rounded">
                                                        <div class="flex items-center gap-1 text-sm">
                                                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                            </svg>
                                                            <span class="font-medium text-orange-800">Substituted with:</span>
                                                            <span class="text-orange-700">{{ $item->substitutedProduct->product_name }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Vendor Notes -->
                                                @if($item->vendor_fulfillment_notes)
                                                    <div class="mt-2 text-sm">
                                                        <span class="font-medium text-gray-700">Vendor Notes:</span>
                                                        <p class="text-gray-600 mt-1">{{ $item->vendor_fulfillment_notes }}</p>
                                                    </div>
                                                @endif

                                                <!-- Customer Notes -->
                                                @if($item->customerNotes_snapshot)
                                                    <div class="mt-2 text-sm">
                                                        <span class="font-medium text-gray-700">Your Notes:</span>
                                                        <p class="text-gray-600 mt-1">{{ $item->customerNotes_snapshot }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Status History -->
                @if($order->statusHistory->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Order Timeline</h2>
                        
                        <div class="relative">
                            @foreach($order->statusHistory->sortBy('created_at') as $history)
                                <div class="flex items-start gap-4 pb-4 {{ !$loop->last ? 'border-l-2 border-gray-200 ml-4' : '' }}">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 -ml-4
                                        @switch($history->status)
                                            @case('processing')
                                                bg-blue-100 text-blue-600
                                                @break
                                            @case('out_for_delivery')
                                                bg-purple-100 text-purple-600
                                                @break
                                            @case('delivered')
                                                bg-green-100 text-green-600
                                                @break
                                            @case('cancelled')
                                                bg-red-100 text-red-600
                                                @break
                                            @default
                                                bg-gray-100 text-gray-600
                                        @endswitch">
                                        <div class="w-3 h-3 rounded-full bg-current"></div>
                                    </div>
                                    
                                    <div class="flex-1 pb-4">
                                        <div class="flex justify-between items-center">
                                            <h3 class="font-medium text-gray-900">
                                                {{ ucwords(str_replace('_', ' ', $history->status)) }}
                                            </h3>
                                            <span class="text-sm text-gray-500">
                                                {{ $history->created_at->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                        
                                        @if($history->notes)
                                            <p class="text-gray-600 text-sm mt-1">{{ $history->notes }}</p>
                                        @endif
                                        
                                        @if($history->updatedBy)
                                            <p class="text-gray-500 text-xs mt-1">
                                                Updated by {{ $history->updatedBy->first_name }} {{ $history->updatedBy->last_name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Special Instructions -->
                @if($order->special_instructions)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Special Instructions</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $order->special_instructions }}</p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3 text-sm">
                        @php
                            $itemsTotal = $order->orderItems->sum(function($item) {
                                return ($item->actual_item_price ?? $item->unit_price_snapshot) * $item->quantity_requested;
                            });
                        @endphp
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Items Total ({{ $order->orderItems->count() }} items)</span>
                            <span class="text-gray-900">₱{{ number_format($itemsTotal, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Fee</span>
                            <span class="text-gray-900">₱{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between font-semibold text-lg">
                                <span class="text-gray-900">Total Amount</span>
                                <span class="text-gray-900">₱{{ number_format($order->final_total_amount, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="pt-2 border-t border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="text-gray-900">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Delivery Information</h2>
                    
                    @if($order->deliveryAddress)
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-700 block">Delivery Address:</span>
                                <div class="text-gray-900 mt-1">
                                    <p>{{ $order->deliveryAddress->address_line_1 }}</p>
                                    @if($order->deliveryAddress->address_line_2)
                                        <p>{{ $order->deliveryAddress->address_line_2 }}</p>
                                    @endif
                                    <p>{{ $order->deliveryAddress->city }}, {{ $order->deliveryAddress->state_province }}</p>
                                    <p>{{ $order->deliveryAddress->postal_code }}</p>
                                    @if($order->deliveryAddress->country)
                                        <p>{{ $order->deliveryAddress->country }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($order->deliveryAddress->recipient_name)
                                <div>
                                    <span class="font-medium text-gray-700">Recipient:</span>
                                    <span class="text-gray-900">{{ $order->deliveryAddress->recipient_name }}</span>
                                </div>
                            @endif
                            
                            @if($order->deliveryAddress->recipient_phone)
                                <div>
                                    <span class="font-medium text-gray-700">Phone:</span>
                                    <span class="text-gray-900">{{ $order->deliveryAddress->recipient_phone }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    @if($order->rider)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h3 class="font-medium text-gray-900 mb-2">Assigned Rider</h3>
                            <div class="flex items-center gap-3">
                                @if($order->rider->profile_image_url)
                                    <img src="{{ $order->rider->profile_image_url }}" 
                                         alt="{{ $order->rider->first_name }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">
                                            {{ substr($order->rider->first_name, 0, 1) }}{{ substr($order->rider->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $order->rider->first_name }} {{ $order->rider->last_name }}
                                    </p>
                                    @if($order->rider->phone_number)
                                        <p class="text-sm text-gray-600">{{ $order->rider->phone_number }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Payment Information -->
                @if($order->payment)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Payment Details</h2>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount Paid</span>
                                <span class="text-gray-900 font-medium">₱{{ number_format($order->payment->amount_paid, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="text-gray-900">{{ ucwords(str_replace('_', ' ', $order->payment->payment_method_used)) }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @switch($order->payment->status)
                                        @case('completed')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('failed')
                                            bg-red-100 text-red-800
                                            @break
                                        @case('refunded')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            </div>
                            
                            @if($order->payment->payment_processed_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Processed At</span>
                                    <span class="text-gray-900">{{ $order->payment->payment_processed_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                            
                            @if($order->payment->gateway_transaction_id)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Transaction ID</span>
                                    <span class="text-gray-900 font-mono text-xs">{{ $order->payment->gateway_transaction_id }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection