@extends('layout.vendor')

@section('title', 'Order Details #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="flex items-center space-x-4 mb-2">
                        <a href="{{ route('vendor.orders.index') }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            ← Back to Orders
                        </a>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->id }}</h2>
                    <p class="text-gray-600">Manage your items in this order</p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <span class="px-4 py-2 text-sm font-medium rounded-lg
                        @if($order->status === 'processing') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'awaiting_rider_assignment') bg-blue-100 text-blue-800
                        @elseif($order->status === 'out_for_delivery') bg-purple-100 text-purple-800
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800">Your Items to Fulfill</h3>
                        <div class="flex items-center space-x-4">
                            <button type="button" id="bulk-ready-btn" 
                                    class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    disabled>
                                <span class="btn-loading hidden">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <span class="btn-text">Mark Selected as Ready</span>
                            </button>
                            <div class="text-sm text-gray-600">
                                <span id="selected-count">0</span> selected
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                            <div class="border border-gray-200 rounded-lg p-4" data-item-id="{{ $item->id }}">
                                <!-- Item Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-start space-x-3">
                                        <input type="checkbox" 
                                               class="item-checkbox mt-1"
                                               data-item-id="{{ $item->id }}"
                                               @if($item->status === 'ready_for_pickup') disabled @endif>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-800">{{ $item->product_name_snapshot }}</h4>
                                            <div class="text-sm text-gray-600 mt-1">
                                                <p>Requested Quantity: {{ $item->quantity_requested }} {{ $item->product->unit }}</p>
                                                @if($item->customer_budget_requested)
                                                    <p>Customer Budget: ₱{{ number_format($item->customer_budget_requested, 2) }}</p>
                                                @else
                                                    <p>Unit Price: ₱{{ number_format($item->unit_price_snapshot, 2) }}</p>
                                                @endif
                                                @if($item->customerNotes_snapshot)
                                                    <p class="text-blue-600"><strong>Customer Notes:</strong> {{ $item->customerNotes_snapshot }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <select class="status-select px-3 py-1 border border-gray-300 rounded-md text-sm"
                                                data-item-id="{{ $item->id }}" 
                                                data-original-value="{{ $item->status }}">
                                            <option value="pending" @if($item->status === 'pending') selected @endif>Pending</option>
                                            <option value="preparing" @if($item->status === 'preparing') selected @endif>Preparing</option>
                                            <option value="ready_for_pickup" @if($item->status === 'ready_for_pickup') selected @endif>Ready for Pickup</option>
                                        </select>
                                        <span class="status-indicator w-3 h-3 rounded-full
                                            @if($item->status === 'ready_for_pickup') bg-green-500
                                            @elseif($item->status === 'preparing') bg-blue-500
                                            @else bg-yellow-400
                                            @endif">
                                        </span>
                                    </div>
                                </div>

                                <!-- Item Details Form -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    @if($item->product->is_budget_based)
                                        <!-- Budget-based item fields -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Description</label>
                                            <input type="text" 
                                                   class="vendor-quantity-desc w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                   value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                   placeholder="e.g., 1.25 kg, 3 pieces">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Actual Price</label>
                                            <input type="number" 
                                                   class="actual-price w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-value="{{ $item->actual_item_price ?? '' }}"
                                                   value="{{ $item->actual_item_price ?? '' }}"
                                                   step="0.01"
                                                   min="0"
                                                   placeholder="₱0.00">
                                        </div>
                                    @else
                                        <!-- Regular item fields -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Description (Optional)</label>
                                            <input type="text" 
                                                   class="vendor-quantity-desc w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                   value="{{ $item->vendor_assigned_quantity_description ?? '' }}"
                                                   placeholder="Additional quantity details">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Final Price</label>
                                            <input type="number" 
                                                   class="actual-price w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                                   data-item-id="{{ $item->id }}"
                                                   data-original-value="{{ $item->actual_item_price ?? $item->unit_price_snapshot * $item->quantity_requested }}"
                                                   value="{{ $item->actual_item_price ?? $item->unit_price_snapshot * $item->quantity_requested }}"
                                                   step="0.01"
                                                   min="0"
                                                   placeholder="₱0.00">
                                        </div>
                                    @endif
                                </div>

                                <!-- Fulfillment Notes -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fulfillment Notes</label>
                                    <textarea class="fulfillment-notes w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                              data-item-id="{{ $item->id }}"
                                              data-original-value="{{ $item->vendor_fulfillment_notes ?? '' }}"
                                              rows="2"
                                              placeholder="Any notes about this item...">{{ $item->vendor_fulfillment_notes ?? '' }}</textarea>
                                </div>

                                <!-- Update Button -->
                                <div class="flex justify-between items-center">
                                    <div class="text-sm">
                                        <span class="changes-indicator text-amber-600 hidden">
                                            Unsaved changes
                                        </span>
                                    </div>
                                    <button type="button" 
                                            class="update-item-btn px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400"
                                            data-item-id="{{ $item->id }}"
                                            disabled>
                                        <span class="btn-loading hidden">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                        <span class="btn-text">Update Item</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Information Sidebar -->
            <div class="lg:col-span-1">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Customer Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Name</p>
                            <p class="text-sm text-gray-900">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Email</p>
                            <p class="text-sm text-gray-900">{{ $order->customer->email }}</p>
                        </div>
                        @if($order->customer->phone_number)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Phone</p>
                                <p class="text-sm text-gray-900">{{ $order->customer->phone_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Delivery Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Address</p>
                            <p class="text-sm text-gray-900">{{ $order->deliveryAddress->address_line1 }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">District</p>
                            <p class="text-sm text-gray-900">{{ $order->deliveryAddress->district->name }}</p>
                        </div>
                        @if($order->deliveryAddress->delivery_notes)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Delivery Notes</p>
                                <p class="text-sm text-gray-900">{{ $order->deliveryAddress->delivery_notes }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-700">Delivery Fee</p>
                            <p class="text-sm text-gray-900">₱{{ number_format($order->delivery_fee, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Order Date</p>
                            <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Payment Method</p>
                            <p class="text-sm text-gray-900">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Payment Status</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        @if($order->special_instructions)
                            <div>
                                <p class="text-sm font-medium text-gray-700">Special Instructions</p>
                                <p class="text-sm text-gray-900">{{ $order->special_instructions }}</p>
                            </div>
                        @endif
                        <div class="border-t pt-3">
                            <p class="text-sm font-medium text-gray-700">Total Amount</p>
                            <p class="text-lg font-bold text-gray-900">₱{{ number_format($order->final_total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 hidden transition-all duration-300">
        <div id="toast-content" class="flex items-center space-x-3">
            <div id="toast-icon"></div>
            <span id="toast-message"></span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedItems = new Set();

            // CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle checkbox selection
            $('.item-checkbox').on('change', function() {
                const itemId = $(this).data('item-id');
                
                if ($(this).is(':checked')) {
                    selectedItems.add(itemId);
                } else {
                    selectedItems.delete(itemId);
                }
                
                updateBulkButton();
            });

            // Update bulk button state
            function updateBulkButton() {
                const count = selectedItems.size;
                $('#selected-count').text(count);
                $('#bulk-ready-btn').prop('disabled', count === 0);
            }

            // Handle bulk ready action
            $('#bulk-ready-btn').on('click', function() {
                if (selectedItems.size === 0) return;

                const $btn = $(this);
                const $btnText = $btn.find('.btn-text');
                const $btnLoading = $btn.find('.btn-loading');

                // Disable button and show loading
                $btn.prop('disabled', true);
                $btnText.hide();
                $btnLoading.removeClass('hidden');

                // Prepare data
                const itemIds = Array.from(selectedItems);

                $.ajax({
                    url: '{{ route("vendor.orders.items.bulk_ready") }}',
                    method: 'POST',
                    data: {
                        order_item_ids: itemIds
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);
                            
                            // Update UI for each item
                            itemIds.forEach(function(itemId) {
                                const $container = $(`[data-item-id="${itemId}"]`);
                                const $select = $container.find('.status-select');
                                const $indicator = $container.find('.status-indicator');
                                const $checkbox = $container.find('.item-checkbox');
                                
                                // Update status
                                $select.val('ready_for_pickup').attr('data-original-value', 'ready_for_pickup');
                                
                                // Update indicator
                                $indicator.removeClass('bg-yellow-400 bg-blue-500').addClass('bg-green-500');
                                
                                // Disable checkbox
                                $checkbox.prop('disabled', true).prop('checked', false);
                                
                                // Clear from selected items
                                selectedItems.delete(itemId);
                            });
                            
                            updateBulkButton();
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to update items. Please try again.';
                        showToast('error', message);
                    },
                    complete: function() {
                        // Re-enable button and hide loading
                        $btn.prop('disabled', false);
                        $btnText.show();
                        $btnLoading.addClass('hidden');
                    }
                });
            });

            // Handle status dropdown changes
            $('.status-select').on('change', function() {
                const itemId = $(this).data('item-id');
                const newStatus = $(this).val();
                const originalStatus = $(this).attr('data-original-value');
                const itemContainer = $(`[data-item-id="${itemId}"]`);
                
                // Update status indicator immediately for better UX
                updateStatusIndicator(itemContainer, newStatus);
                
                // Check if there are changes and enable/disable update button
                checkForChanges(itemId);
            });

            // Handle input field changes
            $('.vendor-quantity-desc, .actual-price, .fulfillment-notes').on('input', function() {
                const itemId = $(this).data('item-id');
                checkForChanges(itemId);
            });

            // Check for changes in item data
            function checkForChanges(itemId) {
                const $container = $(`[data-item-id="${itemId}"]`);
                const $updateBtn = $container.find('.update-item-btn');
                const $changesIndicator = $container.find('.changes-indicator');
                
                const $status = $container.find('.status-select');
                const $quantityDesc = $container.find('.vendor-quantity-desc');
                const $price = $container.find('.actual-price');
                const $notes = $container.find('.fulfillment-notes');
                
                const hasChanges = 
                    $status.val() !== $status.attr('data-original-value') ||
                    $quantityDesc.val() !== $quantityDesc.attr('data-original-value') ||
                    $price.val() !== $price.attr('data-original-value') ||
                    $notes.val() !== $notes.attr('data-original-value');
                
                $updateBtn.prop('disabled', !hasChanges);
                
                if (hasChanges) {
                    $changesIndicator.removeClass('hidden');
                } else {
                    $changesIndicator.addClass('hidden');
                }
            }

            // Update status indicator visual
            function updateStatusIndicator($container, status) {
                const $indicator = $container.find('.status-indicator');
                $indicator.removeClass('bg-yellow-400 bg-blue-500 bg-green-500');
                
                if (status === 'ready_for_pickup') {
                    $indicator.addClass('bg-green-500');
                } else if (status === 'preparing') {
                    $indicator.addClass('bg-blue-500');
                } else {
                    $indicator.addClass('bg-yellow-400');
                }
            }

            // Handle individual item updates
            $('.update-item-btn').on('click', function() {
                const itemId = $(this).data('item-id');
                const $container = $(`[data-item-id="${itemId}"]`);
                const $btn = $(this);
                const $btnText = $btn.find('.btn-text');
                const $btnLoading = $btn.find('.btn-loading');

                // Collect form data
                const data = {
                    status: $container.find('.status-select').val(),
                    vendor_assigned_quantity_description: $container.find('.vendor-quantity-desc').val(),
                    actual_item_price: $container.find('.actual-price').val(),
                    vendor_fulfillment_notes: $container.find('.fulfillment-notes').val()
                };

                // Disable button and show loading
                $btn.prop('disabled', true);
                $btnText.hide();
                $btnLoading.removeClass('hidden');

                $.ajax({
                    url: `/vendor/orders/items/${itemId}`,
                    method: 'PATCH',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            showToast('success', response.message);
                            
                            // Update original values to current values
                            $container.find('.status-select').attr('data-original-value', data.status);
                            $container.find('.vendor-quantity-desc').attr('data-original-value', data.vendor_assigned_quantity_description);
                            $container.find('.actual-price').attr('data-original-value', data.actual_item_price);
                            $container.find('.fulfillment-notes').attr('data-original-value', data.vendor_fulfillment_notes);
                            
                            // Hide changes indicator
                            $container.find('.changes-indicator').addClass('hidden');
                            
                            // If status is ready_for_pickup, disable checkbox
                            if (data.status === 'ready_for_pickup') {
                                $container.find('.item-checkbox').prop('disabled', true).prop('checked', false);
                                selectedItems.delete(itemId);
                                updateBulkButton();
                            }
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to update item. Please try again.';
                        showToast('error', message);
                    },
                    complete: function() {
                        // Re-enable button and hide loading
                        $btn.prop('disabled', false);
                        $btnText.show();
                        $btnLoading.addClass('hidden');
                    }
                });
            });

            // Toast notification function
            function showToast(type, message) {
                const $toast = $('#toast');
                const $toastIcon = $('#toast-icon');
                const $toastMessage = $('#toast-message');

                // Reset classes
                $toast.removeClass('bg-green-100 bg-red-100 border-green-400 border-red-400 text-green-700 text-red-700');
                $toastIcon.empty();

                if (type === 'success') {
                    $toast.addClass('bg-green-100 border border-green-400 text-green-700');
                    $toastIcon.html('<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>');
                } else {
                    $toast.addClass('bg-red-100 border border-red-400 text-red-700');
                    $toastIcon.html('<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>');
                }

                $toastMessage.text(message);
                $toast.removeClass('hidden');

                // Auto hide after 5 seconds
                setTimeout(() => {
                    $toast.addClass('hidden');
                }, 5000);
            }

            // Display any server-side flash messages
            @if(session('success'))
                showToast('success', '{{ session("success") }}');
            @endif

            @if(session('error'))
                showToast('error', '{{ session("error") }}');
            @endif
        });
    </script>
@endsection