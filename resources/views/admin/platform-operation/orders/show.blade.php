@extends('layout.admin')
@section('title', 'Order Details - #' . $order->id)
@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
            ← Back to Orders
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2 lg:mb-0">Order #{{ $order->id }}</h2>
            <div class="flex flex-col sm:flex-row gap-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    @switch($order->status)
                        @case('pending') bg-yellow-100 text-yellow-800 @break
                        @case('processing') bg-blue-100 text-blue-800 @break
                        @case('out_for_delivery') bg-purple-100 text-purple-800 @break
                        @case('delivered') bg-green-100 text-green-800 @break
                        @case('cancelled') bg-red-100 text-red-800 @break
                    @endswitch">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    @switch($order->payment_status)
                        @case('pending') bg-yellow-100 text-yellow-800 @break
                        @case('paid') bg-green-100 text-green-800 @break
                        @case('failed') bg-red-100 text-red-800 @break
                    @endswitch">
                    Payment: {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Order Date:</span>
                <p class="text-gray-600">{{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <span class="font-medium text-gray-700">Payment Method:</span>
                <p class="text-gray-600">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</p>
            </div>
            <div>
                <span class="font-medium text-gray-700">Delivery Fee:</span>
                <p class="text-gray-600">₱{{ number_format($order->delivery_fee, 2) }}</p>
            </div>
            <div>
                <span class="font-medium text-gray-700">Total Amount:</span>
                <p class="text-gray-600 font-semibold">₱{{ number_format($order->final_total_amount, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-700">Name:</span>
                        <p class="text-gray-600">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <p class="text-gray-600">{{ $order->customer->email }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Phone:</span>
                        <p class="text-gray-600">{{ $order->customer->phone_number ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Delivery Information</h3>
                <div class="space-y-2">
                    <div>
                        <span class="font-medium text-gray-700">Address Label:</span>
                        <p class="text-gray-600">{{ $order->deliveryAddress->address_label }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Address:</span>
                        <p class="text-gray-600">{{ $order->deliveryAddress->address_line1 }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">District:</span>
                        <p class="text-gray-600">{{ $order->deliveryAddress->district->name ?? 'N/A' }}</p>
                    </div>
                    @if($order->deliveryAddress->delivery_notes)
                        <div>
                            <span class="font-medium text-gray-700">Delivery Notes:</span>
                            <p class="text-gray-600">{{ $order->deliveryAddress->delivery_notes }}</p>
                        </div>
                    @endif
                    @if($order->rider)
                        <div>
                            <span class="font-medium text-gray-700">Assigned Rider:</span>
                            <p class="text-gray-600">{{ $order->rider->first_name }} {{ $order->rider->last_name }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name_snapshot }}</div>
                                        @if($item->is_substituted && $item->substitutedProduct)
                                            <div class="text-xs text-orange-600">
                                                Substituted with: {{ $item->substitutedProduct->product_name }}
                                            </div>
                                        @endif
                                        @if($item->customerNotes_snapshot)
                                            <div class="text-xs text-gray-500">Note: {{ $item->customerNotes_snapshot }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">{{ $item->product->vendor->vendor_name }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $item->quantity_requested }}
                                        @if($item->vendor_assigned_quantity_description)
                                            <div class="text-xs text-blue-600">{{ $item->vendor_assigned_quantity_description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        ₱{{ number_format($item->actual_item_price ?? $item->unit_price_snapshot, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        ₱{{ number_format(($item->actual_item_price ?? $item->unit_price_snapshot) * $item->quantity_requested, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Order Summary -->
                <div class="mt-6 border-t pt-4">
                    <div class="flex justify-end">
                        <div class="w-64">
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-gray-900">₱{{ number_format($order->orderItems->sum(function($item) { return ($item->actual_item_price ?? $item->unit_price_snapshot) * $item->quantity_requested; }), 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">Delivery Fee:</span>
                                <span class="text-gray-900">₱{{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-t font-semibold">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-gray-900">₱{{ number_format($order->final_total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->payment)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium text-gray-700">Payment Method:</span>
                        <p class="text-gray-600">{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Payment Status:</span>
                        <p class="text-gray-600">{{ ucfirst($order->payment_status) }}</p>
                    </div>
                    @if($order->payment->transaction_id)
                    <div>
                        <span class="font-medium text-gray-700">Transaction ID:</span>
                        <p class="text-gray-600">{{ $order->payment->transaction_id }}</p>
                    </div>
                    @endif
                    @if($order->payment->payment_date)
                    <div>
                        <span class="font-medium text-gray-700">Payment Date:</span>
                        <p class="text-gray-600">{{ $order->payment->payment_date->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                
                <!-- Update Status -->
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mb-4">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add any notes about this status change..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                        Update Status
                    </button>
                </form>

                <!-- Assign Rider -->
                @if(!$order->rider || $order->status != 'delivered')
                <form action="{{ route('admin.orders.assignRider', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="rider_user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $order->rider ? 'Change Rider' : 'Assign Rider' }}
                        </label>
                        <select name="rider_user_id" id="rider_user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a rider...</option>
                            @foreach($availableRiders as $rider)
                                <option value="{{ $rider->id }}" {{ $order->rider_user_id == $rider->id ? 'selected' : '' }}>
                                    {{ $rider->first_name }} {{ $rider->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                        {{ $order->rider ? 'Change Rider' : 'Assign Rider' }}
                    </button>
                </form>
                @endif
            </div>

            <!-- Order Timeline -->
            @if($order->statusHistory->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Timeline</h3>
                <div class="space-y-4">
                    @foreach($order->statusHistory->sortByDesc('created_at') as $history)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full 
                                    @switch($history->status)
                                        @case('pending') bg-yellow-400 @break
                                        @case('processing') bg-blue-400 @break
                                        @case('out_for_delivery') bg-purple-400 @break
                                        @case('delivered') bg-green-400 @break
                                        @case('cancelled') bg-red-400 @break
                                    @endswitch">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                                </p>
                                @if($history->notes)
                                    <p class="text-sm text-gray-600">{{ $history->notes }}</p>
                                @endif
                                <p class="text-xs text-gray-500">
                                    {{ $history->created_at->format('M d, Y h:i A') }}
                                    @if($history->updatedBy)
                                        by {{ $history->updatedBy->first_name }} {{ $history->updatedBy->last_name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Order Summary Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Items:</span>
                        <span class="text-gray-900">{{ $order->orderItems->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Quantity:</span>
                        <span class="text-gray-900">{{ $order->orderItems->sum('quantity_requested') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Order Value:</span>
                        <span class="text-gray-900">₱{{ number_format($order->final_total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-gray-600">Created:</span>
                        <span class="text-gray-900">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    @if($order->updated_at != $order->created_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="text-gray-900">{{ $order->updated_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection