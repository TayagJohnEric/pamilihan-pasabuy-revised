@extends('layout.rider')

@section('title', 'Order Details - #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto space-y-6">
        
        <!-- Back Navigation -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('rider.orders.index') }}" 
               class="flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Back to Dashboard</span>
            </a>
        </div>

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
                    <p class="text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-2 rounded-lg font-medium text-sm
                        {{ $order->status === 'awaiting_rider_assignment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status === 'assigned' ? 'bg-indigo-100 text-indigo-800' : '' }}
                        {{ $order->status === 'pickup_confirmed' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $order->status === 'out_for_delivery' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}">
                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>

            <!-- Order Action Buttons -->
            <div class="flex space-x-3">
                @if($order->status === 'awaiting_rider_assignment')
                    <button id="accept-order-btn" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
                            data-order-id="{{ $order->id }}">
                        Accept Order
                    </button>
                    <button id="decline-order-btn" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
                            data-order-id="{{ $order->id }}">
                        Decline Order
                    </button>
                @elseif($order->status === 'assigned')
                    <button id="confirm-pickup-btn" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
                            data-order-id="{{ $order->id }}">
                        Confirm Pickup
                    </button>
                @elseif($order->status === 'pickup_confirmed')
                    <button id="start-delivery-btn" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium"
                            data-order-id="{{ $order->id }}">
                        Start Delivery
                    </button>
                @elseif($order->status === 'out_for_delivery')
                    <button id="mark-delivered-btn" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium"
                            data-order-id="{{ $order->id }}">
                        Mark as Delivered
                    </button>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Customer Information
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Contact Details</h4>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Name:</span> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                        <p><span class="font-medium">Phone:</span> 
                            @if($order->customer->phone_number)
                                <a href="tel:{{ $order->customer->phone_number }}" class="text-blue-600 hover:underline">
                                    {{ $order->customer->phone_number }}
                                </a>
                            @else
                                Not provided
                            @endif
                        </p>
                        <p><span class="font-medium">Email:</span> {{ $order->customer->email }}</p>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Delivery Address</h4>
                    <div class="text-sm space-y-1">
                        <p class="font-medium">{{ $order->deliveryAddress->address_label }}</p>
                        <p>{{ $order->deliveryAddress->address_line1 }}</p>
                        <p>{{ $order->deliveryAddress->district->name }}</p>
                        @if($order->deliveryAddress->delivery_notes)
                            <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                                <p class="text-yellow-800"><span class="font-medium">Delivery Notes:</span> {{ $order->deliveryAddress->delivery_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Pickup Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Pickup Locations
            </h3>
            
            @foreach($vendorGroups as $vendorId => $items)
                @php $vendor = $items->first()->product->vendor; @endphp
                <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $vendor->vendor_name }}</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><span class="font-medium">Stall:</span> {{ $vendor->stall_number ?? 'N/A' }}</p>
                                <p><span class="font-medium">Section:</span> {{ $vendor->market_section ?? 'N/A' }}</p>
                                @if($vendor->public_contact_number)
                                    <p><span class="font-medium">Contact:</span> 
                                        <a href="tel:{{ $vendor->public_contact_number }}" class="text-blue-600 hover:underline">
                                            {{ $vendor->public_contact_number }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $items->count() }} item{{ $items->count() > 1 ? 's' : '' }}
                        </span>
                    </div>
                    
                    <!-- Items from this vendor -->
                    <div class="space-y-2">
                        @foreach($items as $item)
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-sm">{{ $item->product_name_snapshot }}</p>
                                    <div class="text-xs text-gray-600">
                                        <span>Qty: {{ $item->quantity_requested }} {{ $item->product->unit }}</span>
                                        @if($item->customer_budget_requested)
                                            <span class="ml-2">Budget: ₱{{ number_format($item->customer_budget_requested, 2) }}</span>
                                        @endif
                                    </div>
                                    @if($item->customerNotes_snapshot)
                                        <p class="text-xs text-gray-500 mt-1">Note: {{ $item->customerNotes_snapshot }}</p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded
                                    {{ $item->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                </svg>
                Payment Information
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-medium">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Status:</span>
                        <span class="px-2 py-1 rounded text-sm font-medium
                            {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Your Delivery Fee:</span>
                        <span class="font-medium text-green-600">₱{{ number_format($order->delivery_fee, 2) }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Total:</span>
                        <span class="font-medium">₱{{ number_format($order->final_total_amount, 2) }}</span>
                    </div>
                    @if($order->payment_method === 'cod')
                        <div class="p-3 bg-orange-50 border border-orange-200 rounded">
                            <p class="text-sm text-orange-800">
                                <span class="font-medium">Cash on Delivery:</span> 
                                You need to collect ₱{{ number_format($order->final_total_amount, 2) }} from the customer.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Special Instructions -->
        @if($order->special_instructions)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Special Instructions
            </h3>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-gray-800">{{ $order->special_instructions }}</p>
            </div>
        </div>
        @endif

        <!-- Order Status History -->
        @if($order->statusHistory->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Order Timeline
            </h3>
            <div class="space-y-4">
                @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                <div class="flex space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                    </div>
                    <div class="flex-1">
                                                    <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800">{{ strtoupper(str_replace('_', ' ', $history->status)) }}</p>
                                @if($history->notes)
                                    <p class="text-sm text-gray-600">{{ $history->notes }}</p>
                                @endif
                                @if($history->updatedBy)
                                    <p class="text-xs text-gray-500">
                                        Updated by: {{ $history->updatedBy->first_name }} {{ $history->updatedBy->last_name }}
                                    </p>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ $history->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF token for AJAX requests
            const csrfToken = '{{ csrf_token() }}';

            // Show/hide loading overlay
            function showLoading() {
                document.getElementById('loading-overlay').classList.remove('hidden');
            }

            function hideLoading() {
                document.getElementById('loading-overlay').classList.add('hidden');
            }

            // Handle order acceptance
            const acceptBtn = document.getElementById('accept-order-btn');
            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    
                    if (confirm('Are you sure you want to accept this delivery?')) {
                        showLoading();
                        
                        fetch(`/rider/orders/${orderId}/accept`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to accept order.');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            }

            // Handle order decline
            const declineBtn = document.getElementById('decline-order-btn');
            if (declineBtn) {
                declineBtn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    
                    if (confirm('Are you sure you want to decline this delivery? It will be assigned to another rider.')) {
                        showLoading();
                        
                        fetch(`/rider/orders/${orderId}/decline`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            
                            if (data.success) {
                                alert(data.message);
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    window.location.href = '{{ route("rider.orders.index") }}';
                                }
                            } else {
                                alert(data.message || 'Failed to decline order.');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            }

            // Handle pickup confirmation (assigned -> pickup_confirmed)
            const pickupBtn = document.getElementById('confirm-pickup-btn');
            if (pickupBtn) {
                pickupBtn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    
                    if (confirm('Confirm that you have picked up all items from the vendor(s)?')) {
                        showLoading();
                        
                        fetch(`/rider/orders/${orderId}/pickup`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to confirm pickup.');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            }

            // Handle start delivery (pickup_confirmed -> out_for_delivery)
            const startDeliveryBtn = document.getElementById('start-delivery-btn');
            if (startDeliveryBtn) {
                startDeliveryBtn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    
                    if (confirm('Start delivery and mark order as out for delivery?')) {
                        showLoading();
                        
                        fetch(`/rider/orders/${orderId}/start-delivery`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to start delivery.');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            }

            // Handle delivery confirmation
            const deliveredBtn = document.getElementById('mark-delivered-btn');
            if (deliveredBtn) {
                deliveredBtn.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const paymentMethod = '{{ $order->payment_method }}';
                    
                    let confirmMessage = 'Confirm that you have successfully delivered this order to the customer?';
                    if (paymentMethod === 'cod') {
                        confirmMessage = 'Confirm that you have delivered the order and collected ₱{{ number_format($order->final_total_amount, 2) }} from the customer?';
                    }
                    
                    if (confirm(confirmMessage)) {
                        showLoading();
                        
                        fetch(`/rider/orders/${orderId}/delivered`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            
                            if (data.success) {
                                alert(data.message);
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    window.location.href = '{{ route("rider.orders.index") }}';
                                }
                            } else {
                                alert(data.message || 'Failed to mark order as delivered.');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            }
        });
    </script>
@endsection