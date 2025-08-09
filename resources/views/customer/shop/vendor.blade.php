@extends('layout.customer')
@section('title', $vendor->vendor_name . ' - Vendor Shop')
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
                    <span class="text-gray-500">{{ $vendor->vendor_name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Vendor Header -->
    <div class="bg-white rounded-lg shadow-lg mb-8 overflow-hidden">
        <!-- Banner -->
        @if($vendor->shop_banner_url)
            <div class="h-48 sm:h-64 bg-gradient-to-r from-green-600 to-blue-600 relative">
                <img 
                    src="{{ $vendor->shop_banner_url }}" 
                    alt="{{ $vendor->vendor_name }} banner"
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            </div>
        @else
            <div class="h-48 sm:h-64 bg-gradient-to-r from-green-600 to-blue-600"></div>
        @endif

        <!-- Vendor Info -->
        <div class="relative px-6 pb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-end space-y-4 sm:space-y-0 sm:space-x-6 -mt-16">
                <!-- Logo -->
                <div class="relative">
                    @if($vendor->shop_logo_url)
                        <img 
                            src="{{ $vendor->shop_logo_url }}" 
                            alt="{{ $vendor->vendor_name }} logo"
                            class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg bg-white"
                        >
                    @else
                        <div class="w-32 h-32 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                    @if($vendor->verification_status === 'verified')
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Vendor Details -->
                <div class="flex-1 pt-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ $vendor->vendor_name }}</h1>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-2">
                                @if($vendor->stall_number)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        Stall {{ $vendor->stall_number }}
                                    </span>
                                @endif
                                @if($vendor->market_section)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H9a1 1 0 00-1 1v6a1 1 0 01-1 1H4a1 1 0 110-2V4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $vendor->market_section }}
                                    </span>
                                @endif
                                @if($vendor->business_hours)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $vendor->business_hours }}
                                    </span>
                                @endif
                            </div>
                            @if($vendor->average_rating)
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $vendor->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ number_format($vendor->average_rating, 1) }} rating</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2 mt-4 sm:mt-0">
                            @if($vendor->verification_status === 'verified')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Verified Vendor
                                </span>
                            @endif
                            @if($vendor->accepts_cod)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    💳 Cash on Delivery
                                </span>
                            @endif
                            @if($vendor->is_accepting_orders)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🟢 Accepting Orders
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    🔴 Not Accepting Orders
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($vendor->description)
                        <p class="text-gray-700 mt-4 leading-relaxed">{{ $vendor->description }}</p>
                    @endif

                    @if($vendor->public_contact_number || $vendor->public_email)
                        <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">
                            @if($vendor->public_contact_number)
                                <a href="tel:{{ $vendor->public_contact_number }}" class="flex items-center hover:text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    {{ $vendor->public_contact_number }}
                                </a>
                            @endif
                            @if($vendor->public_email)
                                <a href="mailto:{{ $vendor->public_email }}" class="flex items-center hover:text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                    {{ $vendor->public_email }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <form method="GET" class="space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Search Bar -->
                <div class="flex-1 min-w-0 sm:max-w-md">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search products from {{ $vendor->vendor_name }}..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                </div>

                <!-- Category Filter -->
                @if($categories->count() > 0)
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
                @endif
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

    <!-- Products Grid -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Products</h2>
            <p class="text-gray-600">{{ $products->total() }} products found</p>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="aspect-square relative overflow-hidden bg-gray-50">
                            @if($product->image_url)
                                <img 
                                    src="{{ asset('storage/' . $product->image_url) }}"
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
                            @if($product->category)
                                <div class="mb-2">
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                        {{ $product->category->category_name }}
                                    </span>
                                </div>
                            @endif
                            
                            <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('products.show', $product->id) }}" class="hover:text-green-600 transition-colors">
                                    {{ $product->product_name }}
                                </a>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span class="text-lg font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                                    <span class="text-sm text-gray-500">/ {{ $product->unit }}</span>
                                </div>
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
                <p class="text-gray-500 mb-4">This vendor hasn't added any products yet or your search didn't match any products.</p>
                <div class="space-x-4">
                    <a href="{{ route('products.vendor', $vendor->id) }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        View All Products
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Browse Other Vendors
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection