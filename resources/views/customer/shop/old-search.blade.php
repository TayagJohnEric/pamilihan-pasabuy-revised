@extends('layout.customer')
@section('title', 'Search Results for "' . $searchTerm . '"')
@section('content')
<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-green-600">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-500">Search Results</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Search Header -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg shadow-lg mb-8 p-6 sm:p-8 text-white">
        <div class="max-w-3xl">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">Search Results</h1>
            <p class="text-lg sm:text-xl mb-6 text-purple-100">
                Results for: <span class="font-semibold">"{{ $searchTerm }}"</span>
            </p>
            
            <!-- Search Bar -->
            <form method="GET" action="{{ route('products.search') }}" class="flex flex-col sm:flex-row gap-2 max-w-2xl">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $searchTerm }}"
                        placeholder="Search for products, vendors, categories..." 
                        class="w-full px-4 py-3 rounded-lg text-gray-900 border-0 focus:ring-2 focus:ring-purple-300"
                    >
                </div>
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200"
                >
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Category Links -->
    @if($categories->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Browse by Category:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($categories->take(8) as $category)
                    <a 
                        href="{{ route('products.category', $category->id) }}" 
                        class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full hover:bg-purple-100 hover:text-purple-700 transition-colors text-sm"
                    >
                        {{ $category->category_name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Search Results -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Search Results</h2>
                <p class="text-gray-600">
                    @if($products->total() > 0)
                        {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found for "{{ $searchTerm }}"
                    @else
                        No products found for "{{ $searchTerm }}"
                    @endif
                </p>
            </div>
            
            @if($products->total() > 0)
                <div class="hidden sm:flex items-center text-sm text-gray-500">
                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} results
                </div>
            @endif
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                        <div class="aspect-square relative overflow-hidden bg-gray-50">
                            @if($product->image_url)
                                <img 
                                     src="{{ asset('storage/' . $product->image_url) }}"
                                    alt="{{ $product->product_name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif

                            @if($product->is_budget_based)
                                <span class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                    Budget Option
                                </span>
                            @endif

                            {{-- Only show stock badges for non-budget-based products or when using regular pricing --}}
                            @if(!$product->is_budget_based)
                                @if($product->quantity_in_stock <= 5 && $product->quantity_in_stock > 0)
                                    <span class="absolute top-2 right-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                                        Low Stock
                                    </span>
                                @elseif($product->quantity_in_stock == 0)
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                        Out of Stock
                                    </span>
                                @endif
                            @endif

                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-300"></div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('products.show', $product->id) }}" class="hover:text-purple-600 transition-colors">
                                    {{ $product->product_name }}
                                </a>
                            </h3>
                            
                            <div class="text-sm text-gray-600 mb-3 space-y-1">
                                <p>
                                    by <a href="{{ route('products.vendor', $product->vendor->id) }}" class="text-purple-600 hover:underline font-medium">
                                        {{ $product->vendor->vendor_name }}
                                    </a>
                                </p>
                            </div>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-lg font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                                    <span class="text-sm text-gray-500">/ {{ $product->unit }}</span>
                                    @if($product->is_budget_based && $product->indicative_price_per_unit)
                                        <div class="text-xs text-gray-500">
                                            ~₱{{ number_format($product->indicative_price_per_unit, 2) }}/{{ $product->unit }}
                                        </div>
                                    @endif
                                </div>
                                @if($product->vendor->average_rating)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ number_format($product->vendor->average_rating, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Stock information display --}}
                            <div class="text-sm mb-3">
                                @if(!$product->is_budget_based)
                                    @if($product->quantity_in_stock > 0)
                                        <span class="text-green-600 font-semibold">In Stock: {{ $product->quantity_in_stock }}</span>
                                    @else
                                        <span class="text-red-500 font-semibold">Out of Stock</span>
                                    @endif
                                @else
                                    @if($product->is_available)
                                        <span class="text-green-600 font-semibold">In Stock</span>
                                    @else
                                        <span class="text-red-500 font-semibold">Currently Unavailable</span>
                                    @endif
                                @endif
                            </div>
                            
                            <div>
                                <!-- Unified Add to Cart Section -->
                                <div class="add-to-cart-section">
                                    @php
                                        $isInStock = $product->is_budget_based ? $product->is_available : ($product->quantity_in_stock > 0);
                                        $maxQuantity = $product->is_budget_based ? 999 : $product->quantity_in_stock;
                                        $availableText = $product->is_budget_based ? 'available' : $product->quantity_in_stock . ' available';
                                    @endphp
                                    
                                    @if($isInStock)
                                        <!-- Regular Purchase Form (Default) -->
                                        <form method="POST" action="{{ route('cart.store') }}" class="regular-form-{{ $product->id }} space-y-4">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                                            <button type="submit" 
                                                    class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors focus:ring-4 focus:ring-green-200">
                                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0H9m4 0h2"></path>
                                                </svg>
                                                Add to Cart
                                            </button>
                                        </form>

                                        <!-- Budget-Based Option Toggle (only for budget-based products) -->
                                        @if($product->is_budget_based)
                                            <div class="mt-3">
                                                <button type="button" 
                                                        onclick="toggleBudgetForm({{ $product->id }})"
                                                        class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                    Set Custom Budget
                                                </button>
                                            </div>

                                            <!-- Budget-Based Form (Hidden by default) -->
                                            <form method="POST" action="{{ route('cart.store') }}" class="budget-form-{{ $product->id }} space-y-4 hidden">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                
                                                <div class="space-y-3">
                                                    <div>
                                                        <label for="budget-{{ $product->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                            Budget Amount <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                                            <input type="number" 
                                                                   id="budget-{{ $product->id }}"
                                                                   name="customer_budget" 
                                                                   value="{{ old('customer_budget') }}"
                                                                   step="0.01" 
                                                                   min="0.01"
                                                                   max="999999.99"
                                                                   class="pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full"
                                                                   placeholder="0.00">
                                                        </div>
                                                        @if($product->indicative_price_per_unit)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Indicative price: ~₱{{ number_format($product->indicative_price_per_unit, 2) }}/{{ $product->unit }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex gap-2">
                                                    <button type="button" 
                                                            onclick="toggleBudgetForm({{ $product->id }})"
                                                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" 
                                                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                                        Add to Cart
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    @else
                                        <div class="text-center py-4">
                                            <button class="w-full px-4 py-3 bg-gray-300 text-gray-500 rounded-lg font-semibold cursor-not-allowed" disabled>
                                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                </svg>
                                                @if($product->is_budget_based)
                                                    Currently Unavailable
                                                @else
                                                    Out of Stock
                                                @endif
                                            </button>
                                            <p class="text-sm text-gray-500 mt-2">
                                                @if($product->is_budget_based)
                                                    This service is temporarily unavailable
                                                @else
                                                    This item is currently unavailable
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="mx-auto mb-4 w-16 h-16 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h3m4 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-lg">No products found matching your search.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleBudgetForm(productId) {
        const regularForm = document.querySelector(`.regular-form-${productId}`);
        const budgetForm = document.querySelector(`.budget-form-${productId}`);
        
        if (regularForm.classList.contains('hidden')) {
            // Show regular form, hide budget form
            regularForm.classList.remove('hidden');
            budgetForm.classList.add('hidden');
        } else {
            // Show budget form, hide regular form
            regularForm.classList.add('hidden');
            budgetForm.classList.remove('hidden');
            
            // Focus on budget input
            const budgetInput = document.getElementById(`budget-${productId}`);
            if (budgetInput) {
                budgetInput.focus();
            }
        }
    }

    function increaseQuantity(productId) {
        const input = document.getElementById('quantity-' + productId);
        if (!input) return;
        
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);

        if (current < max) {
            input.value = current + 1;
            updateTotalPrice(productId);
        }
    }

    function decreaseQuantity(productId) {
        const input = document.getElementById('quantity-' + productId);
        if (!input) return;
        
        const current = parseInt(input.value);

        if (current > 1) {
            input.value = current - 1;
            updateTotalPrice(productId);
        }
    }

    function updateTotalPrice(productId) {
        const quantityInput = document.getElementById('quantity-' + productId);
        const totalElement = document.getElementById('total-price-' + productId);

        if (quantityInput && totalElement) {
            const quantity = parseInt(quantityInput.value);
            const unitPrice = parseFloat(quantityInput.getAttribute('data-price')) || 0;
            const total = quantity * unitPrice;

            totalElement.textContent = '₱' + total.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }

    function validateBudgetInput(input) {
        const value = parseFloat(input.value);
        const min = parseFloat(input.getAttribute('min'));
        const max = parseFloat(input.getAttribute('max'));
        
        if (value < min) {
            input.value = min;
        } else if (value > max) {
            input.value = max;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Handle quantity inputs for standard products
        const quantityInputs = document.querySelectorAll('input[name="quantity"]');
        quantityInputs.forEach(function(input) {
            const productId = input.id.split('-')[1];
            input.addEventListener('input', function() {
                updateTotalPrice(productId);
            });
        });

        // Handle budget inputs for budget-based products
        const budgetInputs = document.querySelectorAll('input[name="customer_budget"]');
        budgetInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                validateBudgetInput(this);
            });
            
            input.addEventListener('blur', function() {
                validateBudgetInput(this);
            });
        });

        // Handle form submission validation
        const budgetForms = document.querySelectorAll('form[class*="budget-form"]');
        budgetForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const budgetInput = form.querySelector('input[name="customer_budget"]');
                if (budgetInput) {
                    const value = parseFloat(budgetInput.value);
                    const min = parseFloat(budgetInput.getAttribute('min'));
                    const max = parseFloat(budgetInput.getAttribute('max'));
                    
                    if (isNaN(value) || value < min || value > max) {
                        e.preventDefault();
                        alert('Please enter a valid budget amount between ₱' + min.toLocaleString() + ' and ₱' + max.toLocaleString());
                        budgetInput.focus();
                        return false;
                    }
                }
            });
        });
    });
</script>

@endsection