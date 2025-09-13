@extends('layout.admin')
@section('title', 'Products Management')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Products Management</h2>
            <div class="text-sm text-gray-600">
                Total Products: {{ $products->total() }}
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Product name, description, vendor..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Vendor Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                        <select name="vendor_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Vendors</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->vendor_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Availability Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                        <select name="is_available" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Products</option>
                            <option value="1" {{ request('is_available') == '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ request('is_available') == '0' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Filter
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Products Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->image_url)
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-lg object-cover" src="{{ $product->image_url }}" alt="{{ $product->product_name }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-12 w-12 bg-gray-300 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->product_name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->vendor->vendor_name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $product->vendor->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">₱{{ number_format($product->price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($product->quantity_in_stock <= 5)
                                        <span class="text-red-600 font-medium">{{ $product->quantity_in_stock }}</span>
                                        <span class="text-xs text-red-500">(Low Stock)</span>
                                    @elseif($product->quantity_in_stock <= 10)
                                        <span class="text-yellow-600 font-medium">{{ $product->quantity_in_stock }}</span>
                                        <span class="text-xs text-yellow-500">(Medium Stock)</span>
                                    @else
                                        <span class="text-green-600 font-medium">{{ $product->quantity_in_stock }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->is_available)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Unavailable
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Toggle Availability -->
                                    <form method="POST" action="{{ route('admin.products.toggle-availability', $product->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-sm px-3 py-1 rounded {{ $product->is_available ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}"
                                                onclick="return confirm('Are you sure you want to {{ $product->is_available ? 'disable' : 'enable' }} this product?')">
                                            {{ $product->is_available ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>

                                    <!-- Delete Product -->
                                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-sm px-3 py-1 rounded bg-red-100 text-red-800 hover:bg-red-200"
                                                onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                            Delete
                                        </button>
                                    </form>

                                    <!-- View Details (Optional - if you have a show route) -->
                                    <a href="#" class="text-sm px-3 py-1 rounded bg-blue-100 text-blue-800 hover:bg-blue-200">
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if(request()->hasAny(['search', 'vendor_id', 'is_available']))
                                            Try adjusting your filters to see products.
                                        @else
                                            No products have been added yet.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal (Optional Enhancement) -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50" style="display: none;">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Delete</h3>
        <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete this product? This action cannot be undone.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </button>
            <button id="confirmDelete" class="px-4 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
function openDeleteModal(productId, productName) {
    document.getElementById('deleteModal').style.display = 'flex';
    document.getElementById('confirmDelete').onclick = function() {
        document.getElementById('deleteForm-' + productId).submit();
    };
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

@endsection