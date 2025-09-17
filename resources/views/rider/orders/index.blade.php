@extends('layout.rider')

@section('title', 'Order Management Dashboard')

@section('content')
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Rider Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-gray-900">Delivery Dashboard</h1>
                    <p class="text-gray-600">Manage your delivery assignments and track your progress</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <!-- Availability Toggle -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-700">Available for Deliveries:</span>
                        <button id="availability-toggle" 
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 {{ Auth::user()->rider->is_available ? 'bg-green-500' : 'bg-gray-300' }}"
                                data-available="{{ Auth::user()->rider->is_available ? 'true' : 'false' }}">
                            <span class="sr-only">Toggle availability</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-sm {{ Auth::user()->rider->is_available ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                    <!-- Status Indicator -->
                    <div class="flex items-center space-x-3">
                        <div class="h-3 w-3 rounded-full {{ Auth::user()->rider->is_available ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                        <span class="text-sm font-semibold {{ Auth::user()->rider->is_available ? 'text-green-600' : 'text-gray-500' }}">
                            {{ Auth::user()->rider->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Assignments Section -->
        @if($pendingAssignments->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-2 sm:space-y-0">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Pending Assignments
                </h2>
                <span class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-sm font-medium border border-amber-200">
                    {{ $pendingAssignments->count() }} {{ $pendingAssignments->count() === 1 ? 'assignment' : 'assignments' }} pending
                </span>
            </div>
            <div class="space-y-4">
                @foreach($pendingAssignments as $order)
                <div class="border border-amber-200 rounded-xl p-6 bg-gradient-to-r from-amber-50 to-orange-50">
                    <div class="flex flex-col lg:flex-row lg:items-start space-y-4 lg:space-y-0">
                        <div class="flex-1 space-y-4">
                            <!-- Header with Customer Photo -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Customer Profile Image -->
                                    <div class="flex-shrink-0">
                                        @if($order->customer->profile_image_url)
                                            <img src="{{ $order->customer->profile_image_url }}" 
                                                 alt="{{ $order->customer->first_name }}"
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center border-2 border-white shadow-sm">
                                                <span class="text-gray-500 font-medium text-lg">
                                                    {{ substr($order->customer->first_name, 0, 1) }}{{ substr($order->customer->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Order #{{ $order->id }}</h3>
                                        <p class="text-gray-600">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold uppercase tracking-wide">
                                    Action Required
                                </span>
                            </div>
                            
                            <!-- Order Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $order->customer->phone_number ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $order->deliveryAddress->address_line1 }}, {{ $order->deliveryAddress->district->name }}</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-green-600">₱{{ number_format($order->delivery_fee, 2) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pickup Locations -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-semibold text-gray-700 flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Pickup Locations
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $vendors = $order->orderItems->pluck('product.vendor')->unique('id');
                                    @endphp
                                    @foreach($vendors as $vendor)
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium border border-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Stall {{ $vendor->stall_number ?? 'N/A' }} - {{ $vendor->vendor_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-row lg:flex-col space-x-3 lg:space-x-0 lg:space-y-3 lg:ml-6">
                            <button class="accept-order-btn flex-1 lg:flex-none px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition-colors duration-200 shadow-sm"
                                    data-order-id="{{ $order->id }}">
                                Accept Order
                            </button>
                            <button class="decline-order-btn flex-1 lg:flex-none px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-colors duration-200"
                                    data-order-id="{{ $order->id }}">
                                Decline
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Active Deliveries Section -->
        @if($activeDeliveries->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-2 sm:space-y-0">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Active Deliveries
                </h2>
                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium border border-blue-200">
                    {{ $activeDeliveries->count() }} {{ $activeDeliveries->count() === 1 ? 'delivery' : 'deliveries' }} active
                </span>
            </div>
            <div class="space-y-4">
                @foreach($activeDeliveries as $order)
                <div class="border border-blue-200 rounded-xl p-6 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <div class="flex items-center space-x-4">
                            <!-- Customer Profile Image -->
                            <div class="flex-shrink-0">
                                @if($order->customer->profile_image_url)
                                    <img src="{{ $order->customer->profile_image_url }}" 
                                         alt="{{ $order->customer->first_name }}"
                                         class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center border-2 border-white shadow-sm">
                                        <span class="text-gray-500 font-medium text-lg">
                                            {{ substr($order->customer->first_name, 0, 1) }}{{ substr($order->customer->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-1 sm:space-y-0">
                                    <h3 class="text-xl font-bold text-gray-900">Order #{{ $order->id }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold uppercase tracking-wide w-fit">
                                        In Progress
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 mt-2 text-sm text-gray-600">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <span class="font-semibold text-green-600">₱{{ number_format($order->final_total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span>{{ $order->customer->phone_number ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('rider.orders.show', $order) }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- No Active Orders Message -->
        @if($pendingAssignments->count() == 0 && $activeDeliveries->count() == 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2m0 0V6a2 2 0 012-2h2"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">No Active Orders</h3>
            <p class="text-gray-600 max-w-md mx-auto leading-relaxed">
                @if(!Auth::user()->rider->is_available)
                    You're currently unavailable for deliveries. Toggle your availability above to start receiving orders.
                @else
                    You're available for deliveries! New orders will appear here when assigned.
                @endif
            </p>
        </div>
        @endif

        <!-- Recent Delivery History -->
        @if($deliveryHistory->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Recent Deliveries
            </h2>
            <div class="space-y-3">
                @foreach($deliveryHistory as $order)
                <div class="border rounded-xl p-4 {{ $order->status === 'delivered' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                        <div class="flex items-center space-x-4">
                            <!-- Customer Profile Image -->
                            <div class="flex-shrink-0">
                                @if($order->customer->profile_image_url)
                                    <img src="{{ $order->customer->profile_image_url }}" 
                                         alt="{{ $order->customer->first_name }}"
                                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center border-2 border-white shadow-sm">
                                        <span class="text-gray-500 font-medium text-sm">
                                            {{ substr($order->customer->first_name, 0, 1) }}{{ substr($order->customer->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <span class="font-bold text-gray-900">Order #{{ $order->id }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                                    <span>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</span>
                                    <span class="font-semibold text-green-600">₱{{ number_format($order->delivery_fee, 2) }}</span>
                                    <span>{{ $order->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF token for AJAX requests
            const csrfToken = '{{ csrf_token() }}';

            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `flex items-center px-4 py-3 mb-2 text-sm rounded-xl shadow-lg transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ${
                    type === 'success' ? 'text-white bg-green-500 border border-green-600' :
                    type === 'error' ? 'text-white bg-red-500 border border-red-600' :
                    'text-white bg-blue-500 border border-blue-600'
                }`;
                
                toast.innerHTML = `
                    <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        ${type === 'success' ? 
                            '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>' :
                            '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-white hover:bg-opacity-20 inline-flex h-6 w-6 items-center justify-center" onclick="this.parentElement.remove()">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
                
                document.getElementById('toast-container').appendChild(toast);
                
                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                }, 100);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }

            // Handle availability toggle
            document.getElementById('availability-toggle').addEventListener('click', function() {
                const toggle = this;
                const originalContent = toggle.innerHTML;
                
                // Show loading state
                toggle.disabled = true;
                toggle.classList.add('opacity-75', 'cursor-not-allowed');
                
                fetch('{{ route("rider.availability.toggle") }}', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    toggle.disabled = false;
                    toggle.classList.remove('opacity-75', 'cursor-not-allowed');
                    
                    if (data.success) {
                        // Update toggle appearance
                        const toggle = document.getElementById('availability-toggle');
                        const indicator = toggle.querySelector('span:last-child');
                        const statusDot = document.querySelector('.h-3.w-3.rounded-full');
                        const statusText = statusDot.nextElementSibling;
                        
                        if (data.is_available) {
                            toggle.classList.add('bg-green-500');
                            toggle.classList.remove('bg-gray-300');
                            indicator.classList.add('translate-x-6');
                            indicator.classList.remove('translate-x-1');
                            statusDot.classList.add('bg-green-500');
                            statusDot.classList.remove('bg-gray-400');
                            statusText.textContent = 'Available';
                            statusText.classList.add('text-green-600');
                            statusText.classList.remove('text-gray-500');
                        } else {
                            toggle.classList.remove('bg-green-500');
                            toggle.classList.add('bg-gray-300');
                            indicator.classList.remove('translate-x-6');
                            indicator.classList.add('translate-x-1');
                            statusDot.classList.remove('bg-green-500');
                            statusDot.classList.add('bg-gray-400');
                            statusText.textContent = 'Unavailable';
                            statusText.classList.remove('text-green-600');
                            statusText.classList.add('text-gray-500');
                        }
                        
                        // Show success message
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Failed to update availability status.', 'error');
                    }
                })
                .catch(error => {
                    // Reset button state on error
                    toggle.disabled = false;
                    toggle.classList.remove('opacity-75', 'cursor-not-allowed');
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });
            });

            // Handle order acceptance
            document.querySelectorAll('.accept-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const originalContent = this.innerHTML;
                    
                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = `
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Processing...</span>
                        </div>
                    `;
                    this.classList.add('opacity-75', 'cursor-not-allowed');
                    
                    fetch(`/rider/orders/${orderId}/accept`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button state
                            this.disabled = false;
                            this.innerHTML = originalContent;
                            this.classList.remove('opacity-75', 'cursor-not-allowed');
                            
                            if (data.success) {
                                showToast(data.message, 'success');
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    location.reload();
                                }
                            } else {
                                showToast(data.message || 'Failed to accept order.', 'error');
                            }
                        })
                        .catch(error => {
                            // Reset button state on error
                            this.disabled = false;
                            this.innerHTML = originalContent;
                            this.classList.remove('opacity-75', 'cursor-not-allowed');
                            console.error('Error:', error);
                            showToast('An error occurred. Please try again.', 'error');
                        });
                });
            });

            // Handle order decline
            document.querySelectorAll('.decline-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    const originalContent = this.innerHTML;
                    
                    // Show loading state
                    this.disabled = true;
                    this.innerHTML = `
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Processing...</span>
                        </div>
                    `;
                    this.classList.add('opacity-75', 'cursor-not-allowed');
                    
                    fetch(`/rider/orders/${orderId}/decline`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Reset button state
                            this.disabled = false;
                            this.innerHTML = originalContent;
                            this.classList.remove('opacity-75', 'cursor-not-allowed');
                            
                            if (data.success) {
                                showToast(data.message, 'success');
                                location.reload();
                            } else {
                                showToast(data.message || 'Failed to decline order.', 'error');
                            }
                        })
                        .catch(error => {
                            // Reset button state on error
                            this.disabled = false;
                            this.innerHTML = originalContent;
                            this.classList.remove('opacity-75', 'cursor-not-allowed');
                            console.error('Error:', error);
                            showToast('An error occurred. Please try again.', 'error');
                        });
                });
            });
        });
    </script>
@endsection