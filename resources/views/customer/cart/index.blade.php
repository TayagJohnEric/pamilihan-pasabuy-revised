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

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if($cartItems->count() > 0)
                <div class="space-y-6">
                    @foreach($cartItems as $item)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col lg:flex-row gap-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <img  src="{{ asset('storage/' . $item->product->image_url) }}"
                                         alt="{{ $item->product->product_name }}" 
                                         class="w-24 h-24 object-cover rounded-lg">
                                </div>

                                <!-- Product Details -->
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                        <div class="flex-grow">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                {{ $item->product->product_name }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mt-1">
                                                {{ Str::limit($item->product->description, 100) }}
                                            </p>
                                            
                                            @if($item->product->is_budget_based)
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Budget-Based Item
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Price/Budget Info -->
                                        <div class="text-right">
                                            @if($item->product->is_budget_based)
                                                <div class="text-lg font-bold text-green-600">
                                                    ₱{{ number_format($item->customer_budget, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">Your Budget</div>
                                            @else
                                                <div class="text-lg font-bold text-green-600">
                                                    ₱{{ number_format($item->product->price * $item->quantity, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ₱{{ number_format($item->product->price, 2) }} × {{ $item->quantity }}
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
                                                @if($item->product->is_budget_based)
                                                    <!-- Budget-based item form -->
                                                    <div class="flex-grow space-y-3">
                                                        <div>
                                                            <label for="budget-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                Budget Amount
                                                            </label>
                                                            <div class="relative">
                                                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                                                <input type="number" 
                                                                       id="budget-{{ $item->id }}"
                                                                       name="customer_budget" 
                                                                       value="{{ $item->customer_budget }}" 
                                                                       step="0.01" 
                                                                       min="0.01"
                                                                       class="pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent w-full sm:w-32">
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <label for="notes-{{ $item->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                                Notes (Optional)
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
                                                        <input type="number" 
                                                               id="quantity-{{ $item->id }}"
                                                               name="quantity" 
                                                               value="{{ $item->quantity }}" 
                                                               min="1" 
                                                               max="{{ $item->product->quantity_in_stock }}"
                                                               class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                    </div>
                                                @endif

                                                <!-- Action Buttons -->
                                                <div class="flex gap-2">
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

                                    @if($item->customer_notes && !$item->product->is_budget_based)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-700">
                                                <span class="font-medium">Notes:</span> {{ $item->customer_notes }}
                                            </p>
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
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-800">
                                Total: <span class="text-green-600">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <p class="text-gray-600 text-sm mt-1">
                                {{ $cartItems->count() }} item(s) in cart
                            </p>
                        </div>

                        <div class="flex gap-3">
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
                    <a href="#" 
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

$(document).ready(function () {
    // For standard product (non-budget based)
    $('input[name="quantity"]').on('change', function (e) {
        e.preventDefault(); // ✅ Add this line correctly inside the function

        let input = $(this);
        let quantity = input.val();
        let cartItemId = input.attr('id').split('-')[1];

        $.ajax({
            url: `/cart/${cartItemId}`,
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Failed to update quantity.');
            }
        });
    });

    // For budget-based product
    $('input[name="customer_budget"], textarea[name="customer_notes"]').on('change', function (e) {
        e.preventDefault(); // ✅ Add this line correctly

        let input = $(this);
        let cartItemId = input.attr('id').split('-')[1];
        let budgetInput = $(`#budget-${cartItemId}`);
        let notesInput = $(`#notes-${cartItemId}`);

        $.ajax({
            url: `/cart/${cartItemId}`,
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                customer_budget: budgetInput.val(),
                customer_notes: notesInput.val(),
            },
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Failed to update budget or notes.');
            }
        });
    });
});

</script>
@endsection