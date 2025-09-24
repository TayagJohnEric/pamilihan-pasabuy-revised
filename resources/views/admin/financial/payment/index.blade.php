@extends('layout.admin')

@section('title', 'Payments Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-[90rem] mx-auto px-4 py-8">
        <!-- Header Section with Gradient Background -->
        <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl shadow-lg p-8 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center text-white">
                <div class="mb-6 lg:mb-0">
                    <h2 class="text-2xl lg:text-2xl font-bold text-white drop-shadow-sm">
                        Payments Management
                    </h2>
                    <p class="text-emerald-100 text-md">
                        Manage and filter all payments
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4 border border-white/20">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <div>
                            <div class="text-2xl font-bold text-white">{{ $payments->total() }}</div>
                            <div class="text-emerald-100 text-sm">Total Payments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
            <!-- Filters Section -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6">
                <form method="GET" action="{{ route('admin.payments.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Field -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search Rider/Vendor</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by rider or vendor name..."
                                       class="pl-10 w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors duration-200">
                            </div>
                        </div>

                        <!-- Payment Method Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method_used" 
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors duration-200">
                                <option value="">All Methods</option>
                                <option value="online_payment" 
                                    {{ request('payment_method_used') === 'online_payment' ? 'selected' : '' }}>
                                    Online Payment
                                </option>
                                <option value="cod" 
                                    {{ request('payment_method_used') === 'cod' ? 'selected' : '' }}>
                                    Cash on Delivery
                                </option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" 
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors duration-200">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex items-end space-x-2">
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                Search
                            </button>
                            <a href="{{ route('admin.payments.index') }}" 
                               class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 text-center shadow-md hover:shadow-lg">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Payments Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount Paid</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">Processed At</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden xl:table-cell">Rider</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden xl:table-cell">Vendor</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gradient-to-r hover:from-emerald-50/50 hover:to-teal-50/50 transition-all duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors duration-200">
                                                #{{ $payment->order_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-900">
                                            ₱{{ number_format($payment->amount_paid, 2) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="flex items-center">
                                        @if($payment->payment_method_used == 'cod')
                                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        @endif
                                        <span class="text-sm text-gray-700 capitalize">
                                            {{ str_replace('_', ' ', $payment->payment_method_used) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border
                                        @if($payment->status === 'completed') bg-green-100 text-green-800 border-green-200
                                        @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800 border-yellow-200
                                        @elseif($payment->status === 'failed') bg-red-100 text-red-800 border-red-200
                                        @elseif($payment->status === 'refunded') bg-gray-100 text-gray-800 border-gray-200
                                        @endif">
                                        <div class="w-2 h-2 rounded-full mr-2 
                                            @if($payment->status === 'completed') bg-green-500
                                            @elseif($payment->status === 'pending') bg-yellow-500
                                            @elseif($payment->status === 'failed') bg-red-500
                                            @elseif($payment->status === 'refunded') bg-gray-500
                                            @endif"></div>
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>
                                            {{ $payment->payment_processed_at ? $payment->payment_processed_at->format('Y-m-d H:i') : 'N/A' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm text-gray-700">
                                                @if($payment->order && $payment->order->rider)
                                                    {{ $payment->order->rider->first_name }} {{ $payment->order->rider->last_name }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden xl:table-cell">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm text-gray-700">
                                                @php
                                                    $vendors = collect();
                                                    if($payment->order && $payment->order->orderItems) {
                                                        foreach($payment->order->orderItems as $item) {
                                                            if($item->product && $item->product->vendor) {
                                                                $vendors->push($item->product->vendor->vendor_name);
                                                            }
                                                        }
                                                    }
                                                    $uniqueVendors = $vendors->unique();
                                                @endphp
                                                @if($uniqueVendors->count() > 0)
                                                    {{ $uniqueVendors->implode(', ') }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="#" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-emerald-600 hover:text-white hover:bg-emerald-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md border border-emerald-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center mb-6 shadow-lg">
                                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No payments found</h3>
                                        <p class="text-gray-600 max-w-md text-center">
                                            No payments match your current filter criteria. Try adjusting your search parameters or check back later for new payments.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection