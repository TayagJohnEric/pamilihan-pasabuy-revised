@extends('layout.customer')
@section('title', $product->product_name . ' - ' . $product->vendor->vendor_name)
@section('content')
<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-green-600">Home</a>
            </li>
            @if($product->category)
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('products.category', $product->category->id) }}" class="text-gray-600 hover:text-green-600">
                        {{ $product->category->category_name }}
                    </a>
                </div>
            </li>
            @endif
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-500">{{ $product->product_name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="lg:flex">
            <!-- Product Image -->
            <div class="lg:w-1/2">
                <div class="aspect-square relative bg-gray-100">
                    @if($product->image_url)
                        <img 
                             src="{{ asset('storage/' . $product->image_url) }}"
                            alt="{{ $product->product_name }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                    
                    @if($product->is_budget_based)
                        <span class="absolute top-4 left-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                            Budget Option Available
                        </span>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="lg:w-1/2 p-6 lg:p-8">
                <div class="mb-4">
                    @if($product->category)
                        <span class="inline-block bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full">
                            {{ $product->category->category_name }}
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $product->product_name }}</h1>
                
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <span class="text-3xl font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                        <span class="text-lg text-gray-500 ml-2">per {{ $product->unit }}</span>
                    </div>
                    
                    @if($product->is_budget_based && $product->indicative_price_per_unit)
                        <p class="text-sm text-gray-600">
                            Indicative price: ₱{{ number_format($product->indicative_price_per_unit, 2) }} per {{ $product->unit }}
                        </p>
                    @endif
                </div>

                <!-- Vendor Info -->
                <div class="border-t border-b border-gray-200 py-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Sold by</p>
                            <a href="{{ route('products.vendor', $product->vendor->id) }}" class="text-lg font-semibold text-green-600 hover:underline">
                                {{ $product->vendor->vendor_name }}
                            </a>
                            @if($product->vendor->average_rating)
                                <div class="flex items-center mt-1">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $product->vendor->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ number_format($product->vendor->average_rating, 1) }} rating</span>
                                </div>
                            @endif
                        </div>
                        @if($product->vendor->shop_logo_url)
                            <img src="{{ $product->vendor->shop_logo_url }}" alt="{{ $product->vendor->vendor_name }}" class="w-16 h-16 rounded-full object-cover">
                        @endif
                    </div>
                </div>

                <!-- Stock Info -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Availability:</span>
                        @if($product->is_budget_based)
                            <span class="text-sm {{ $product->is_available ? 'text-green-600' : 'text-red-600' }} font-medium">
                                {{ $product->is_available ? 'Available' : 'Currently Unavailable' }}
                            </span>
                        @else
                            <span class="text-sm {{ $product->quantity_in_stock <= 5 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                {{ $product->quantity_in_stock }} {{ $product->unit }} available
                            </span>
                        @endif
                    </div>
                    @if(!$product->is_budget_based && $product->quantity_in_stock <= 5 && $product->quantity_in_stock > 0)
                        <p class="text-sm text-red-600">⚠️ Low stock - order soon!</p>
                    @endif
                </div>

                @if($product->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                <!-- Add to Cart Section -->
                <div class="space-y-4">
                    @php
                        $isInStock = $product->is_budget_based ? $product->is_available : ($product->quantity_in_stock > 0);
                        $maxQuantity = $product->is_budget_based ? 999 : $product->quantity_in_stock;
                    @endphp
                    
                    @if($isInStock)
                        <!-- Regular Purchase Form (Default) -->
                        <div class="regular-form-{{ $product->id }}">
                            <form method="POST" action="{{ route('cart.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <!-- Standard product form -->
                                <div class="flex items-center space-x-4">
                                    <label for="quantity-{{ $product->id }}" class="text-sm font-medium text-gray-700">
                                        Quantity:
                                    </label>
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button type="button" 
                                                onclick="decreaseQuantity({{ $product->id }})" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-l-lg">
                                            -
                                        </button>
                                        <input type="number" 
                                               id="quantity-{{ $product->id }}"
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $maxQuantity }}"
                                               data-price="{{ $product->price }}"
                                               class="w-16 px-2 py-2 text-center border-0 focus:ring-0 focus:outline-none">
                                        <button type="button" 
                                                onclick="increaseQuantity({{ $product->id }})" 
                                                class="px-3 py-2 text-gray-600 hover:bg-gray-50 rounded-r-lg">
                                            +
                                        </button>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        ({{ $product->is_budget_based ? 'available' : $product->quantity_in_stock . ' available' }})
                                    </span>
                                </div>

                                <!-- Price Display -->
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Unit Price:</span>
                                    <span class="font-semibold text-gray-800">₱{{ number_format($product->price, 2) }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Total:</span>
                                    <span id="total-price-{{ $product->id }}" class="font-bold text-green-600">
                                        ₱{{ number_format($product->price, 2) }}
                                    </span>
                                </div>

                                <button type="submit" 
                                        class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 focus:ring-4 focus:ring-green-200">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0H9m4 0h2"></path>
                                    </svg>
                                    Add to Cart
                                </button>
                            </form>
                        </div>

                        <!-- Budget-Based Option Toggle (only for budget-based products) -->
                        @if($product->is_budget_based)
                            <div class="pt-4 border-t border-gray-200">
                                <div class="budget-toggle-{{ $product->id }}">
                                    <button type="button" 
                                            onclick="toggleBudgetForm({{ $product->id }})"
                                            class="w-full px-6 py-3 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition-colors">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        Set Custom Budget Instead
                                    </button>
                                    <p class="text-xs text-gray-600 text-center mt-2">
                                        Get a custom quote based on your specific budget and requirements
                                    </p>
                                </div>

                                <!-- Budget-Based Form (Hidden by default) -->
                                <div class="budget-form-{{ $product->id }} hidden">
                                    <form method="POST" action="{{ route('cart.store') }}" class="space-y-4 mt-4">
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
                                                           required
                                                           class="pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent w-full"
                                                           placeholder="0.00">
                                                </div>
                                                @if($product->indicative_price_per_unit)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Indicative price: ~₱{{ number_format($product->indicative_price_per_unit, 2) }}/{{ $product->unit }}
                                                    </p>
                                                @endif
                                            </div>
                                            
                                            <div>
                                                <label for="notes-{{ $product->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Notes (Optional)
                                                </label>
                                                <textarea id="notes-{{ $product->id }}"
                                                          name="customer_notes" 
                                                          rows="3"
                                                          placeholder="Special requests or preferences..."
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none">{{ old('customer_notes') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="flex space-x-3">
                                            <button type="button" 
                                                    onclick="toggleBudgetForm({{ $product->id }})"
                                                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                                Back to Standard
                                            </button>
                                            <button type="submit" 
                                                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 focus:ring-4 focus:ring-blue-200">
                                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                Add with Budget
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                    @else
                        <div class="text-center py-4">
                            <button class="w-full bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed" disabled>
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

                <!-- Additional Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @if($product->vendor->stall_number)
                            <div>
                                <span class="text-gray-600">Stall Number:</span>
                                <span class="font-medium">{{ $product->vendor->stall_number }}</span>
                            </div>
                        @endif
                        @if($product->vendor->market_section)
                            <div>
                                <span class="text-gray-600">Market Section:</span>
                                <span class="font-medium">{{ $product->vendor->market_section }}</span>
                            </div>
                        @endif
                        @if($product->vendor->accepts_cod)
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓ Cash on Delivery Available
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Ratings Section -->
    @if($product->vendor && $product->vendor->ratingsReceived && $product->vendor->ratingsReceived->count() > 0)
        <div class="bg-white rounded-lg shadow-lg mt-8 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Customer Reviews for {{ $product->vendor->vendor_name }}</h3>
            <div class="space-y-4">
                @foreach($product->vendor->ratingsReceived as $rating)
                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $rating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 font-medium">{{ $rating->user ? $rating->user->first_name . ' ' . $rating->user->last_name : 'Anonymous' }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                        </div>
                        @if($rating->comment)
                            <p class="text-gray-700">{{ $rating->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Related Products from Same Vendor -->
    @if($relatedProducts->count() > 0)
        <div class="bg-white rounded-lg shadow-lg mt-8 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">More from {{ $product->vendor->vendor_name }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-square relative bg-gray-100">
                            @if($relatedProduct->image_url)
                                <img src="{{ asset('storage/' . $relatedProduct->image_url) }}" alt="{{ $relatedProduct->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h4 class="font-medium text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('products.show', $relatedProduct->id) }}" class="hover:text-green-600">
                                    {{ $relatedProduct->product_name }}
                                </a>
                            </h4>
                            <p class="text-green-600 font-semibold">₱{{ number_format($relatedProduct->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Similar Products -->
    @if($similarProducts->count() > 0)
        <div class="bg-white rounded-lg shadow-lg mt-8 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Similar Products</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similarProducts as $similarProduct)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        <div class="aspect-square relative bg-gray-100">
                            @if($similarProduct->image_url)
                                <img src="{{ asset('storage/' . $similarProduct->image_url) }}" alt="{{ $similarProduct->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h4 class="font-medium text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('products.show', $similarProduct->id) }}" class="hover:text-green-600">
                                    {{ $similarProduct->product_name }}
                                </a>
                            </h4>
                            <p class="text-sm text-gray-600 mb-1">
                                by <a href="{{ route('products.vendor', $similarProduct->vendor->id) }}" class="text-green-600 hover:underline">
                                    {{ $similarProduct->vendor->vendor_name }}
                                </a>
                            </p>
                            <p class="text-green-600 font-semibold">₱{{ number_format($similarProduct->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    function toggleBudgetForm(productId) {
        const regularForm = document.querySelector('.regular-form-' + productId);
        const budgetForm = document.querySelector('.budget-form-' + productId);
        const budgetToggle = document.querySelector('.budget-toggle-' + productId);

        if (budgetForm.classList.contains('hidden')) {
            // Show budget form, hide regular form and toggle button
            budgetForm.classList.remove('hidden');
            regularForm.classList.add('hidden');
            budgetToggle.classList.add('hidden');
        } else {
            // Show regular form and toggle button, hide budget form
            budgetForm.classList.add('hidden');
            regularForm.classList.remove('hidden');
            budgetToggle.classList.remove('hidden');
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
        const forms = document.querySelectorAll('form[action*="cart"]');
        forms.forEach(function(form) {
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