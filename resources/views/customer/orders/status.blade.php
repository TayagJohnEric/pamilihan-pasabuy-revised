@extends('layout.customer')

@section('title', 'Order Status - Order #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Order Header -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Order #{{ $order->id }}</h2>
                        <p class="text-gray-600">Placed on {{ $order->order_date->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <!-- Status Badge -->
                        <div class="flex items-center space-x-3">
                            <span id="order-status-badge" class="
                                @switch($order->status)
                                    @case('pending_payment')
                                        bg-yellow-100 text-yellow-800
                                        @break
                                    @case('processing')
                                        bg-blue-100 text-blue-800
                                        @break
                                    @case('awaiting_rider_assignment')
                                        bg-purple-100 text-purple-800
                                        @break
                                    @case('out_for_delivery')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('delivered')
                                        bg-green-100 text-green-800
                                        @break
                                    @case('cancelled')
                                        bg-red-100 text-red-800
                                        @break
                                    @case('failed')
                                        bg-red-100 text-red-800
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800
                                @endswitch
                                px-3 py-1 rounded-full text-sm font-medium capitalize">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                            
                            <!-- Payment Status -->
                            <span class="
                                @if($order->payment_status === 'paid')
                                    bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif
                                px-3 py-1 rounded-full text-sm font-medium">
                                Payment: {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Status Updates -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Progress</h3>
                
                <!-- Progress Indicator -->
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <!-- Step 1: Payment -->
                        <div class="flex flex-col items-center text-center">
                            <div id="step-payment" class="
                                @if(in_array($order->status, ['processing', 'awaiting_rider_assignment', 'out_for_delivery', 'delivered']))
                                    bg-green-500
                                @elseif($order->status === 'pending_payment')
                                    bg-blue-500
                                @else
                                    bg-gray-300
                                @endif
                                w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                @if(in_array($order->status, ['processing', 'awaiting_rider_assignment', 'out_for_delivery', 'delivered']))
                                    ✓
                                @else
                                    1
                                @endif
                            </div>
                            <span class="text-xs mt-2 font-medium text-gray-600">Payment</span>
                        </div>

                        <!-- Step 2: Preparation -->
                        <div class="flex flex-col items-center text-center">
                            <div id="step-preparation" class="
                                @if(in_array($order->status, ['awaiting_rider_assignment', 'out_for_delivery', 'delivered']))
                                    bg-green-500
                                @elseif($order->status === 'processing')
                                    bg-blue-500
                                @else
                                    bg-gray-300
                                @endif
                                w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                @if(in_array($order->status, ['awaiting_rider_assignment', 'out_for_delivery', 'delivered']))
                                    ✓
                                @elseif($order->status === 'processing')
                                    <div class="animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></div>
                                @else
                                    2
                                @endif
                            </div>
                            <span class="text-xs mt-2 font-medium text-gray-600">Preparation</span>
                        </div>

                        <!-- Step 3: Rider Assignment -->
                        <div class="flex flex-col items-center text-center">
                            <div id="step-rider" class="
                                @if(in_array($order->status, ['out_for_delivery', 'delivered']))
                                    bg-green-500
                                @elseif($order->status === 'awaiting_rider_assignment')
                                    bg-blue-500
                                @else
                                    bg-gray-300
                                @endif
                                w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                @if(in_array($order->status, ['out_for_delivery', 'delivered']))
                                    ✓
                                @elseif($order->status === 'awaiting_rider_assignment')
                                    <div class="animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></div>
                                @else
                                    3
                                @endif
                            </div>
                            <span class="text-xs mt-2 font-medium text-gray-600">Rider Assignment</span>
                        </div>

                        <!-- Step 4: Delivery -->
                        <div class="flex flex-col items-center text-center">
                            <div id="step-delivery" class="
                                @if($order->status === 'delivered')
                                    bg-green-500
                                @elseif($order->status === 'out_for_delivery')
                                    bg-blue-500
                                @else
                                    bg-gray-300
                                @endif
                                w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                @if($order->status === 'delivered')
                                    ✓
                                @elseif($order->status === 'out_for_delivery')
                                    <div class="animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></div>
                                @else
                                    4
                                @endif
                            </div>
                            <span class="text-xs mt-2 font-medium text-gray-600">Delivery</span>
                        </div>
                    </div>

                    <!-- Progress Line -->
                    <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-300 -z-10">
                        <div id="progress-line" class="h-full bg-green-500 transition-all duration-500" style="width: 
                            @switch($order->status)
                                @case('pending_payment')
                                    0%
                                    @break
                                @case('processing')
                                    33%
                                    @break
                                @case('awaiting_rider_assignment')
                                    66%
                                    @break
                                @case('out_for_delivery')
                                @case('delivered')
                                    100%
                                    @break
                                @default
                                    0%
                            @endswitch
                        "></div>
                    </div>
                </div>

                <!-- Status Message -->
                <div id="status-message" class="mt-6 p-4 rounded-lg bg-blue-50 border border-blue-200">
                    <p class="text-blue-800 font-medium">
                        @switch($order->status)
                            @case('pending_payment')
                                Confirming your payment...
                                @break
                            @case('processing')
                                Your order is being prepared by our vendors.
                                @break
                            @case('awaiting_rider_assignment')
                                Finding an available rider for your delivery.
                                @break
                            @case('out_for_delivery')
                                Your order is on the way!
                                @break
                            @case('delivered')
                                Your order has been delivered successfully.
                                @break
                            @case('cancelled')
                                This order has been cancelled.
                                @break
                            @case('failed')
                                There was an issue processing your order. Please contact support.
                                @break
                            @default
                                Processing your order...
                        @endswitch
                    </p>
                    
                    <!-- Items Progress for Processing Status -->
                    @if($order->status === 'processing')
                        <div class="mt-3">
                            <div class="flex justify-between text-sm text-blue-700">
                                <span>Items prepared:</span>
                                <span id="items-progress">
                                    {{ $order->orderItems->where('status', 'ready_for_pickup')->count() }} 
                                    of {{ $order->orderItems->count() }}
                                </span>
                            </div>
                            <div class="mt-1 w-full bg-blue-200 rounded-full h-2">
                                <div id="items-progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $order->orderItems->count() > 0 ? ($order->orderItems->where('status', 'ready_for_pickup')->count() / $order->orderItems->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rider Information (if assigned) -->
            @if($order->rider_user_id)
                <div class="mb-8 p-6 bg-green-50 rounded-lg border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Your Rider
                    </h3>
                    <div id="rider-info" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="font-medium text-gray-800">
                                {{ $order->rider->user->first_name }} {{ $order->rider->user->last_name }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $order->rider->vehicle_type ?? 'Delivery Vehicle' }}</p>
                            @if($order->rider->average_rating)
                                <div class="flex items-center mt-1">
                                    <span class="text-yellow-400">★</span>
                                    <span class="text-sm text-gray-600 ml-1">{{ number_format($order->rider->average_rating, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            @if($order->rider->user->phone_number)
                                <p class="text-sm text-gray-600">Contact: {{ $order->rider->user->phone_number }}</p>
                            @endif
                            <p class="text-sm text-gray-600">Total Deliveries: {{ $order->rider->total_deliveries ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Order Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Order Items -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800">{{ $item->product_name_snapshot }}</h4>
                                        <p class="text-sm text-gray-600">
                                            Vendor: {{ $item->product->vendor->vendor_name ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <span class="text-sm px-2 py-1 rounded-full
                                        @if($item->status === 'ready_for_pickup')
                                            bg-green-100 text-green-800
                                        @elseif($item->status === 'preparing')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                </div>
                                
                                <div class="text-sm text-gray-600 space-y-1">
                                    @if($item->customer_budget_requested)
                                        <p>Budget: ₱{{ number_format($item->customer_budget_requested, 2) }}</p>
                                    @else
                                        <p>Quantity: {{ $item->quantity_requested }} × ₱{{ number_format($item->unit_price_snapshot, 2) }}</p>
                                    @endif
                                    
                                    @if($item->customerNotes_snapshot)
                                        <p class="italic">Note: {{ $item->customerNotes_snapshot }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-medium">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery Fee:</span>
                            <span class="font-medium">₱{{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Amount:</span>
                                <span>₱{{ number_format($order->final_total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-800 mb-2">Delivery Address</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-800">{{ $order->deliveryAddress->full_address ?? 'Address not available' }}</p>
                            @if($order->special_instructions)
                                <p class="text-sm text-gray-600 mt-2 italic">
                                    Special Instructions: {{ $order->special_instructions }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order History</h3>
                <div class="space-y-3">
                    @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $history->status) }}</p>
                                @if($history->notes)
                                    <p class="text-sm text-gray-600">{{ $history->notes }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $history->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-refresh Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let refreshInterval;
            
            // Only auto-refresh for active statuses
            const activeStatuses = ['pending_payment', 'processing', 'awaiting_rider_assignment', 'out_for_delivery'];
            const currentStatus = '{{ $order->status }}';
            
            if (activeStatuses.includes(currentStatus)) {
                startAutoRefresh();
            }

            function startAutoRefresh() {
                refreshInterval = setInterval(function() {
                    fetchOrderStatus();
                }, 10000); // Refresh every 10 seconds
            }

            function stopAutoRefresh() {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }
            }

            function fetchOrderStatus() {
                fetch(`/orders/{{ $order->id }}/status-update`)
                    .then(response => response.json())
                    .then(data => {
                        updateOrderStatus(data);
                    })
                    .catch(error => {
                        console.error('Error fetching order status:', error);
                    });
            }

            function updateOrderStatus(data) {
                // Update status badge
                const statusBadge = document.getElementById('order-status-badge');
                if (statusBadge) {
                    statusBadge.textContent = data.status.replace('_', ' ');
                    statusBadge.className = getStatusBadgeClass(data.status);
                }

                // Update progress steps
                updateProgressSteps(data.status);

                // Update status message
                updateStatusMessage(data.status, data);

                // Update rider info if available
                if (data.rider) {
                    updateRiderInfo(data.rider);
                }

                // Update items progress
                if (data.status === 'processing') {
                    updateItemsProgress(data.items_ready_count, data.total_items_count);
                }

                // Stop auto-refresh if order is completed or failed
                if (['delivered', 'cancelled', 'failed'].includes(data.status)) {
                    stopAutoRefresh();
                    // Reload page to show final state
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            }

            function getStatusBadgeClass(status) {
                const baseClasses = 'px-3 py-1 rounded-full text-sm font-medium capitalize ';
                const statusClasses = {
                    'pending_payment': 'bg-yellow-100 text-yellow-800',
                    'processing': 'bg-blue-100 text-blue-800',
                    'awaiting_rider_assignment': 'bg-purple-100 text-purple-800',
                    'out_for_delivery': 'bg-green-100 text-green-800',
                    'delivered': 'bg-green-100 text-green-800',
                    'cancelled': 'bg-red-100 text-red-800',
                    'failed': 'bg-red-100 text-red-800'
                };
                return baseClasses + (statusClasses[status] || 'bg-gray-100 text-gray-800');
            }

            function updateProgressSteps(status) {
                const steps = ['payment', 'preparation', 'rider', 'delivery'];
                const statusToStep = {
                    'pending_payment': 0,
                    'processing': 1,
                    'awaiting_rider_assignment': 2,
                    'out_for_delivery': 3,
                    'delivered': 4
                };

                const currentStep = statusToStep[status] || 0;
                const progressPercentage = currentStep * 33.33;

                // Update progress line
                const progressLine = document.getElementById('progress-line');
                if (progressLine) {
                    progressLine.style.width = Math.min(progressPercentage, 100) + '%';
                }

                // Update step indicators
                steps.forEach((step, index) => {
                    const element = document.getElementById(`step-${step}`);
                    if (element) {
                        if (index < currentStep) {
                            // Completed step
                            element.className = 'bg-green-500 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold';
                            element.innerHTML = '✓';
                        } else if (index === currentStep && status !== 'delivered') {
                            // Current active step
                            element.className = 'bg-blue-500 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold';
                            element.innerHTML = '<div class="animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></div>';
                        } else {
                            // Future step
                            element.className = 'bg-gray-300 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold';
                            element.innerHTML = index + 1;
                        }
                    }
                });
            }

            function updateStatusMessage(status, data) {
                const messageElement = document.getElementById('status-message');
                const messages = {
                    'pending_payment': 'Confirming your payment...',
                    'processing': 'Your order is being prepared by our vendors.',
                    'awaiting_rider_assignment': 'Finding an available rider for your delivery.',
                    'out_for_delivery': 'Your order is on the way!',
                    'delivered': 'Your order has been delivered successfully.',
                    'cancelled': 'This order has been cancelled.',
                    'failed': 'There was an issue processing your order. Please contact support.'
                };

                if (messageElement) {
                    const messageText = messageElement.querySelector('p');
                    if (messageText) {
                        messageText.textContent = messages[status] || 'Processing your order...';
                    }
                }
            }

            function updateItemsProgress(readyCount, totalCount) {
                const progressText = document.getElementById('items-progress');
                const progressBar = document.getElementById('items-progress-bar');
                
                if (progressText) {
                    progressText.textContent = `${readyCount} of ${totalCount}`;
                }
                
                if (progressBar && totalCount > 0) {
                    const percentage = (readyCount / totalCount) * 100;
                    progressBar.style.width = percentage + '%';
                }
            }

            function updateRiderInfo(rider) {
                const riderInfoElement = document.getElementById('rider-info');
                if (riderInfoElement && rider) {
                    // Create rider info section if not exists
                    if (!document.querySelector('.rider-section')) {
                        const riderSection = document.createElement('div');
                        riderSection.className = 'rider-section mb-8 p-6 bg-green-50 rounded-lg border border-green-200';
                        riderSection.innerHTML = `
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Your Rider
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="font-medium text-gray-800">${rider.name}</p>
                                    <p class="text-sm text-gray-600">${rider.vehicle_type || 'Delivery Vehicle'}</p>
                                    ${rider.rating ? `<div class="flex items-center mt-1"><span class="text-yellow-400">★</span><span class="text-sm text-gray-600 ml-1">${rider.rating}</span></div>` : ''}
                                </div>
                                <div>
                                    ${rider.phone ? `<p class="text-sm text-gray-600">Contact: ${rider.phone}</p>` : ''}
                                </div>
                            </div>
                        `;
                        
                        // Insert before order details
                        const orderDetailsSection = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-2');
                        orderDetailsSection.parentNode.insertBefore(riderSection, orderDetailsSection);
                    }
                }
            }
        });
    </script>
@endsection