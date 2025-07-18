@extends('layout.vendor')
@section('title', 'Product Details')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Product Details</h2>
                <p class="text-gray-600">View product information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('vendor.products.edit', $product) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Product
                </a>
                <a href="{{ route('vendor.products.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>

        <!-- Product Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="aspect-square w-full overflow-hidden rounded-lg bg-gray-100">
                    @if($product->image_url)
                        <img src="{{ asset('storage/' . $product->image_url) }}" 
                             alt="{{ $product->product_name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="flex space-x-3">
                    <form action="{{ route('vendor.products.destroy', $product) }}" 
                          method="POST" 
                          class="flex-1"
                          onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                            Delete Product
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $product->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $product->is_budget_based ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product->is_budget_based ? 'Budget-Based' : 'Standard Pricing' }}
                    </span>
                </div>

                <!-- Product Name -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->product_name }}</h1>
                </div>

                <!-- Product Information Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Category</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $product->category->category_name ?? 'No Category' }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Unit</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $product->unit }}</p>
                    </div>

                    @if($product->is_budget_based)
                        <div class="bg-gray-50 p-4 rounded-lg sm:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Indicative Price per Unit</h3>
                            <p class="text-2xl font-bold text-blue-600">₱{{ number_format($product->indicative_price_per_unit, 2) }}</p>
                            <p class="text-sm text-gray-600 mt-1">Actual quantity will depend on customer's budget</p>
                        </div>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Price</h3>
                            <p class="text-2xl font-bold text-green-600">₱{{ number_format($product->price, 2) }}</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Stock Quantity</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $product->quantity_in_stock }} {{ $product->unit }}</p>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                @if($product->description)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                        <div class="prose prose-sm max-w-none">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $product->description }}</p>
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-500">
                        <div>
                            <span class="font-medium">Created:</span>
                            <span>{{ $product->created_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Last Updated:</span>
                            <span>{{ $product->updated_at->format('M d, Y \a\t h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Product ID -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Product ID</h4>
                    <p class="text-base font-semibold text-gray-900">#{{ $product->id }}</p>
                </div>

                <!-- Vendor Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Vendor</h4>
                    <p class="text-base font-semibold text-gray-900">{{ $product->vendor->business_name ?? 'N/A' }}</p>
                </div>

                <!-- Availability Status -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full {{ $product->is_available ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        <span class="text-base font-semibold text-gray-900">
                            {{ $product->is_available ? 'Available for Orders' : 'Currently Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Pricing Model Details -->
            <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-blue-800 mb-2">Pricing Model</h4>
                @if($product->is_budget_based)
                    <div class="space-y-2">
                        <p class="text-sm text-blue-700">
                            <span class="font-medium">Budget-Based Pricing:</span>
                            This product uses flexible pricing based on customer budget.
                        </p>
                        <p class="text-sm text-blue-700">
                            The indicative price of ₱{{ number_format($product->indicative_price_per_unit, 2) }} per {{ $product->unit }} 
                            helps customers estimate quantities within their budget.
                        </p>
                    </div>
                @else
                    <div class="space-y-2">
                        <p class="text-sm text-blue-700">
                            <span class="font-medium">Standard Pricing:</span>
                            This product has fixed pricing per unit.
                        </p>
                        <p class="text-sm text-blue-700">
                            Current stock: {{ $product->quantity_in_stock }} {{ $product->unit }} 
                            available at ₱{{ number_format($product->price, 2) }} per {{ $product->unit }}.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('vendor.products.edit', $product) }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Product
                </a>
                
                <button type="button" 
                        onclick="toggleAvailability({{ $product->id }})"
                        class="inline-flex items-center px-6 py-3 {{ $product->is_available ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($product->is_available)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        @endif
                    </svg>
                    {{ $product->is_available ? 'Mark as Unavailable' : 'Mark as Available' }}
                </button>
                
                <form action="{{ route('vendor.products.destroy', $product) }}" 
                      method="POST" 
                      class="inline-block"
                      onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleAvailability(productId) {
    fetch(`/vendor/products/${productId}/toggle-availability`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert(data.message);
            // Reload the page to update the UI
            location.reload();
        } else {
            alert('Error updating product availability');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating product availability');
    });
}
</script>
@endpush
@endsection