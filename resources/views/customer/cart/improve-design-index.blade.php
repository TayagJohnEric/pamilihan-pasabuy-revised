@extends('layout.customer')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Shopping Cart')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Main Container -->
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-32 lg:pb-6">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Shopping Cart</h1>
                        <p class="text-emerald-50">Review and manage your selected items</p>
                    </div>
                    <div class="hidden sm:flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        <span class="text-white font-medium">{{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-amber-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-amber-800 font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($cartItems->count() > 0)
            <!-- Cart Items -->
            <div class="space-y-4 mb-6">
                @foreach($cartItems as $item)
                    @php
                        $isBudgetBased = !is_null($item->customer_budget);
                    @endphp
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-200">
                        <!-- Mobile Item Header -->
                        <div class="sm:hidden bg-gray-50 px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900 truncate pr-2">
                                    {{ $item->product->product_name }}
                                </h3>
                                @if($isBudgetBased)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 whitespace-nowrap">
                                        Budget-Based
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 whitespace-nowrap">
                                        Standard
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0 mx-auto sm:mx-0">
                                    @if($item->product->image_url)
                                        <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                             alt="{{ $item->product->product_name }}" 
                                             class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-xl border-2 border-gray-100">
                                    @else
                                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center border-2 border-gray-100">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-grow">
                                    <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                                        <div class="flex-grow">
                                            <!-- Desktop Title -->
                                            <h3 class="hidden sm:block text-lg font-bold text-gray-900 mb-1">
                                                {{ $item->product->product_name }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-3">
                                                by <span class="font-medium text-gray-700">{{ $item->product->vendor->vendor_name }}</span>
                                            </p>
                                            
                                            <div class="flex flex-wrap gap-2 mb-4">
                                                <div class="hidden sm:block">
                                                    @if($isBudgetBased)
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                                            </svg>
                                                            Budget-Based Purchase
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800">
                                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.51-1.31c-.562-.649-1.413-1.076-2.353-1.253V5z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Standard Purchase
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($item->product->is_budget_based && !$isBudgetBased)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                        Budget Option Available
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Price/Budget Info -->
                                        <div class="text-center sm:text-right bg-gray-50 sm:bg-transparent rounded-xl p-3 sm:p-0">
                                            @if($isBudgetBased)
                                                <div class="text-2xl font-bold text-blue-600 mb-1">
                                                    ₱{{ number_format($item->customer_budget, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">Your Budget</div>
                                            @else
                                                <div class="text-2xl font-bold text-emerald-600 mb-1">
                                                    ₱{{ number_format($item->subtotal, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ₱{{ number_format($item->product->price, 2) }} × {{ $item->quantity }} {{ $item->product->unit }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Edit Controls -->
                                    <div class="mt-6 pt-6 border-t border-gray-100">
                                        @if(!$isBudgetBased)
                                            <!-- Standard Product Controls -->
                                            <div class="space-y-4">
                                                <div class="flex flex-col sm:flex-row gap-4 items-start">
                                                    <div class="flex-grow">
                                                        <label for="quantity-{{ $item->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Quantity
                                                        </label>
                                                        <div class="flex items-center bg-white border-2 border-gray-200 rounded-xl overflow-hidden focus-within:border-emerald-500 transition-colors">
                                                            <button type="button" 
                                                                    onclick="decreaseCartQuantity({{ $item->id }})"
                                                                    class="px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors focus:outline-none focus:bg-gray-50">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                </svg>
                                                            </button>
                                                            <input type="number" 
                                                                   id="quantity-{{ $item->id }}"
                                                                   name="quantity" 
                                                                   value="{{ $item->quantity }}" 
                                                                   min="1" 
                                                                   max="{{ $item->product->is_budget_based ? 999 : $item->product->quantity_in_stock }}"
                                                                   class="w-20 px-3 py-3 text-center border-0 focus:ring-0 focus:outline-none font-semibold text-gray-900"
                                                                   data-price="{{ $item->product->price }}"
                                                                   data-item-id="{{ $item->id }}">
                                                            <button type="button" 
                                                                    onclick="increaseCartQuantity({{ $item->id }})"
                                                                    class="px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors focus:outline-none focus:bg-gray-50">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0L4 12.414a1 1 0 011.414-1.414L10 15.586l5.293-5.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                            {{ $item->product->is_budget_based ? 'Available' : $item->product->quantity_in_stock . ' available' }}
                                                        </p>
                                                    </div>

                                                    <!-- Remove Button -->
                                                    <div class="flex justify-center sm:justify-start">
                                                        <button type="button" 
                                                                onclick="removeItem({{ $item->id }})"
                                                                class="inline-flex items-center px-4 py-2.5 bg-red-50 text-red-700 rounded-xl font-semibold hover:bg-red-100 hover:text-red-800 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Budget-Based Product Controls -->
                                            <div class="space-y-4">
                                                <div class="flex flex-col lg:flex-row gap-4 items-start">
                                                    <div class="flex-grow space-y-4">
                                                        <div>
                                                            <label for="budget-{{ $item->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                                Budget Amount <span class="text-red-500">*</span>
                                                            </label>
                                                            <div class="relative">
                                                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                                                <input type="number" 
                                                                       id="budget-{{ $item->id }}"
                                                                       name="customer_budget" 
                                                                       value="{{ $item->customer_budget }}" 
                                                                       step="0.01" 
                                                                       min="0.01"
                                                                       max="999999.99"
                                                                       required
                                                                       class="pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors w-full sm:w-48 font-semibold"
                                                                       data-item-id="{{ $item->id }}">
                                                            </div>
                                                            @if($item->product->indicative_price_per_unit)
                                                                <p class="text-xs text-gray-500 mt-2 flex items-center">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    Indicative: ~₱{{ number_format($item->product->indicative_price_per_unit, 2) }}/{{ $item->product->unit }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        
                                                        <div>
                                                            <label for="notes-{{ $item->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                                Special Notes <span class="text-gray-400">(Optional)</span>
                                                            </label>
                                                            <textarea id="notes-{{ $item->id }}"
                                                                      name="customer_notes" 
                                                                      rows="3"
                                                                      placeholder="Special requests or preferences..."
                                                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                                                      data-item-id="{{ $item->id }}">{{ $item->customer_notes }}</textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Remove Button -->
                                                    <div class="flex justify-center lg:justify-start">
                                                        <button type="button" 
                                                                onclick="removeItem({{ $item->id }})"
                                                                class="inline-flex items-center px-4 py-2.5 bg-red-50 text-red-700 rounded-xl font-semibold hover:bg-red-100 hover:text-red-800 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Hidden Remove Form -->
                                        <form id="remove-form-{{ $item->id }}" method="POST" action="{{ route('cart.destroy', $item) }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>

                                    <!-- Display notes for budget-based items -->
                                    @if($isBudgetBased && $item->customer_notes)
                                        <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-xl">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-semibold text-blue-800 mb-1">Special Notes:</p>
                                                    <p class="text-sm text-blue-700">{{ $item->customer_notes }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Convert to Budget Option -->
                                    @if($item->product->is_budget_based && !$isBudgetBased)
                                        <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-blue-800">Want a custom quote instead?</p>
                                                        <p class="text-xs text-blue-600 mt-0.5">Switch to budget-based pricing for this item</p>
                                                    </div>
                                                </div>
                                                <button type="button" 
                                                        onclick="showBudgetConversion({{ $item->id }})"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm whitespace-nowrap">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                    </svg>
                                                    Switch to Budget
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Hidden Budget Conversion Form -->
                                        <div id="budget-conversion-{{ $item->id }}" class="hidden mt-4 p-6 bg-blue-50 border-2 border-blue-200 rounded-xl" data-item-id="{{ $item->id }}">
                                            <form method="POST" action="{{ route('cart.update', $item) }}" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Your Budget <span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="relative">
                                                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">₱</span>
                                                        <input type="number" 
                                                               name="customer_budget" 
                                                               step="0.01" 
                                                               min="0.01"
                                                               max="999999.99"
                                                               required
                                                               placeholder="Enter your budget"
                                                               class="pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors w-full font-semibold">
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Special Notes <span class="text-gray-400">(Optional)</span>
                                                    </label>
                                                    <textarea name="customer_notes" 
                                                              rows="3"
                                                              placeholder="Any special requirements..."
                                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
                                                </div>
                                                
                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Convert to Budget
                                                    </button>
                                                    <button type="button" 
                                                            onclick="hideBudgetConversion({{ $item->id }})"
                                                            class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <!-- Empty Cart -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="text-center py-16 px-6">
                    <div class="w-32 h-32 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0h8m-8 0H9m4 0h2"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Your cart is empty</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">Discover amazing products from our vendors and start building your perfect order.</p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-bold hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Mobile Sticky Cart Summary -->
    @if($cartItems->count() > 0)
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t-2 border-gray-200 shadow-2xl z-50">
            <div class="px-4 py-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-xl font-bold text-gray-900">
                            Total: <span class="text-emerald-600">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($cartItems->where('customer_budget', '!=', null)->count() > 0)
                            <p class="text-blue-600 text-xs mt-1 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Budget items subject to vendor confirmation
                            </p>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }}
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="clearCart()" 
                            class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Clear Cart
                    </button>
                    
                    <a href="#" 
                       class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-bold text-center hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Checkout
                    </a>
                </div>
            </div>
        </div>

        <!-- Desktop Cart Summary -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-t-4 border-emerald-500">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="text-left lg:text-right order-2 lg:order-1">
                        <div class="text-3xl font-bold text-gray-900 mb-2">
                            Total: <span class="text-emerald-600">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                            {{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }} in cart
                        </div>
                        @if($cartItems->where('customer_budget', '!=', null)->count() > 0)
                            <p class="text-blue-600 text-sm flex items-center justify-end">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Budget-based items subject to vendor confirmation
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 order-1 lg:order-2">
                        <button onclick="clearCart()" 
                                class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Clear Cart
                        </button>
                        
                        <a href="#" 
                           class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-bold hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Clear Cart Form -->
        <form id="clear-cart-form" method="POST" action="{{ route('cart.clear') }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    // Prevent double submissions
    let isSubmitting = false;
    
    // For standard product (non-budget based) - auto-update on quantity change
    $('input[name="quantity"]').on('change', function (e) {
        if (isSubmitting) return;
        
        let input = $(this);
        let quantity = input.val();
        let cartItemId = input.data('item-id') || input.attr('id').split('-')[1];

        if (!cartItemId || !quantity) return;

        isSubmitting = true;
        input.prop('disabled', true);

        $.ajax({
            url: `/cart/${cartItemId}`,
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: quantity
            },
            success: function (response) {
                if (response.success && response.cart_summary) {
                    updateCartSummary(response.cart_summary);
                    
                    // Show subtle success indication
                    input.addClass('border-emerald-300').removeClass('border-red-300');
                    setTimeout(() => input.removeClass('border-emerald-300'), 2000);
                }
            },
            error: function (xhr) {
                handleAjaxError(xhr, input);
            },
            complete: function() {
                isSubmitting = false;
                input.prop('disabled', false);
            }
        });
    });

    // For budget-based products - auto-update on budget or notes change
    $('input[name="customer_budget"], textarea[name="customer_notes"]').on('change', function (e) {
        if (isSubmitting) return;
        
        let input = $(this);
        let cartItemId = input.data('item-id');
        
        if (!cartItemId) return;
        
        let budgetInput = $(`input[name="customer_budget"][data-item-id="${cartItemId}"]`);
        let notesInput = $(`textarea[name="customer_notes"][data-item-id="${cartItemId}"]`);
        
        // Clear any pending timeout
        clearTimeout(window.budgetUpdateTimeout);
        
        // Validate budget input
        if (budgetInput.length > 0) {
            const value = parseFloat(budgetInput.val());
            const min = parseFloat(budgetInput.attr('min'));
            const max = parseFloat(budgetInput.attr('max'));
            
            if (isNaN(value) || value < min || value > max) {
                showErrorMessage('Please enter a valid budget amount between ₱' + min.toLocaleString() + ' and ₱' + max.toLocaleString());
                budgetInput.focus().addClass('border-red-300');
                return;
            }
        }
        
        // Auto-update after a short delay
        window.budgetUpdateTimeout = setTimeout(function() {
            if (isSubmitting) return;
            
            isSubmitting = true;
            input.prop('disabled', true);
            
            $.ajax({
                url: `/cart/${cartItemId}`,
                method: 'POST',
                data: {
                    _method: 'PUT',
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    customer_budget: budgetInput.val(),
                    customer_notes: notesInput.val()
                },
                success: function (response) {
                    if (response.success && response.cart_summary) {
                        updateCartSummary(response.cart_summary);
                        
                        // Show subtle success indication
                        input.addClass('border-emerald-300').removeClass('border-red-300');
                        setTimeout(() => input.removeClass('border-emerald-300'), 2000);
                    }
                },
                error: function (xhr) {
                    handleAjaxError(xhr, input);
                },
                complete: function() {
                    isSubmitting = false;
                    input.prop('disabled', false);
                }
            });
        }, 1000);
    });

    // Handle budget conversion form submission with enhanced error handling
    $('form').on('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }
        
        const form = $(this);
        const budgetInput = form.find('input[name="customer_budget"]');
        const isBudgetConversion = form.closest('[id^="budget-conversion-"]').length > 0;
        
        // Validate budget input if present
        if (budgetInput.length > 0) {
            const value = parseFloat(budgetInput.val());
            const min = parseFloat(budgetInput.attr('min'));
            const max = parseFloat(budgetInput.attr('max'));
            
            if (isNaN(value) || value < min || value > max) {
                e.preventDefault();
                showErrorMessage('Please enter a valid budget amount between ₱' + min.toLocaleString() + ' and ₱' + max.toLocaleString());
                budgetInput.focus().addClass('border-red-300');
                return false;
            }
        }
        
        // Handle budget conversion forms with AJAX
        if (isBudgetConversion) {
            e.preventDefault();
            
            isSubmitting = true;
            const submitBtn = form.find('button[type="submit"]');
            const originalText = submitBtn.text();
            submitBtn.prop('disabled', true).text('Converting...');
            
            const formData = new FormData(form[0]);
            const cartItemId = form.closest('[id^="budget-conversion-"]').data('item-id');
            
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        showSuccessMessage(response.message || 'Item converted successfully!');
                        
                        // Hide the conversion form
                        hideBudgetConversion(cartItemId);
                        
                        // Update cart summary if available
                        if (response.cart_summary) {
                            updateCartSummary(response.cart_summary);
                        }
                        
                        // Reload the page to show the updated cart structure
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showErrorMessage(response.message || 'Conversion failed. Please try again.');
                    }
                },
                error: function (xhr) {
                    handleAjaxError(xhr, budgetInput);
                },
                complete: function() {
                    isSubmitting = false;
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
            
            return false;
        }
    });

    // Store previous values for error handling
    $('input[name="quantity"]').each(function() {
        $(this).data('previous-value', $(this).val());
    });

    $('input[name="quantity"]').on('focus', function() {
        $(this).data('previous-value', $(this).val());
    });

    // Enhanced error handling function
    function handleAjaxError(xhr, inputElement) {
        let errorMessage = 'An error occurred. Please try again.';
        
        if (xhr.status === 422) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                errorMessage = Object.values(errors).flat().join('\n');
            }
        } else if (xhr.status === 404) {
            errorMessage = 'Item not found. Please refresh the page.';
        } else if (xhr.responseJSON?.message) {
            errorMessage = xhr.responseJSON.message;
        }
        
        showErrorMessage(errorMessage);
        
        if (inputElement) {
            inputElement.addClass('border-red-300');
            // Reset to previous value for quantity inputs
            if (inputElement.attr('name') === 'quantity') {
                const previousValue = inputElement.data('previous-value');
                if (previousValue) {
                    inputElement.val(previousValue);
                }
            }
        }
    }
});

// Enhanced message functions with improved animations and positioning
function showSuccessMessage(message) {
    // Remove any existing messages
    $('.temp-alert').remove();
    
    //Toast Success
    const alertDiv = $(`
        <div class="temp-alert fixed top-4 right-4 z-50 transform translate-x-full opacity-0 transition-all duration-300 ease-out">
            <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-r-xl shadow-lg max-w-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-emerald-800">${message}</p>
                    </div>
                </div>
            </div>
        </div>
    `);
    
    $('body').append(alertDiv);
    
    // Animate in
    setTimeout(() => {
        alertDiv.removeClass('translate-x-full opacity-0');
    }, 100);
    
    // Remove after 4 seconds with animation
    setTimeout(function() {
        alertDiv.addClass('translate-x-full opacity-0');
        setTimeout(() => alertDiv.remove(), 300);
    }, 4000);
}

function showErrorMessage(message) {
    // Remove any existing messages
    $('.temp-alert').remove();
    
    //Toast Error
    const alertDiv = $(`
        <div class="temp-alert fixed top-4 right-4 z-50 transform translate-x-full opacity-0 transition-all duration-300 ease-out">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-xl shadow-lg max-w-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800">${message}</p>
                    </div>
                </div>
            </div>
        </div>
    `);
    
    $('body').append(alertDiv);
    
    // Animate in
    setTimeout(() => {
        alertDiv.removeClass('translate-x-full opacity-0');
    }, 100);
    
    // Remove after 5 seconds with animation
    setTimeout(function() {
        alertDiv.addClass('translate-x-full opacity-0');
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}

// Function to update cart summary without page reload
function updateCartSummary(cartSummary) {
    // Update the cart total in both mobile and desktop views
    $('.text-emerald-600').each(function() {
        if ($(this).parent().text().includes('Total:')) {
            $(this).text('₱' + parseFloat(cartSummary.subtotal).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        }
    });
    
    // Update cart count in header if exists
    $('.cart-count').text(cartSummary.item_count);
    
    // Update item count displays
    $('text').each(function() {
        if ($(this).text().includes('item')) {
            const count = cartSummary.item_count;
            $(this).text(`${count} ${count === 1 ? 'item' : 'items'}`);
        }
    });
}

// Existing functions for UI interactions
function removeItem(itemId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        // Use form submission for delete operations to avoid AJAX issues
        document.getElementById('remove-form-' + itemId).submit();
    }
}

function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart? This action cannot be undone.')) {
        document.getElementById('clear-cart-form').submit();
    }
}

function showBudgetConversion(itemId) {
    const element = document.getElementById('budget-conversion-' + itemId);
    element.classList.remove('hidden');
    // Add smooth reveal animation
    element.style.opacity = '0';
    element.style.transform = 'translateY(-10px)';
    setTimeout(() => {
        element.style.transition = 'all 0.3s ease-out';
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
    }, 10);
}

function hideBudgetConversion(itemId) {
    const element = document.getElementById('budget-conversion-' + itemId);
    element.style.transition = 'all 0.3s ease-in';
    element.style.opacity = '0';
    element.style.transform = 'translateY(-10px)';
    setTimeout(() => {
        element.classList.add('hidden');
    }, 300);
}

function increaseCartQuantity(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);

    if (current < max) {
        input.value = current + 1;
        updateCartItemTotal(itemId);
    }
}

function decreaseCartQuantity(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const current = parseInt(input.value);

    if (current > 1) {
        input.value = current - 1;
        updateCartItemTotal(itemId);
    }
}

function updateCartItemTotal(itemId) {
    const quantityInput = document.getElementById('quantity-' + itemId);
    if (quantityInput) {
        // Store previous value before triggering change
        $(quantityInput).data('previous-value', quantityInput.value);
        // Trigger the change event to update via AJAX
        quantityInput.dispatchEvent(new Event('change'));
    }
}
    
</script>
@endsection