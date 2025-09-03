@extends('layout.vendor')

@section('title', 'Order Details - Order #' . $orderInfo->order_id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Header Section with Back Button -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('vendor.orders.new') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Orders
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Order #{{ $orderInfo->order_id }}</h2>
                        <p class="text-gray-600">
                            Ordered on {{ \Carbon\Carbon::parse($orderInfo->order_date)->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                </div>
                
                <!-- Order Status Badges -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($orderInfo->order_status === 'processing')
                            bg-blue-100 text-blue-800
                        @elseif($orderInfo->order_status === 'pending_payment')
                            bg-yellow-100 text-yellow-800
                        @elseif($orderInfo->order_status === 'delivered')
                            bg-green-100 text-green-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $orderInfo->order_status)) }}
                    </span>
                    
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($orderInfo->payment_status === 'paid')
                            bg-green-100 text-green-800
                        @elseif($orderInfo->payment_status === 'pending')
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-red-100 text-red-800
                        @endif">
                        Payment: {{ ucfirst($orderInfo->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content - Order Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Your Items in This Order</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ count($orderDetails) }} item(s) to fulfill</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        @foreach($orderDetails as $item)
                            <div class="border border-gray-200 rounded-lg p-4 
                                @if($item->status === 'preparing') border-blue-300 bg-blue-50
                                @elseif($item->status === 'ready_for_pickup') border-green-300 bg-green-50
                                @endif">
                                
                                <div class="flex flex-col md:flex-row gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product_image)
                                            <img src="{{ $item->product_image }}" 
                                                 alt="{{ $item->product_name_snapshot }}"
                                                 class="w-20 h-20 object-cover rounded-lg border">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded-lg border flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Information -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                                    {{ $item->product_name_snapshot }}
                                                </h4>
                                                
                                                <!-- Status Badge -->
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mb-2
                                                    @if($item->status === 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($item->status === 'preparing')
                                                        bg-blue-100 text-blue-800
                                                    @elseif($item->status === 'ready_for_pickup')
                                                        bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                </span>
                                                
                                                @if($item->is_budget_based)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                                                        Budget-Based Item
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Item Details Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                            <div class="space-y-2">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Requested Quantity:</span>
                                                    <span class="font-medium">{{ $item->quantity_requested }} {{ $item->unit }}</span>
                                                </div>
                                                
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Unit Price (Snapshot):</span>
                                                    <span class="font-medium">₱{{ number_format($item->unit_price_snapshot, 2) }}</span>
                                                </div>
                                                
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Current Product Price:</span>
                                                    <span class="font-medium">₱{{ number_format($item->current_product_price, 2) }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-2">
                                                @if($item->customer_budget_requested > 0)
                                                    <div class="flex justify-between">
                                                        <span class="text-yellow-600 font-medium">Customer Budget:</span>
                                                        <span class="font-semibold text-yellow-700">₱{{ number_format($item->customer_budget_requested, 2) }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if($item->actual_item_price)
                                                    <div class="flex justify-between">
                                                        <span class="text-green-600">Your Price:</span>
                                                        <span class="font-semibold text-green-700">₱{{ number_format($item->actual_item_price, 2) }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if($item->vendor_assigned_quantity_description)
                                                    <div class="flex justify-between">
                                                        <span class="text-blue-600">Your Quantity:</span>
                                                        <span class="font-medium text-blue-700">{{ $item->vendor_assigned_quantity_description }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Customer Notes -->
                                        @if($item->customerNotes_snapshot)
                                            <div class="mt-4 p-3 bg-blue-50 rounded-md border border-blue-200">
                                                <div class="text-sm font-medium text-blue-800 mb-1">Customer Notes:</div>
                                                <div class="text-sm text-blue-700">{{ $item->customerNotes_snapshot }}</div>
                                            </div>
                                        @endif
                                        
                                        <!-- Vendor Notes -->
                                        @if($item->vendor_fulfillment_notes)
                                            <div class="mt-4 p-3 bg-green-50 rounded-md border border-green-200">
                                                <div class="text-sm font-medium text-green-800 mb-1">Your Notes:</div>
                                                <div class="text-sm text-green-700">{{ $item->vendor_fulfillment_notes }}</div>
                                            </div>
                                        @endif
                                        
                                        <!-- Substitution Info -->
                                        @if($item->is_substituted)
                                            <div class="mt-4 p-3 bg-orange-50 rounded-md border border-orange-200">
                                                <div class="text-sm font-medium text-orange-800 mb-1">Item Substituted</div>
                                                <div class="text-sm text-orange-700">This item has been substituted with an alternative product.</div>
                                            </div>
                                        @endif
                                        
                                        <!-- Action Buttons -->
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @if($item->status === 'pending')
                                                <button onclick="acknowledgeItem({{ $item->id }})"
                                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                                    Mark as Preparing
                                                </button>
                                            @elseif($item->status === 'preparing')
                                                <button onclick="markAsReady({{ $item->id }})"
                                                        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                                                    Mark as Ready
                                                </button>
                                            @endif
                                            
                                            <button onclick="addNotes({{ $item->id }})" 
                                                    class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                                Add Notes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Sidebar - Order Information -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Customer Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-gray-600">Name</div>
                                <div class="text-gray-800">{{ $orderInfo->customer_first_name }} {{ $orderInfo->customer_last_name }}</div>
                            </div>
                            
                            @if($orderInfo->customer_email)
                                <div>
                                    <div class="text-sm font-medium text-gray-600">Email</div>
                                    <div class="text-gray-800">{{ $orderInfo->customer_email }}</div>
                                </div>
                            @endif
                            
                            @if($orderInfo->customer_phone)
                                <div>
                                    <div class="text-sm font-medium text-gray-600">Phone</div>
                                    <div class="text-gray-800">{{ $orderInfo->customer_phone }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Delivery Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-gray-600">Address</div>
                                <div class="text-gray-800">
                                    {{ $orderInfo->address_line1 }}<br>
                                    {{ $orderInfo->district_name }}
                                </div>
                                @if($orderInfo->address_label)
                                    <div class="mt-1">
                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">
                                            {{ $orderInfo->address_label }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($orderInfo->delivery_notes)
                                <div>
                                    <div class="text-sm font-medium text-gray-600">Delivery Notes</div>
                                    <div class="text-gray-800">{{ $orderInfo->delivery_notes }}</div>
                                </div>
                            @endif
                            
                            @if($orderInfo->special_instructions)
                                <div>
                                    <div class="text-sm font-medium text-gray-600">Special Instructions</div>
                                    <div class="text-gray-800">{{ $orderInfo->special_instructions }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Payment Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-medium">
                                    {{ $orderInfo->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status</span>
                                <span class="font-medium
                                    @if($orderInfo->payment_status === 'paid') text-green-600
                                    @elseif($orderInfo->payment_status === 'pending') text-yellow-600
                                    @else text-red-600
                                    @endif">
                                    {{ ucfirst($orderInfo->payment_status) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Fee</span>
                                <span class="font-medium">₱{{ number_format($orderInfo->delivery_fee, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-3 mt-3">
                                <span>Total Order Amount</span>
                                <span>₱{{ number_format($orderInfo->final_total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
            <span id="notification-message"></span>
        </div>
    </div>
    
    <!-- Notes Modal -->
    <div id="notesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Fulfillment Notes</h3>
                <textarea id="notesTextarea" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          rows="4" 
                          placeholder="Enter any notes about how you'll fulfill this item..."></textarea>
                <div class="mt-4 flex justify-end space-x-3">
                    <button onclick="closeNotesModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button onclick="saveNotes()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Notes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentOrderItemId = null;
        
        /**
         * Acknowledge an order item and mark it as preparing
         */
        function acknowledgeItem(orderItemId) {
            updateItemStatus(orderItemId, 'preparing', 'Mark as Preparing');
        }
        
        /**
         * Mark an order item as ready for pickup
         */
        function markAsReady(orderItemId) {
            updateItemStatus(orderItemId, 'ready_for_pickup', 'Mark as Ready');
        }
        
        /**
         * Update order item status
         */
        function updateItemStatus(orderItemId, status, buttonText) {
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
                data: { status: status },
                success: function(response) {
                    showNotification(response.message, 'success');
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
                    button.disabled = false;
                    button.textContent = originalText;
                }
            });
        }
        
        /**
         * Open notes modal for adding fulfillment notes
         */
        function addNotes(orderItemId) {
            currentOrderItemId = orderItemId;
            document.getElementById('notesModal').classList.remove('hidden');
            document.getElementById('notesTextarea').focus();
        }
        
        /**
         * Close notes modal
         */
        function closeNotesModal() {
            document.getElementById('notesModal').classList.add('hidden');
            document.getElementById('notesTextarea').value = '';
            currentOrderItemId = null;
        }
        
        /**
         * Save fulfillment notes
         */
        function saveNotes() {
            const notes = document.getElementById('notesTextarea').value.trim();
            
            if (!notes) {
                showNotification('Please enter some notes', 'error');
                return;
            }
            
            if (!currentOrderItemId) {
                showNotification('Invalid order item', 'error');
                return;
            }
            
            $.ajax({
                url: `/vendor/orders/items/${currentOrderItemId}/notes`, // You'll need to add this route
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { 
                    notes: notes
                },
                success: function(response) {
                    showNotification('Notes saved successfully', 'success');
                    closeNotesModal();
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to save notes';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification(errorMessage, 'error');
                }
            });
        }
        
        /**
         * Show notification message
         */
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notification-message');
            const notificationDiv = notification.querySelector('div');
            
            notificationMessage.textContent = message;
            
            if (type === 'success') {
                notificationDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg';
            } else {
                notificationDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg';
            }
            
            notification.classList.remove('hidden');
            
            setTimeout(function() {
                notification.classList.add('hidden');
            }, 4000);
        }
        
        // Close modal when clicking outside
        document.getElementById('notesModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNotesModal();
            }
        });
        
        // Handle escape key for modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeNotesModal();
            }
        });
    </script>
@endsection