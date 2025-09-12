@extends('layout.vendor')
@section('title', 'Edit Product')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Product</h2>
                <p class="text-gray-600">Update product information</p>
            </div>
            <a href="{{ route('vendor.products.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Products
            </a>
        </div>

        @if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <!-- Form -->
        <form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" 
                           id="product_name" 
                           name="product_name" 
                           value="{{ old('product_name', $product->product_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('product_name') border-red-500 @enderror"
                           placeholder="Enter product name">
                    @error('product_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                    <input type="text" 
                           id="unit" 
                           name="unit" 
                           value="{{ old('unit', $product->unit) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unit') border-red-500 @enderror"
                           placeholder="e.g., kg, piece, liter">
                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                    
                    <!-- Current Image Preview -->
                    @if($product->image_url)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $product->image_url) }}" 
                                 alt="Current product image" 
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-300">
                            <p class="text-sm text-gray-500 mt-1">Current image</p>
                        </div>
                    @endif
                    
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image. Maximum file size: 2MB. Accepted formats: JPEG, PNG, JPG, GIF</p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                          placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pricing Model -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Pricing Model *</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Standard Pricing -->
                    <div class="relative">
                        <input type="radio" 
                               id="standard_pricing" 
                               name="pricing_model" 
                               value="standard" 
                               class="sr-only peer"
                               {{ old('pricing_model', $product->is_budget_based ? 'budget_based' : 'standard') == 'standard' ? 'checked' : '' }}>
                        <label for="standard_pricing" 
                               class="flex p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <div class="flex items-center">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 peer-checked:border-blue-600 peer-checked:bg-blue-600"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Standard Pricing</div>
                                    <div class="text-sm text-gray-500">Set a fixed price with stock quantity</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Budget-Based Pricing -->
                    <div class="relative">
                        <input type="radio" 
                               id="budget_pricing" 
                               name="pricing_model" 
                               value="budget_based" 
                               class="sr-only peer"
                               {{ old('pricing_model', $product->is_budget_based ? 'budget_based' : 'standard') == 'budget_based' ? 'checked' : '' }}>
                        <label for="budget_pricing" 
                               class="flex p-4 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50">
                            <div class="flex items-center">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 peer-checked:border-blue-600 peer-checked:bg-blue-600"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Budget-Based Pricing</div>
                                    <div class="text-sm text-gray-500">Set indicative price per unit, quantity varies by budget</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                @error('pricing_model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price and Stock Fields (Always Visible) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₱) *</label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           value="{{ old('price', $product->price) }}"
                           step="0.01" 
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity_in_stock" class="block text-sm font-medium text-gray-700 mb-2">Quantity in Stock *</label>
                    <input type="number" 
                           id="quantity_in_stock" 
                           name="quantity_in_stock" 
                           value="{{ old('quantity_in_stock', $product->quantity_in_stock) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity_in_stock') border-red-500 @enderror"
                           placeholder="0">
                    @error('quantity_in_stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Budget-Based Pricing Fields -->
            <div id="budget_fields" class="hidden">
                <div class="max-w-md">
                    <label for="indicative_price_per_unit" class="block text-sm font-medium text-gray-700 mb-2">Indicative Price per Unit (₱) *</label>
                    <input type="number" 
                           id="indicative_price_per_unit" 
                           name="indicative_price_per_unit" 
                           value="{{ old('indicative_price_per_unit', $product->indicative_price_per_unit) }}"
                           step="0.01" 
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('indicative_price_per_unit') border-red-500 @enderror"
                           placeholder="0.00">
                    @error('indicative_price_per_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">This is an estimated price. Actual quantity will depend on customer's budget.</p>
                </div>
            </div>

            <!-- Availability -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_available" 
                           value="1" 
                           {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="ml-2 text-sm font-medium text-gray-700">Product is available for sale</span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" 
                        class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Update Product
                </button>
                <a href="{{ route('vendor.products.index') }}" 
                   class="flex-1 sm:flex-none px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg text-center transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const standardRadio = document.getElementById('standard_pricing');
    const budgetRadio = document.getElementById('budget_pricing');
    const budgetFields = document.getElementById('budget_fields');
    
    function togglePricingFields() {
        if (budgetRadio.checked) {
            // Show budget-based fields when budget pricing is selected
            budgetFields.classList.remove('hidden');
            document.getElementById('indicative_price_per_unit').disabled = false;
        } else {
            // Hide budget-based fields when standard pricing is selected
            budgetFields.classList.add('hidden');
            document.getElementById('indicative_price_per_unit').disabled = true;
        }

        // Price and stock fields remain enabled and visible for both pricing models
        document.getElementById('price').disabled = false;
        document.getElementById('quantity_in_stock').disabled = false;
    }
    
    standardRadio.addEventListener('change', togglePricingFields);
    budgetRadio.addEventListener('change', togglePricingFields);
    
    // Initialize on page load
    togglePricingFields();
});
</script>
@endsection