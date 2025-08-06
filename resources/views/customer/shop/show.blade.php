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
                            src="{{ $product->image_url }}" 
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
                            Budget Based
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
                        <span class="text-sm font-medium text-gray-700">Stock Available:</span>
                        <span class="text-sm {{ $product->quantity_in_stock <= 5 ? 'text-red-600' : 'text-green-600' }} font-medium">
                            {{ $product->quantity_in_stock }} {{ $product->unit }}
                        </span>
                    </div>
                    @if($product->quantity_in_stock <= 5)
                        <p class="text-sm text-red-600">⚠️ Low stock - order soon!</p>
                    @endif
                </div>

                @if($product->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @if($product->quantity_in_stock > 0)
                        <button class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200">
                            Add to Cart
                        </button>
                        <button class="w-full bg-white border-2 border-green-600 text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-green-50 transition-colors duration-200">
                            Buy Now
                        </button>
                    @else
                        <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                            Out of Stock
                        </button>
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
    @if($product->vendor->ratingsReceived->count() > 0)
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
                                <span class="ml-2 font-medium">{{ $rating->user->first_name }} {{ $rating->user->last_name }}</span>
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
                                <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->product_name }}" class="w-full h-full object-cover">
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
                                <img src="{{ $similarProduct->image_url }}" alt="{{ $similarProduct->product_name }}" class="w-full h-full object-cover">
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
@endsection