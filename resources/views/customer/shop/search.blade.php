@extends('layout.customer')
@section('title', 'Search Results for "' . $searchTerm . '"')
@section('content')

<style>
    /* Custom carousel styles */
    .featured-carousel {
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .featured-carousel::-webkit-scrollbar {
        display: none;
    }
    
    .category-carousel {
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .category-carousel::-webkit-scrollbar {
        display: none;
    }
    
    /* Custom animations */
    @keyframes slideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .animate-slide-in {
        animation: slideIn 0.6s ease-out forwards;
    }
    
    /* Enhanced hover effects */
    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: block;
        text-decoration: none;
        color: inherit;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        text-decoration: none;
        color: inherit;
    }
    
    /* Improved focus styles for accessibility */
    .focus-ring:focus {
        outline: 2px solid #10b981;
        outline-offset: 2px;
    }
    
    /* Search input with icon styles */
    .search-input-wrapper {
        position: relative;
    }
    
    .search-input-wrapper input {
        padding-right: 3.5rem;
    }
    
    .search-input-wrapper .search-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .search-input-wrapper .search-icon:hover {
        color: #10b981;
    }
    
    /* Category carousel navigation */
    .carousel-nav-button {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }
    
    .carousel-nav-button:hover {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Mobile adjustments */
    @media (max-width: 640px) {
        .category-item {
            min-width: 120px;
        }
    }
</style>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<div class="animate-slide-in max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-emerald-600 transition-colors duration-200">Home</a>
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
    <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl shadow-lg mb-8 p-6 sm:p-8 text-white overflow-hidden relative">
        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
        <div class="relative max-w-3xl">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 leading-tight">
              Search Results
            </h1>
            <p class="text-md sm:text-lg mb-5 text-emerald-100 max-w-2xl">
                Results for: <span class="font-semibold text-white">"{{ $searchTerm }}"</span>
            </p>
            
            <!-- Enhanced Search Bar with Icon Inside -->
            <form method="GET" action="{{ route('products.search') }}" class="max-w-2xl">
                <div class="search-input-wrapper">
                    <input 
                        id="product-search"
                        type="text" 
                        name="q" 
                        value="{{ $searchTerm }}"
                        placeholder="Search for products, vendors, categories..." 
                        class="w-full px-5 py-4 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-white focus:ring-opacity-30 shadow-lg placeholder-gray-500 text-base focus-ring"
                    >
                    <svg class="search-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
        </div>
    </div>

     <!-- Categories -->
    @if($categories->count() > 0)
        <div class=" mb-7">
             <h3 class="text-lg font-semibold text-gray-800 mb-4">Browse by Category:</h3>
            <div class="relative">
                <div id="category-carousel" class="category-carousel flex gap-3 overflow-x-auto pb-2">
                    @foreach($categories->take(12) as $category)
                        <a href="{{ route('products.category', $category->id) }}" class="flex-none px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm text-gray-600 rounded-full hover:bg-emerald-100 hover:text-emerald-700 transition-all duration-200 text-sm font-medium whitespace-nowrap">
                            {{ $category->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

</div>

<!-- Products Section - Mobile-Optimized -->
<div class="sm:px-6 lg:px-8 animate-slide-in">
    <div class="sm:max-w-[90rem] sm:mx-auto">
        <div class=" p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-1">Search Results</h3>
                    <p class="text-gray-600">
                        @if($products->total() > 0)
                            {{ $products->total() }} {{ Str::plural('product', $products->total()) }} found for "{{ $searchTerm }}"
                        @else
                            No products found for "{{ $searchTerm }}"
                        @endif
                    </p>
                </div>
                
                @if($products->total() > 0)
                    <div class="flex items-center text-sm text-gray-500 bg-gray-50 px-4 py-2 rounded-lg">
                        <span class="font-medium">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} results</span>
                    </div>
                @endif
            </div>

            @if($products->count() > 0)
                <!-- Enhanced Product Grid - Mobile: 2 columns, Tablet: 3 columns, Desktop: 4 columns -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6 mb-8">
                    @foreach($products as $product)
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl hover:border-emerald-200 transition-all duration-300 group">
                            <div class="aspect-square relative overflow-hidden bg-gray-50">
                                @if($product->image_url)
                                    <img 
                                        src="{{ asset('storage/' . $product->image_url) }}"
                                        alt="{{ $product->product_name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Enhanced Badge Styling -->
                                @if($product->is_budget_based)
                                    <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                        Budget Option
                                    </span>
                                @endif

                                @if(!$product->is_budget_based)
                                    @if($product->quantity_in_stock <= 5 && $product->quantity_in_stock > 0)
                                        <span class="absolute top-2 right-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                            Low Stock
                                        </span>
                                    @elseif($product->quantity_in_stock == 0)
                                        <span class="absolute top-2 right-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs px-2 py-1 rounded-full font-medium shadow-lg">
                                            Out of Stock
                                        </span>
                                    @endif
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            
                            <div class="p-3 sm:p-4">
                                <!-- Product Title -->
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 text-sm sm:text-base">
                                    <a href="{{ route('products.show', $product->id) }}" class="hover:text-emerald-600 transition-colors duration-200">
                                        {{ $product->product_name }}
                                    </a>
                                </h3>
                                
                                <!-- Price and Rating Row -->
                                <div class="flex items-center justify-between mb-2 gap-2">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-base sm:text-lg font-bold text-emerald-600 truncate">₱{{ number_format($product->price, 2) }}</span>
                                        </div>
                                    </div>
                                    @if($product->vendor->average_rating)
                                        <div class="flex items-center text-xs text-gray-600 bg-gray-50 px-2 py-1 rounded-full flex-shrink-0">
                                            <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="hidden sm:inline">{{ number_format($product->vendor->average_rating, 1) }}</span>
                                            <span class="sm:hidden">{{ number_format($product->vendor->average_rating, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Enhanced Stock Information -->
                                <div class="text-xs mb-3">
                                    @if(!$product->is_budget_based)
                                        @if($product->quantity_in_stock > 0)
                                            <div class="flex items-center text-emerald-600 font-medium">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="truncate">In Stock: {{ $product->quantity_in_stock }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center text-red-500 font-medium">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Out of Stock</span>
                                            </div>
                                        @endif
                                    @else
                                        @if($product->is_available)
                                            <div class="flex items-center text-emerald-600 font-medium">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Available</span>
                                            </div>
                                        @else
                                            <div class="flex items-center text-red-500 font-medium">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-xs">Currently Unavailable</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                
                                <!-- Enhanced Add to Cart Section -->
                                <div class="add-to-cart-section">
                                    @php
                                        $isInStock = $product->is_budget_based ? $product->is_available : ($product->quantity_in_stock > 0);
                                    @endphp
                                    
                                    @if($isInStock)
                                        <!-- Regular Purchase Form (Default) -->
                                        <form class="regular-form-{{ $product->id }} space-y-2 sm:space-y-3 ajax-cart-form" data-product-id="{{ $product->id }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            
                                            <!-- Quantity Input for non-budget products -->
                                            @if(!$product->is_budget_based)
                                                <div class="flex items-center space-x-2">
                                                    <label for="quantity-{{ $product->id }}" class="text-xs font-medium text-gray-700 flex-shrink-0">Qty:</label>
                                                    <input type="number" 
                                                        id="quantity-{{ $product->id }}"
                                                        name="quantity" 
                                                        value="1"
                                                        min="1" 
                                                        max="{{ $product->quantity_in_stock }}"
                                                        class="flex-1 px-2 py-1.5 sm:px-3 sm:py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-xs sm:text-sm">
                                                </div>
                                            @else
                                                <input type="hidden" name="quantity" value="1">
                                            @endif

                                            <button type="submit" 
                                                    class="w-full px-2 py-2 sm:px-3 sm:py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 focus:ring-4 focus:ring-emerald-200 shadow-lg hover:shadow-xl text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1.5 loading-text-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0H9m4 0h2"></path>
                                                </svg>
                                                <span class="loading-text">Add to Cart</span>
                                                <span class="loading-spinner hidden">
                                                    <svg class="animate-spin w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </form>

                                        <!-- Budget-Based Option Toggle -->
                                        @if($product->is_budget_based)
                                            <div class="mt-1.5 sm:mt-2">
                                                <button type="button" 
                                                        onclick="toggleBudgetForm({{ $product->id }})"
                                                        class="w-full px-2 py-1.5 sm:px-3 sm:py-2 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 transition-colors duration-200 text-xs sm:text-sm border border-blue-200">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                    Set Custom Budget
                                                </button>
                                            </div>

                                            <!-- Budget-Based Form (Hidden by default) -->
                                            <form class="budget-form-{{ $product->id }} space-y-2 sm:space-y-3 hidden ajax-cart-form" data-product-id="{{ $product->id }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                
                                                <div class="space-y-2 sm:space-y-3">
                                                    <div>
                                                        <label for="budget-{{ $product->id }}" class="block text-xs font-medium text-gray-700 mb-1.5">
                                                            Budget Amount <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <span class="absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-xs sm:text-sm">₱</span>
                                                            <input type="number" 
                                                                   id="budget-{{ $product->id }}"
                                                                   name="customer_budget" 
                                                                   value="{{ old('customer_budget') }}"
                                                                   step="0.01" 
                                                                   min="0.01"
                                                                   max="999999.99"
                                                                   class="pl-6 sm:pl-8 pr-3 sm:pr-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full text-xs sm:text-sm"
                                                                   placeholder="0.00">
                                                        </div>
                                                        @if($product->indicative_price_per_unit)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Indicative: ~₱{{ number_format($product->indicative_price_per_unit, 2) }}/{{ $product->unit }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex gap-1.5 sm:gap-2">
                                                    <button type="button" 
                                                            onclick="toggleBudgetForm({{ $product->id }})"
                                                            class="flex-1 px-2 py-1.5 sm:px-3 sm:py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors duration-200 text-xs sm:text-sm">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" 
                                                            class="flex-1 px-2 py-1.5 sm:px-3 sm:py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                                                        <span class="loading-text">Add to Cart</span>
                                                        <span class="loading-spinner hidden">
                                                            <svg class="animate-spin w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    @else
                                        <div class="text-center py-2 sm:py-3">
                                            <button class="w-full px-2 py-2 sm:px-3 sm:py-2.5 bg-gray-200 text-gray-500 rounded-lg font-semibold cursor-not-allowed text-xs sm:text-sm" disabled>
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                </svg>
                                                @if($product->is_budget_based)
                                                    Currently Unavailable
                                                @else
                                                    Out of Stock
                                                @endif
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1.5">
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
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="text-center py-16 text-gray-500">
                    <div class="max-w-md mx-auto">
                        <svg class="mx-auto mb-6 w-20 h-20 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No products found</h3>
                        <p class="text-gray-600 mb-6">We couldn't find any products matching your search for "{{ $searchTerm }}"</p>
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-medium hover:from-emerald-700 hover:to-teal-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0H9m4 0h2"></path>
                            </svg>
                            Browse All Products
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // carousel functionality
    function scrollCarousel(direction, carouselId) {
        const carousel = document.getElementById(carouselId);
        const cardWidth = carouselId === 'featured-carousel' ? 288 + 16 : 120 + 12; // card width + gap
        const scrollAmount = direction === 'left' ? -cardWidth * 2 : cardWidth * 2;
        
        carousel.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
    
    
    // Enhanced search functionality with debouncing
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Optional: implement live search here
            }, 300);
        });
        
        // Submit form when search icon is clicked
        const searchIcon = document.querySelector('.search-icon');
        if (searchIcon) {
            searchIcon.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.closest('form').submit();
            });
        }
    }

    // Toast notification function
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container');
        const toastId = 'toast-' + Date.now();
        
        const iconClass = type === 'success' ? 'fa-check' : 'fa-exclamation-triangle';
        const bgClass = type === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
        const textClass = type === 'success' ? 'text-green-700' : 'text-red-700';
        const closeClass = type === 'success' ? 'text-green-400 hover:text-green-600' : 'text-red-400 hover:text-red-600';
        
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `max-w-md mx-auto rounded-xl border ${bgClass} p-4 flex items-center justify-between shadow-lg transform translate-x-full transition-transform duration-300 ease-out`;
        
        toast.innerHTML = `
            <div class="flex items-center space-x-3 ${textClass} text-sm font-semibold leading-tight">
                <i class="fas ${iconClass} text-base"></i>
                <span>${message}</span>
            </div>
            <button onclick="removeToast('${toastId}')" aria-label="Close" class="${closeClass} focus:outline-none transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        
        // Slide in animation
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            removeToast(toastId);
        }, 5000);
    }

    function removeToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    // AJAX Cart functionality
    function handleCartSubmission(form) {
        const button = form.querySelector('button[type="submit"]');
        const loadingText = button.querySelector('.loading-text');
        const loadingSpinner = button.querySelector('.loading-spinner');
        const loadingTextIcon = button.querySelector('.loading-text-icon');
        
        // Show loading state
        button.disabled = true;
        if (loadingText) loadingText.classList.add('hidden');
        if (loadingSpinner) loadingSpinner.classList.remove('hidden');
        if (loadingTextIcon) loadingTextIcon.classList.add('hidden');
        
        const formData = new FormData(form);
        
        fetch('{{ route("cart.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                // Update cart count in header if element exists
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement && data.cart_count !== undefined) {
                    cartCountElement.textContent = data.cart_count;
                }
                
                // Reset budget form if it was used
                const productId = form.dataset.productId;
                const budgetForm = document.querySelector(`.budget-form-${productId}`);
                if (budgetForm && !budgetForm.classList.contains('hidden')) {
                    toggleBudgetForm(productId);
                    form.reset();
                }
            } else {
                showToast(data.message || 'An error occurred while adding to cart.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while adding to cart. Please try again.', 'error');
        })
        .finally(() => {
            // Hide loading state
            button.disabled = false;
            if (loadingText) loadingText.classList.remove('hidden');
            if (loadingSpinner) loadingSpinner.classList.add('hidden');
            if (loadingTextIcon) loadingTextIcon.classList.remove('hidden');
        });
    }

    // Budget form toggle functionality
    function toggleBudgetForm(productId) {
        const regularForm = document.querySelector(`.regular-form-${productId}`);
        const budgetForm = document.querySelector(`.budget-form-${productId}`);
        
        if (regularForm && budgetForm) {
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

    // Document ready functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Handle AJAX cart forms
        const cartForms = document.querySelectorAll('.ajax-cart-form');
        cartForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleCartSubmission(form);
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

        // Handle form submission validation for budget forms
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
                        showToast(`Please enter a valid budget amount between ₱${min.toLocaleString()} and ₱${max.toLocaleString()}`, 'error');
                        budgetInput.focus();
                        return false;
                    }
                }
            });
        });

        // Add smooth scroll behavior to page
        document.documentElement.style.scrollBehavior = 'smooth';
    });

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Close toast with Escape key
        if (e.key === 'Escape') {
            const toasts = document.querySelectorAll('[id^="toast-"]');
            toasts.forEach(toast => {
                const toastId = toast.id;
                removeToast(toastId);
            });
        }
    });

    // Intersection Observer for lazy loading images (if needed)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                    img.classList.remove('loading');
                    observer.unobserve(img);
                }
            });
        });

        // Observe all product images with lazy loading
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }
</script>

@endsection