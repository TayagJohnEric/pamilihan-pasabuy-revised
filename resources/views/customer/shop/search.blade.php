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

    <!-- Filter and Sort Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <form method="GET" action="{{ route('products.search') }}" class="space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
            <!-- Hidden search term -->
            <input type="hidden" name="q" value="{{ $searchTerm }}">
            
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Category Filter -->
                <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="all" {{ request('category') == 'all' || !request('category') ? 'selected' : '' }}>All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                
                <!-- Price Range -->
                <div class="flex gap-2">
                    <input 
                        type="number" 
                        name="min_price" 
                        value="{{ request('min_price') }}"
                        placeholder="Min Price" 
                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                    <input 
                        type="number" 
                        name="max_price" 
                        value="{{ request('max_price') }}"
                        placeholder="Max Price" 
                        class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                    >
                </div>
            </div>

            <div class="flex gap-2">
                <!-- Sort Options -->
                <select name="sort" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="relevance" {{ request('sort') == 'relevance' || !request('sort') ? 'selected' : '' }}>Most Relevant</option>
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Apply
                </button>
            </div>
        </form>
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
                                    src="{{ $product->image_url }}" 
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
                                    Budget Based
                                </span>
                            @endif

                            @if($product->quantity_in_stock <= 5 && $product->quantity_in_stock > 0)
                                <span class="absolute top-2 right-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                                    Low Stock
                                </span>
                            @elseif($product->quantity_in_stock == 0)
                                <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    Out of Stock
                                </span>
                            @endif

                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-300"></div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('products.show', $product->id) }}" class="hover:text-purple-600 transition-colors">
                                    {{ $product->product_name }}
                                </a>
                            </h3>
                            
                            @if($product->description)
                                <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ $product->description }}</p>
                            @endif
                            
                            <div class="text-sm text-gray-600 mb-3 space-y-1">
                                <p>
                                    by <a href="{{ route('products.vendor', $product->vendor->id) }}" class="text-purple-600 hover:underline font-medium">
                                        {{ $product->vendor->vendor_name }}
                                    </a>
                                </p>
                                @if($product->category)
                                    <p>
                                        in <a href="{{ route('products.category', $product->category->id) }}" class="text-purple-600 hover:underline">
                                            {{ $product->category->category_name }}
                                        </a>
                                    </p>
                                @endif
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
                            
                            <div class="text-sm mb-3">
                                @if($product->quantity_in_stock > 0)
                                    <span class="text-green-600 font-semibold">In Stock: {{ $product->quantity_in_stock }}</span>
                                @else
                                    <span class="text-red-500 font-semibold">Out of Stock</span>
                                @endif
                            </div>
                            <div>
                                @if($product->quantity_in_stock > 0)
                                    <form method="POST" action="{{ route('cart.add', $product->id) }}">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors">Add to Cart</button>
                                    </form>
                                @else
                                    <button class="w-full px-4 py-2 bg-gray-300 text-gray-500 rounded-lg font-semibold cursor-not-allowed" disabled>Out of Stock</button>
                                @endif
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
@endsection