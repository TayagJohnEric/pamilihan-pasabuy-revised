@extends('layout.customer')
@section('title', 'Fresh Market Products - Browse Local Vendors')
@section('content')
<div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg shadow-lg mb-8 p-6 sm:p-8 text-white">
        <div class="max-w-3xl">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4">Fresh from Local Vendors</h1>
            <p class="text-lg sm:text-xl mb-6 text-green-100">Discover quality products from trusted vendors in your community</p>
            
            <!-- Search Bar -->
            <form method="GET" action="{{ route('products.search') }}" class="flex flex-col sm:flex-row gap-2 max-w-2xl">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('search') }}"
                        placeholder="Search products, vendors, or categories..." 
                        class="w-full px-4 py-3 rounded-lg text-gray-900 border-0 focus:ring-2 focus:ring-green-300"
                    >
                </div>
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-white text-green-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200"
                >
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Filter and Sort Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <form method="GET" class="space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Category Filter -->
                <div class="min-w-0 flex-1 sm:max-w-xs">
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="all">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div class="flex gap-2">
                    <input 
                        type="number" 
                        name="min_price" 
                        value="{{ request('min_price') }}"
                        placeholder="Min Price" 
                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                    <input 
                        type="number" 
                        name="max_price" 
                        value="{{ request('max_price') }}"
                        placeholder="Max Price" 
                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                </div>
            </div>

            <div class="flex gap-2">
                <!-- Sort Options -->
                <select name="sort" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    @if(!request()->hasAny(['search', 'category', 'min_price', 'max_price']) && $featuredProducts->count() > 0)
    <!-- Featured Products Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($featuredProducts->take(4) as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-square relative overflow-hidden bg-gray-100">
                        @if($product->image_url)
                            <img 
                                src="{{ $product->image_url }}" 
                                alt="{{ $product->product_name }}"
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
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
                                Budget Based
                            </span>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-green-600">
                                {{ $product->product_name }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">
                            by <a href="{{ route('products.vendor', $product->vendor->id) }}" class="text-green-600 hover:underline">
                                {{ $product->vendor->vendor_name }}
                            </a>
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500">{{ $product->unit }}</span>
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
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Products Grid -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                    Filtered Products
                @else
                    All Products
                @endif
            </h2>
            <p class="text-gray-600">{{ $products->total() }} products found</p>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="aspect-square relative overflow-hidden bg-gray-50">
                            @if($product->image_url)
                                <img 
                                    src="{{ $product->image_url }}" 
                                    alt="{{ $product->product_name }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
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
                                    Budget Based
                                </span>
                            @endif

                            @if($product->quantity_in_stock <= 5)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    Low Stock
                                </span>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="mb-2">
                                @if($product->category)
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                        {{ $product->category->category_name }}
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('products.show', $product->id) }}" class="hover:text-green-600 transition-colors">
                                    {{ $product->product_name }}
                                </a>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $product->description }}</p>
                            
                            <p class="text-sm text-gray-600 mb-3">
                                by <a href="{{ route('products.vendor', $product->vendor->id) }}" class="text-green-600 hover:underline">
                                    {{ $product->vendor->vendor_name }}
                                </a>
                            </p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-lg font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                                    <span class="text-sm text-gray-500">/ {{ $product->unit }}</span>
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
                            
                            <div class="text-sm text-gray-500 mb-3">
                                Stock: {{ $product->quantity_in_stock }} {{ $product->unit }}
                            </div>
                            
                            <a 
                                href="{{ route('products.show', $product->id) }}" 
                                class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200"
                            >
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
        @else
            <!-- No Products Found -->
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
                <p class="text-gray-500 mb-4">Try adjusting your search or filter criteria</p>
                <a href="{{ route('products.index') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    View All Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection