@extends('layout.vendor')

@section('title', 'New Orders - Vendor Dashboard')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">New Orders</h2>
                    <p class="text-gray-600">Review and manage incoming order requests for your products</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="bg-blue-50 px-4 py-2 rounded-lg text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $totalNewOrders }}</div>
                        <div class="text-sm text-blue-800">New Orders</div>
                    </div>
                    <div class="bg-green-50 px-4 py-2 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $totalItems }}</div>
                        <div class="text-sm text-green-800">Total Items</div>
                    </div>
                    <div class="bg-yellow-50 px-4 py-2 rounded-lg text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $totalBudgetRequests }}</div>
                        <div class="text-sm text-yellow-800">Budget Requests</div>
                    </div>
                </div>
            </div>
        </div>

        @if($groupedOrders->count() > 0)
            <!-- Orders List -->
            <div class="space-y-6">
                @foreach($groupedOrders as $orderId => $orderItems)
                    @php
                        $firstItem = $orderItems->first();
                    @endphp
                    
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                        <!-- Order Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 rounded-t-lg">
                            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            Order #{{ $orderId }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($firstItem->order_date)->format('M d, Y \a\t g:i A') }}
                                        </p>
                                    </div>
                                    
                                    <!-- Customer Info -->
                                    <div class="bg-white px-3 py-2 rounded-md border">
                                        <div class="text-sm font-medium text-gray-800">
                                            {{ $firstItem->customer_first_name }} {{ $firstItem->customer_last_name }}
                                        </div>
                                        @if($firstItem->customer_phone)
                                            <div class="text-sm text-gray-600">{{ $firstItem->customer_phone }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <!-- Payment Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($firstItem->payment_status === 'paid')
                                            bg-green-100 text-green-800
                                        @elseif($firstItem->payment_status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $firstItem->payment_status)) }}
                                    </span>
                                    
                                    <!-- Payment Method Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $firstItem->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($orderItems as $item)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex flex-col lg:flex-row gap-4">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0">
                                                @if($item->product_image)
                                                    <img src="{{ $item->product_image }}" 
                                                         alt="{{ $item->product_name_snapshot }}"
                                                         class="w-16 h-16 object-cover rounded-lg border">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 rounded-lg border flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Product Details -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-gray-800 mb-1">
                                                            {{ $item->product_name_snapshot }}
                                                        </h4>
                                                        
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 text-sm text-gray-600">
                                                            <div>
                                                                <span class="font-medium">Quantity:</span>
                                                                {{ $item->quantity_requested }} {{ $item->unit }}
                                                            </div>
                                                            
                                                            <div>
                                                                <span class="font-medium">Unit Price:</span>
                                                                ₱{{ number_format($item->unit_price_snapshot, 2) }}
                                                            </div>
                                                            
                                                            @if($item->customer_budget_requested > 0)
                                                                <div class="sm:col-span-2">
                                                                    <span class="font-medium text-yellow-600">Customer Budget:</span>
                                                                    <span class="font-semibold text-yellow-700">
                                                                        ₱{{ number_format($item->customer_budget_requested, 2) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        @if($item->customerNotes_snapshot)
                                                            <div class="mt-3 p-3 bg-blue-50 rounded-md border border-blue-200">
                                                                <div class="text-sm font-medium text-blue-800 mb-1">Customer Notes:</div>
                                                                <div class="text-sm text-blue-700">{{ $item->customerNotes_snapshot }}</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Action Buttons -->
                                                    <div class="flex flex-col gap-2">
                                                        @if($item->is_budget_based)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                Budget-Based
                                                            </span>
                                                        @endif
                                                        
                                                        <button onclick="acknowledgeItem({{ $item->order_item_id }})"
                                                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                                            Mark as Preparing
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Delivery Information -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                    <h5 class="font-semibold text-green-800 mb-2">Delivery Information</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <div class="font-medium text-green-700">Address:</div>
                                            <div class="text-green-600">
                                                {{ $firstItem->address_line1 }}<br>
                                                {{ $firstItem->district_name }}
                                                @if($firstItem->address_label)
                                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs ml-2">
                                                        {{ $firstItem->address_label }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($firstItem->delivery_notes || $firstItem->special_instructions)
                                            <div>
                                                <div class="font-medium text-green-700">Special Instructions:</div>
                                                <div class="text-green-600">
                                                    {{ $firstItem->delivery_notes ?: $firstItem->special_instructions }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-4 flex justify-end">
                                <a href="{{ route('vendor.orders.details', $orderId) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                    View Full Details
                                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No New Orders</h3>
                <p class="text-gray-500">You don't have any new orders at the moment. Check back later!</p>
            </div>
        @endif
    </div>

    <!-- Success/Error Messages -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
            <span id="notification-message"></span>
        </div>
    </div>

    <!-- JavaScript for AJAX functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        /**
         * Acknowledge an order item and mark it as preparing
         * @param {number} orderItemId - The ID of the order item to acknowledge
         */
        function acknowledgeItem(orderItemId) {
            // Disable the button to prevent double-clicks
            const button = event.target;
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Processing...';
            
            $.ajax({
                url: '{{ route("vendor.orders.items.acknowledge", ":orderItemId") }}'.replace(':orderItemId', orderItemId),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showNotification(response.message, 'success');
                    button.textContent = 'Acknowledged';
                    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    button.classList.add('bg-green-600', 'cursor-not-allowed');
                    
                    // Optionally reload the page after a short delay to update the list
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification(errorMessage, 'error');
                    
                    // Re-enable the button
                    button.disabled = false;
                    button.textContent = originalText;
                }
            });
        }
        
        /**
         * Show notification message
         * @param {string} message - The message to display
         * @param {string} type - The type of notification (success/error)
         */
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notification-message');
            const notificationDiv = notification.querySelector('div');
            
            notificationMessage.textContent = message;
            
            // Set colors based on type
            if (type === 'success') {
                notificationDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg';
            } else {
                notificationDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg';
            }
            
            notification.classList.remove('hidden');
            
            // Hide after 4 seconds
            setTimeout(function() {
                notification.classList.add('hidden');
            }, 4000);
        }
        
        // Auto-refresh new orders count every 30 seconds
        setInterval(function() {
            $.ajax({
                url: '{{ route("vendor.orders.count") }}',
                type: 'GET',
                success: function(response) {
                    // Update the count in the header if it exists
                    const countElement = document.querySelector('.text-blue-600');
                    if (countElement && response.count !== undefined) {
                        countElement.textContent = response.count;
                    }
                    
                    // Show notification if there are new orders
                    if (response.count > 0 && response.count > {{ $totalNewOrders }}) {
                        showNotification('You have new orders!', 'success');
                    }
                },
                error: function() {
                    console.log('Failed to refresh order count');
                }
            });
        }, 30000); // 30 seconds
        
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection