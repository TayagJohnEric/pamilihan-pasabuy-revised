@extends('layout.rider')

@section('title', 'Order Details - #' . $order->id)

@section('content')
    <div class="min-h-screen bg-gray-50 py-4 sm:py-6 lg:py-8">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Back Navigation -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('rider.orders.index') }}" 
                   class="inline-flex items-center space-x-2 text-gray-600 hover:text-green-600 transition-colors duration-200 group">
                    <div class="p-2 rounded-lg group-hover:bg-green-50 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Back to Dashboard</span>
                </a>
            </div>

            <!-- Order Header Card -->
            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                <div class="px-6 py-8 sm:px-8">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="p-2 bg-green-50 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                            </div>
                            <p class="text-gray-600 text-sm sm:text-base">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <!-- Order Status Badge -->
                            <div class="order-1 sm:order-2">
                                <span class="inline-flex items-center px-4 py-2 rounded-xl font-semibold text-sm border
                                    {{ $order->status === 'awaiting_rider_assignment' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $order->status === 'assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $order->status === 'pickup_confirmed' ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                    {{ $order->status === 'out_for_delivery' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-50 text-green-700 border-green-200' : '' }}">
                                    <div class="w-2 h-2 rounded-full mr-2
                                        {{ $order->status === 'awaiting_rider_assignment' ? 'bg-amber-400' : '' }}
                                        {{ $order->status === 'assigned' ? 'bg-blue-400' : '' }}
                                        {{ $order->status === 'pickup_confirmed' ? 'bg-purple-400' : '' }}
                                        {{ $order->status === 'out_for_delivery' ? 'bg-indigo-400' : '' }}
                                        {{ $order->status === 'delivered' ? 'bg-green-400' : '' }}"></div>
                                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="order-2 sm:order-1 flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                @if($order->status === 'awaiting_rider_assignment')
                                    <button id="accept-order-btn" 
                                            class="relative inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Accept Order</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing...</span>
                                        </div>
                                    </button>
                                    <button id="decline-order-btn" 
                                            class="relative inline-flex items-center justify-center px-6 py-3 bg-white text-red-600 border-2 border-red-200 rounded-xl hover:bg-red-50 hover:border-red-300 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Decline Order</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-red-600 mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing...</span>
                                        </div>
                                    </button>
                                @elseif($order->status === 'assigned')
                                    <button id="confirm-pickup-btn" 
                                            class="relative inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Confirm Pickup</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing...</span>
                                        </div>
                                    </button>
                                @elseif($order->status === 'pickup_confirmed')
                                    <button id="start-delivery-btn" 
                                            class="relative inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Start Delivery</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing...</span>
                                        </div>
                                    </button>
                                @elseif($order->status === 'out_for_delivery')
                                    <button id="mark-delivered-btn" 
                                            class="relative inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto"
                                            data-order-id="{{ $order->id }}">
                                        <span class="button-text">Mark as Delivered</span>
                                        <div class="button-loading hidden flex items-center justify-center">
                                            <svg class="animate-spin h-4 w-4 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing...</span>
                                        </div>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="xl:col-span-2 space-y-6">
                    
                    <!-- Customer Information -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-2 bg-green-50 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                Customer Information
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="grid md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($order->customer->profile_image_url)
                                                <img class="w-16 h-16 rounded-full object-cover border-2 border-green-100" 
                                                     src="{{asset('storage/' . $order->customer->profile_image_url)  }}" 
                                                     alt="{{ $order->customer->first_name }} {{ $order->customer->last_name }}">
                                            @else
                                                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center border-2 border-green-200">
                                                    <span class="text-green-600 font-semibold text-xl">
                                                        {{ substr($order->customer->first_name, 0, 1) }}{{ substr($order->customer->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 text-lg truncate">
                                                {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                            </h4>
                                            <p class="text-gray-600 text-sm truncate">{{ $order->customer->email }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($order->customer->phone_number)
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-gray-50 rounded-lg">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </div>
                                            <a href="tel:{{ $order->customer->phone_number }}" 
                                               class="text-green-600 hover:text-green-700 font-medium transition-colors duration-200">
                                                {{ $order->customer->phone_number }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                        <div class="p-1 bg-green-50 rounded mr-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        Delivery Address
                                    </h4>
                                    <div class="space-y-2">
                                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                            <p class="font-semibold text-gray-900 mb-1">{{ $order->deliveryAddress->address_label }}</p>
                                            <p class="text-gray-700 text-sm">{{ $order->deliveryAddress->address_line1 }}</p>
                                            <p class="text-gray-600 text-sm">{{ $order->deliveryAddress->district->name }}</p>
                                        </div>
                                        @if($order->deliveryAddress->delivery_notes)
                                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                                <div class="flex items-start space-x-2">
                                                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="font-medium text-amber-800 text-sm">Delivery Notes</p>
                                                        <p class="text-amber-700 text-sm">{{ $order->deliveryAddress->delivery_notes }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Locations -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-2 bg-green-50 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                Pickup Locations
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-6">
                                @foreach($vendorGroups as $vendorId => $items)
                                    @php $vendor = $items->first()->product->vendor; @endphp
                                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        @if($vendor->shop_logo_url)
                                                            <img class="w-12 h-12 rounded-xl object-cover border border-gray-200" 
                                                                 src="{{asset('storage/' . $vendor->shop_logo_url ) }}" 
                                                                 alt="{{ $vendor->vendor_name }}">
                                                        @else
                                                            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center border border-green-200">
                                                                <span class="text-green-600 font-semibold text-lg">
                                                                    {{ substr($vendor->vendor_name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-bold text-gray-900 text-lg truncate">{{ $vendor->vendor_name }}</h4>
                                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mt-1">
                                                            <span class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4"></path>
                                                                </svg>
                                                                Stall: {{ $vendor->stall_number ?? 'N/A' }}
                                                            </span>
                                                            <span class="flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                </svg>
                                                                Section: {{ $vendor->market_section ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        @if($vendor->public_contact_number)
                                                            <a href="tel:{{ $vendor->public_contact_number }}" 
                                                               class="inline-flex items-center text-green-600 hover:text-green-700 text-sm font-medium mt-2 transition-colors duration-200">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                                </svg>
                                                                {{ $vendor->public_contact_number }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                                        {{ $items->count() }} item{{ $items->count() > 1 ? 's' : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="px-6 py-4">
                                            <div class="space-y-3">
                                                @foreach($items as $item)
                                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                                        <div class="flex-1 min-w-0">
                                                            <h5 class="font-semibold text-gray-900 truncate">{{ $item->product_name_snapshot }}</h5>
                                                            <div class="flex flex-wrap gap-3 text-sm text-gray-600 mt-1">
                                                                <span class="flex items-center">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                                    </svg>
                                                                    Qty: {{ $item->quantity_requested }} {{ $item->product->unit }}
                                                                </span>
                                                                @if($item->customer_budget_requested)
                                                                    <span class="flex items-center">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                                        </svg>
                                                                        Budget: ₱{{ number_format($item->customer_budget_requested, 2) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            @if($item->customerNotes_snapshot)
                                                                <p class="text-sm text-gray-500 mt-2 italic">Note: {{ $item->customerNotes_snapshot }}</p>
                                                            @endif
                                                        </div>
                                                        <div class="flex-shrink-0 ml-4">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold
                                                                {{ $item->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                                                {{ $item->status === 'preparing' ? 'bg-blue-100 text-blue-700' : '' }}
                                                                {{ $item->status === 'ready_for_pickup' ? 'bg-green-100 text-green-700' : '' }}">
                                                                {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    @if($order->special_instructions)
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-2 bg-orange-50 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                Special Instructions
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-orange-800 font-medium">{{ $order->special_instructions }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    
                    <!-- Payment Information -->
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-2 bg-purple-50 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2"></path>
                                    </svg>
                                </div>
                                Payment Details
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600 font-medium">Payment Method</span>
                                    <span class="font-bold text-gray-900 uppercase">{{ $order->payment_method }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-gray-600 font-medium">Payment Status</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold
                                        {{ $order->payment_status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $order->payment_status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ strtoupper($order->payment_status) }}
                                    </span>
                                </div>
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600 font-medium">Order Total</span>
                                        <span class="font-bold text-gray-900 text-lg">₱{{ number_format($order->final_total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-gray-600 font-medium">Your Delivery Fee</span>
                                        <span class="font-bold text-green-600 text-lg">₱{{ number_format($order->delivery_fee, 2) }}</span>
                                    </div>
                                </div>
                                
                                @if($order->payment_method === 'cod')
                                    <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-xl">
                                        <div class="flex items-start space-x-3">
                                            <div class="p-1 bg-orange-100 rounded">
                                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-orange-800 text-sm">Cash on Delivery</p>
                                                <p class="text-orange-700 text-sm">Collect ₱{{ number_format($order->final_total_amount, 2) }} from customer</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    @if($order->statusHistory->count() > 0)
                    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <div class="p-2 bg-gray-50 rounded-lg mr-3">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Order Timeline
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div class="space-y-4">
                                @foreach($order->statusHistory->sortByDesc('created_at') as $index => $history)
                                <div class="flex space-x-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-3 h-3 {{ $index === 0 ? 'bg-green-500' : 'bg-gray-400' }} rounded-full"></div>
                                        @if(!$loop->last)
                                            <div class="w-px h-8 bg-gray-200 mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-8">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                            <div class="mb-2 sm:mb-0">
                                                <p class="font-semibold text-gray-900">{{ strtoupper(str_replace('_', ' ', $history->status)) }}</p>
                                                @if($history->notes)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $history->notes }}</p>
                                                @endif
                                                @if($history->updatedBy)
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Updated by: {{ $history->updatedBy->first_name }} {{ $history->updatedBy->last_name }}
                                                    </p>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500 font-medium">{{ $history->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
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
                toast.className = `flex items-center px-6 py-4 mb-2 text-sm rounded-xl shadow-lg transform transition-all duration-300 ease-in-out translate-x-full opacity-0 ${
                    type === 'success' ? 'text-white bg-green-500' :
                    type === 'error' ? 'text-white bg-red-500' :
                    'text-white bg-blue-500'
                }`;
                
                toast.innerHTML = `
                    <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        ${type === 'success' ? 
                            '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>' :
                            '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 hover:bg-black hover:bg-opacity-10 inline-flex h-8 w-8 items-center justify-center" onclick="this.parentElement.remove()">
                        <span class="sr-only">Close</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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

            // Generic button handler
            function handleButtonClick(button, endpoint) {
                const orderId = button.dataset.orderId;
                const buttonText = button.querySelector('.button-text');
                const buttonLoading = button.querySelector('.button-loading');
                
                // Show loading state
                button.disabled = true;
                buttonText.classList.add('hidden');
                buttonLoading.classList.remove('hidden');
                button.classList.add('opacity-75', 'cursor-not-allowed');
                
                fetch(`/rider/orders/${orderId}/${endpoint}`, {
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
                        button.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                        button.classList.remove('opacity-75', 'cursor-not-allowed');
                        
                        if (data.success) {
                            showToast(data.message, 'success');
                            if (data.redirect_url) {
                                setTimeout(() => window.location.href = data.redirect_url, 1500);
                            } else {
                                setTimeout(() => location.reload(), 1500);
                            }
                        } else {
                            showToast(data.message || `Failed to ${endpoint.replace('-', ' ')}.`, 'error');
                        }
                    })
                    .catch(error => {
                        // Reset button state on error
                        button.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonLoading.classList.add('hidden');
                        button.classList.remove('opacity-75', 'cursor-not-allowed');
                        console.error('Error:', error);
                        showToast('An error occurred. Please try again.', 'error');
                    });
            }

            // Handle order acceptance
            const acceptBtn = document.getElementById('accept-order-btn');
            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'accept');
                });
            }

            // Handle order decline
            const declineBtn = document.getElementById('decline-order-btn');
            if (declineBtn) {
                declineBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'decline');
                });
            }

            // Handle pickup confirmation
            const pickupBtn = document.getElementById('confirm-pickup-btn');
            if (pickupBtn) {
                pickupBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'pickup');
                });
            }

            // Handle start delivery
            const startDeliveryBtn = document.getElementById('start-delivery-btn');
            if (startDeliveryBtn) {
                startDeliveryBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'start-delivery');
                });
            }

            // Handle delivery confirmation
            const deliveredBtn = document.getElementById('mark-delivered-btn');
            if (deliveredBtn) {
                deliveredBtn.addEventListener('click', function() {
                    handleButtonClick(this, 'delivered');
                });
            }
        });
    </script>
@endsection