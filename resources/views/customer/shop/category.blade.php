@extends('layout.customer')
@section('title', $category->category_name . ' - Browse Products')
@section('content')

<style>

/* Custom animations */
    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

.animate-slide-in {
        animation: slideIn 0.6s ease-out forwards;
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
        pointer-events: none;
    }

</style>

<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm animate-slide-in ">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-green-600">Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-500">{{ $category->category_name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-lg shadow-lg mb-8 p-6 sm:p-8 text-white animate-slide-in">
        <div class="max-w-3xl">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 leading-tight">{{ $category->category_name }}</h1>
            @if($category->description)
                <p class="text-md sm:text-lg mb-5 text-emerald-100 max-w-2xl">{{ $category->description }}</p>
            @endif
            
           <!-- Search Bar -->
            <form method="GET" action="{{ route('products.search') }}" class="max-w-3xl">
                <div class="search-input-wrapper">
                    <label for="product-search" class="sr-only">Search products</label>
                    <input id="product-search" type="text" name="q" value="{{ request('search') }}" placeholder="Search products, vendors, or categories..." class="w-full px-5 py-4 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-emerald-300/50 shadow-lg placeholder-gray-500 text-lg" aria-label="Search products, vendors, or categories">
                    <button type="submit" class="search-icon" aria-label="Search products">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Navigation -->
        @if($categories->count() > 0)
            <div class="animate-slide-in mb-7">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Browse by Category:</h3>
                <div class="relative">
                    <div id="category-carousel" class="category-carousel flex gap-3 overflow-x-auto pb-2">
                        @foreach($categories->take(12) as $cat)
                            <a href="{{ route('products.category', $cat->id) }}"
                            class="flex-none px-4 py-2 border border-gray-200 shadow-sm text-sm font-medium whitespace-nowrap rounded-xl transition-all duration-200
                            {{ $cat->id == $category->id 
                                ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 hover:text-white' 
                                : 'bg-white text-gray-600 hover:bg-emerald-100 hover:text-emerald-700' }}">
                                {{ $cat->category_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


    <!-- Products Grid -Mobile Optimized -->
   <div class="animate-slide-in">
        <div class="sm:max-w-[90rem] sm:mx-auto">
            <div class="p-1">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                   Products in {{ $category->category_name }}
                </h2>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-10">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="product-card bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:border-emerald-300">
                            <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                @if($product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif

                                @if($product->is_budget_based)
                                    <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">Budget</span>
                                @endif
                            </div>

                            <div class="p-3 sm:p-4">
                                <div class="mb-2">
                                    @if($product->category)
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full">{{ $product->category->category_name }}</span>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm sm:text-base">{{ $product->product_name }}</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mb-3">by <span class="text-emerald-600 font-medium">{{ $product->vendor->vendor_name }}</span></p>
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
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        {{ $products->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">No products found</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">We couldn't find any products matching your search criteria. Try adjusting your filters or search terms.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl">View All Products</a>
                        <button onclick="document.querySelector('form').reset()" class="inline-block px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">Clear Filters</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>

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

</script>
@endsection