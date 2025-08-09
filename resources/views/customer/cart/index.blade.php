@extends('layout.customer')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Shopping Cart</h1>
            <p class="text-green-100 mt-1">Review and manage your selected items</p>
        </div>

        <!-- Cart Content -->
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if($cartItems->count() > 0)
                <div class="space-y-6">
                    @foreach($cartItems as $item)
                        @php
                            $isBudgetBased = !is_null($item->customer_budget);
                        @endphp
                        
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col lg:flex-row gap-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($item->product->image_url)
                                        <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                             alt="{{ $item->product->product_name }}" 
                                             class="w-24 h-24 object-cover rounded-lg">
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                        <div class="flex-grow">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ $item->product->product_name }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mt-1">
                                                by {{ $item->product->vendor->vendor_name }}
                                            </p>
                                            @if($item->product->description)
                                                <p class="text-gray-600 text-sm mt-1">
                                                    {{ Str::limit($item->product->description, 100) }}
                                                </p>
                                            @endif
                                            
                                            <div class="mt-2">
                                                @if($isBudgetBased)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Budget-Based Purchase
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Standard Purchase
                                                    </span>
                                                @endif
                                                
                                                @if($item->product->is_budget_based && !$isBudgetBased)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ml-2">
                                                        Budget Option Available
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Price/Budget Info -->
                                        <div class="text-right">
                                            @if($isBudgetBased)
                                                <div class="text-lg font-bold text-blue-600">
                                                    ₱{{ number_format($item->customer_budget, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">Your Budget</div>
                                            @else
                                                <div class="text-lg font-bold text-green-600">
                                                    ₱{{ number_format($item->subtotal, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ₱{{ number_format($item->product->price, 2) }} × {{ $item->quantity }} {{ $item->product->unit }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Edit Form -->
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="space-y-4">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="flex flex-col sm:flex-row gap-4 items-start">
                                                @if($isBudgetBased)
                                                    <!-- Budget-based item form -->
                                                    <div class="flex-grow space-y-3">
                                                        <div>
                                                            <label for="budget-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                Budget Amount <span class="text-red-500">*</span>
                                                            </label>
                                                            <div class="relative">
                                                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                                                <input type="number" 
                                                                       id="budget-{{ $item->id }}"
                                                                       name="customer_budget" 
                                                                       value="{{ $item->customer_budget }}" 
                                                                       step="0.01" 
                                                                       min="0.01"
                                                                       max="999999.99"
                                                                       required
                                                                       class="pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent w-full sm:w-40">
                                                            </div>
                                                            @if($item->product->indicative_price_per_unit)
                                                                <p class="text-xs text-gray-500 mt-1">
                                                                    Indicative: ~₱{{ number_format($item->product->indicative_price_per_unit, 2) }}/{{ $item->product->unit }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        
                                                        <div>
                                                            <label for="notes-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                Special Notes (Optional)
                                                            </label>
                                                            <textarea id="notes-{{ $item->id }}"
                                                                      name="customer_notes" 
                                                                      rows="2"
                                                                      placeholder="Special requests or preferences..."
                                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none">{{ $item->customer_notes }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Standard item form -->
                                                    <div>
                                                        <label for="quantity-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                            Quantity
                                                        </label>
                                                        <div class="flex items-center border border-gray-300 rounded-lg">
                                                            <button type="button" 
                                                                    onclick="decreaseCartQuantity({{ $item->id }})"
                                                                    class="px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-l-lg">
                                                                -
                                                            </button>
                                                            <input type="number" 
                                                                   id="quantity-{{ $item->id }}"
                                                                   name="quantity" 
                                                                   value="{{ $item->quantity }}" 
                                                                   min="1" 
                                                                   max="{{ $item->product->is_budget_based ? 999 : $item->product->quantity_in_stock }}"
                                                                   class="w-16 px-2 py-2 text-center border-0 focus:ring-0 focus:outline-none"
                                                                   data-price="{{ $item->product->price }}"
                                                                   data-item-id="{{ $item->id }}">
                                                            <button type="button" 
                                                                    onclick="increaseCartQuantity({{ $item->id }})"
                                                                    class="px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-r-lg">
                                                                +
                                                            </button>
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ $item->product->is_budget_based ? 'Available' : $item->product->quantity_in_stock . ' available' }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <!-- Action Buttons -->
                                                <div class="flex gap-2">
                                                    <button type="submit"
                                                            class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors text-sm">
                                                        Update
                                                    </button>
                                                    <button type="button" 
                                                            onclick="removeItem({{ $item->id }})"
                                                            class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors text-sm">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- Hidden Remove Form -->
                                        <form id="remove-form-{{ $item->id }}" method="POST" action="{{ route('cart.destroy', $item) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>

                                    <!-- Display notes for budget-based items -->
                                    @if($isBudgetBased && $item->customer_notes)
                                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                            <p class="text-sm text-blue-700">
                                                <span class="font-medium">Special Notes:</span> {{ $item->customer_notes }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Convert to Budget Option (for budget-based products purchased at original price) -->
                                    @if($item->product->is_budget_based && !$isBudgetBased)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm text-gray-700 font-medium">Want a custom quote instead?</p>
                                                    <p class="text-xs text-gray-600">Switch to budget-based pricing for this item</p>
                                                </div>
                                                <button type="button" 
                                                        onclick="showBudgetConversion({{ $item->id }})"
                                                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded font-medium hover:bg-blue-200 transition-colors text-sm">
                                                    Switch to Budget
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Hidden Budget Conversion Form -->
                                        <div id="budget-conversion-{{ $item->id }}" class="hidden mt-3 p-3 bg-blue-50 rounded-lg">
                                            <form method="POST" action="{{ route('cart.update', $item) }}" class="space-y-3">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Your Budget <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="relative">
                                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                                        <input type="number" 
                                                               name="customer_budget" 
                                                               step="0.01" 
                                                               min="0.01"
                                                               max="999999.99"
                                                               required
                                                               placeholder="Enter your budget"
                                                               class="pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full">
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Special Notes (Optional)
                                                    </label>
                                                    <textarea name="customer_notes" 
                                                              rows="2"
                                                              placeholder="Any special requirements..."
                                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                                                </div>
                                                
                                                <div class="flex gap-2">
                                                    <button type="submit" 
                                                            class="px-3 py-1 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 transition-colors text-sm">
                                                        Convert to Budget
                                                    </button>
                                                    <button type="button" 
                                                            onclick="hideBudgetConversion({{ $item->id }})"
                                                            class="px-3 py-1 border border-gray-300 text-gray-700 rounded font-medium hover:bg-gray-50 transition-colors text-sm">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-left sm:text-right order-2 sm:order-1">
                            <div class="text-2xl font-bold text-gray-800">
                                Total: <span class="text-green-600">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $itemCount }} item(s) • {{ $totalQuantity }} total pieces
                            </p>
                            @if($cartItems->where('customer_budget', '!=', null)->count() > 0)
                                <p class="text-blue-600 text-xs mt-1">
                                    * Budget-based items subject to vendor confirmation
                                </p>
                            @endif
                        </div>

                        <div class="flex gap-3 order-1 sm:order-2">
                            <button onclick="clearCart()" 
                                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Clear Cart
                            </button>
                            
                            <a href="#" 
                               class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Hidden Clear Cart Form -->
                <form id="clear-cart-form" method="POST" action="{{ route('cart.clear') }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            @else
                <!-- Empty Cart -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0H9m4 0h2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-800 mb-2">Your cart is empty</h3>
                    <p class="text-gray-600 mb-6">Add some items to get started with your shopping.</p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function removeItem(itemId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            document.getElementById('remove-form-' + itemId).submit();
        }
    }

    function clearCart() {
        if (confirm('Are you sure you want to clear your entire cart? This action cannot be undone.')) {
            document.getElementById('clear-cart-form').submit();
        }
    }

    function showBudgetConversion(itemId) {
        document.getElementById('budget-conversion-' + itemId).classList.remove('hidden');
    }

    function hideBudgetConversion(itemId) {
        document.getElementById('budget-conversion-' + itemId).classList.add('hidden');
    }

    function increaseCartQuantity(itemId) {
        const input = document.getElementById('quantity-' + itemId);
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);

        if (current < max) {
            input.value = current + 1;
            updateCartItemTotal(itemId);
        }
    }

    function decreaseCartQuantity(itemId) {
        const input = document.getElementById('quantity-' + itemId);
        const current = parseInt(input.value);

        if (current > 1) {
            input.value = current - 1;
            updateCartItemTotal(itemId);
        }
    }

    function updateCartItemTotal(itemId) {
        const quantityInput = document.getElementById('quantity-' + itemId);
        if (quantityInput) {
            // Auto-submit the form after a short delay
            clearTimeout(window.updateTimeout);
            window.updateTimeout = setTimeout(function() {
                quantityInput.closest('form').submit();
            }, 1000);
        }
    }

    $(document).ready(function () {
        // For standard product (non-budget based) - auto-update on quantity change
        $('input[name="quantity"]').on('change', function (e) {
            let input = $(this);
            let quantity = input.val();
            let cartItemId = input.data('item-id') || input.attr('id').split('-')[1];

            // Clear any pending timeout
            clearTimeout(window.updateTimeout);

            $.ajax({
                url: `/cart/${cartItemId}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    quantity: quantity
                },
                success: function (response) {
                    // Show success message without full page reload
                    showSuccessMessage('Cart updated successfully');
                    // Update the total display
                    location.reload();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = Object.values(errors).flat().join('\n');
                        alert('Validation Error:\n' + errorMessage);
                    } else {
                        alert(xhr.responseJSON?.message || 'Failed to update quantity.');
                    }
                    // Reset to previous value on error
                    input.val(input.data('previous-value') || 1);
                }
            });
        });

        // Handle budget conversion form submission
        $('form').on('submit', function(e) {
            const budgetInput = $(this).find('input[name="customer_budget"]');
            const isBudgetConversion = $(this).closest('[id^="budget-conversion-"]').length > 0;
            
            if (budgetInput.length > 0) {
                const value = parseFloat(budgetInput.val());
                const min = parseFloat(budgetInput.attr('min'));
                const max = parseFloat(budgetInput.attr('max'));
                
                if (isNaN(value) || value < min || value > max) {
                    e.preventDefault();
                    alert('Please enter a valid budget amount between ₱' + min.toLocaleString() + ' and ₱' + max.toLocaleString());
                    budgetInput.focus();
                    return false;
                }
            }
            
            // If this is a budget conversion form, handle it specially
            if (isBudgetConversion) {
                e.preventDefault();
                
                const form = $(this);
                const formData = new FormData(form[0]);
                const cartItemId = form.closest('[id^="budget-conversion-"]').attr('id').split('-')[2];
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        showSuccessMessage('Item converted to budget-based pricing successfully!');
                        // Reload the page to show the updated cart
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = Object.values(errors).flat().join('\n');
                            alert('Validation Error:\n' + errorMessage);
                        } else {
                            alert(xhr.responseJSON?.message || 'Failed to convert item to budget-based pricing.');
                        }
                    }
                });
                
                return false;
            }
        });

        // Store previous values for error handling
        $('input[name="quantity"]').each(function() {
            $(this).data('previous-value', $(this).val());
        });

        $('input[name="quantity"]').on('focus', function() {
            $(this).data('previous-value', $(this).val());
        });
    });

    function showSuccessMessage(message) {
        // Create a temporary success message
        const alertDiv = $('<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 fixed top-4 right-4 z-50">' + message + '</div>');
        $('body').append(alertDiv);
        
        // Remove after 3 seconds
        setTimeout(function() {
            alertDiv.fadeOut(500, function() {
                alertDiv.remove();
            });
        }, 3000);
    }
</script>
@endsection