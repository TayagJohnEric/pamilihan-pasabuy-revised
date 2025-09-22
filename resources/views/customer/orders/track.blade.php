@extends('layout.customer')

@section('title', 'Track Order #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Track Your Delivery</h2>
                <p class="text-gray-600 mb-4">Real-time updates for Order #{{ $order->id }}</p>
                
                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('customer.orders.show', $order) }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Order Details
                    </a>
                </div>
            </div>

            <!-- Order Status Banner -->
            <div class="mb-8">
                <div class="flex items-center justify-between p-6 rounded-lg
                    @switch($order->status)
                        @case('assigned')
                            bg-blue-50 border border-blue-200
                            @break
                        @case('pickup_confirmed')
                            bg-green-50 border border-green-200
                            @break
                        @case('out_for_delivery')
                            bg-purple-50 border border-purple-200
                            @break
                        @case('delivered')
                            bg-green-50 border border-green-200
                            @break
                        @default
                            bg-gray-50 border border-gray-200
                    @endswitch">
                    
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4
                            @switch($order->status)
                                @case('assigned')
                                    bg-blue-100
                                    @break
                                @case('pickup_confirmed')
                                    bg-green-100
                                    @break
                                @case('out_for_delivery')
                                    bg-purple-100
                                    @break
                                @case('delivered')
                                    bg-green-100
                                    @break
                                @default
                                    bg-gray-100
                            @endswitch">
                            
                            @switch($order->status)
                                @case('assigned')
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    @break
                                @case('pickup_confirmed')
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    @break
                                @case('out_for_delivery')
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('delivered')
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                            @endswitch
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-bold 
                                @switch($order->status)
                                    @case('assigned')
                                        text-blue-800
                                        @break
                                    @case('pickup_confirmed')
                                        text-green-800
                                        @break
                                    @case('out_for_delivery')
                                        text-purple-800
                                        @break
                                    @case('delivered')
                                        text-green-800
                                        @break
                                    @default
                                        text-gray-800
                                @endswitch">
                                @switch($order->status)
                                    @case('assigned')
                                        Rider En Route to Pickup
                                        @break
                                    @case('pickup_confirmed')
                                        Items Picked Up
                                        @break
                                    @case('out_for_delivery')
                                        Out for Delivery
                                        @break
                                    @case('delivered')
                                        Delivered Successfully
                                        @break
                                    @default
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                @endswitch
                            </h3>
                            
                            <p class="
                                @switch($order->status)
                                    @case('assigned')
                                        text-blue-700
                                        @break
                                    @case('pickup_confirmed')
                                        text-green-700
                                        @break
                                    @case('out_for_delivery')
                                        text-purple-700
                                        @break
                                    @case('delivered')
                                        text-green-700
                                        @break
                                    @default
                                        text-gray-700
                                @endswitch">
                                @switch($order->status)
                                    @case('assigned')
                                        Your rider is heading to pick up your items from the vendors.
                                        @break
                                    @case('pickup_confirmed')
                                        All items have been collected and your rider will start delivery soon.
                                        @break
                                    @case('out_for_delivery')
                                        Your order is on its way to you!
                                        @break
                                    @case('delivered')
                                        Your order has been delivered. We hope you enjoyed your items!
                                        @break
                                    @default
                                        Your order is being processed.
                                @endswitch
                            </p>
                        </div>
                    </div>
                    
                    @if($order->status === 'delivered')
                        <div class="text-right">
                            <a href="{{ route('customer.orders.rate', $order) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                Rate Order
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Rider Information -->
                @if($order->rider)
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Delivery Rider</h3>
                        
                        <div class="flex items-center gap-4 mb-4">
                            @if($order->rider->profile_image_url)
                                <img src="{{ $order->rider->profile_image_url }}" 
                                     alt="{{ $order->rider->first_name }}"
                                     class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium text-lg">
                                        {{ substr($order->rider->first_name, 0, 1) }}{{ substr($order->rider->last_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            
                            <div>
                                <h4 class="font-medium text-gray-900 text-lg">
                                    {{ $order->rider->first_name }} {{ $order->rider->last_name }}
                                </h4>
                                @if($order->rider->phone_number)
                                    <p class="text-gray-600">{{ $order->rider->phone_number }}</p>
                                @endif
                                
                                @if($order->rider->rider && $order->rider->rider->average_rating)
                                    <div class="flex items-center mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= round($order->rider->rider->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">({{ number_format($order->rider->rider->average_rating, 1) }})</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($order->rider->rider)
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                @if($order->rider->rider->vehicle_type)
                                    <div>
                                        <span class="font-medium text-gray-700">Vehicle:</span>
                                        <span class="text-gray-900 capitalize">{{ $order->rider->rider->vehicle_type }}</span>
                                    </div>
                                @endif
                                
                                <div>
                                    <span class="font-medium text-gray-700">Deliveries:</span>
                                    <span class="text-gray-900">{{ $order->rider->rider->total_deliveries ?? 0 }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Delivery Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Address</h3>
                    
                    @if($order->deliveryAddress)
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Address:</span>
                                <p class="text-gray-900">{{ $order->deliveryAddress->address_line1 }}</p>
                            </div>
                            
                            @if($order->deliveryAddress->address_label)
                                <div>
                                    <span class="font-medium text-gray-700">Label:</span>
                                    <span class="text-gray-900">{{ $order->deliveryAddress->address_label }}</span>
                                </div>
                            @endif
                            
                            @if($order->deliveryAddress->district)
                                <div>
                                    <span class="font-medium text-gray-700">District:</span>
                                    <span class="text-gray-900">{{ $order->deliveryAddress->district->name }}, San Fernando, Pampanga</span>
                                </div>
                            @endif
                            
                            @if($order->deliveryAddress->delivery_notes)
                                <div>
                                    <span class="font-medium text-gray-700">Delivery Notes:</span>
                                    <p class="text-gray-900">{{ $order->deliveryAddress->delivery_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    @if($order->special_instructions)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="font-medium text-gray-700">Special Instructions:</span>
                            <p class="text-gray-900 mt-1">{{ $order->special_instructions }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Progress Timeline -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Order Progress</h3>
                
                <div class="relative">
                    @php
                        $statuses = [
                            'assigned' => [
                                'title' => 'Rider Assigned',
                                'description' => 'A rider has been assigned to your order',
                                'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
                            ],
                            'pickup_confirmed' => [
                                'title' => 'Items Picked Up',
                                'description' => 'All items have been collected from vendors',
                                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'
                            ],
                            'out_for_delivery' => [
                                'title' => 'Out for Delivery',
                                'description' => 'Your order is on its way to you',
                                'icon' => 'M8 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                            ],
                            'delivered' => [
                                'title' => 'Delivered',
                                'description' => 'Order has been delivered successfully',
                                'icon' => 'M5 13l4 4L19 7'
                            ]
                        ];
                        
                        $currentStatusIndex = array_search($order->status, array_keys($statuses));
                    @endphp
                    
                    @foreach($statuses as $statusKey => $statusInfo)
                        @php
                            $isCompleted = array_search($statusKey, array_keys($statuses)) <= $currentStatusIndex;
                            $isCurrent = $statusKey === $order->status;
                            
                            // Find the corresponding history entry
                            $historyEntry = $order->statusHistory->where('status', $statusKey)->first();
                        @endphp
                        
                        <div class="flex items-start gap-4 pb-8 {{ !$loop->last ? 'relative' : '' }}">
                            @if(!$loop->last)
                                <div class="absolute left-6 top-12 w-0.5 h-8 {{ $isCompleted ? 'bg-green-400' : 'bg-gray-300' }}"></div>
                            @endif
                            
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                                @if($isCurrent)
                                    bg-blue-100 border-2 border-blue-500
                                @elseif($isCompleted)
                                    bg-green-100
                                @else
                                    bg-gray-100
                                @endif">
                                
                                <svg class="w-6 h-6 
                                    @if($isCurrent)
                                        text-blue-600
                                    @elseif($isCompleted)
                                        text-green-600
                                    @else
                                        text-gray-400
                                    @endif" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusInfo['icon'] }}"></path>
                                </svg>
                            </div>
                            
                            <div class="flex-1 pt-2">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-medium 
                                        @if($isCurrent)
                                            text-blue-900
                                        @elseif($isCompleted)
                                            text-green-900
                                        @else
                                            text-gray-500
                                        @endif">
                                        {{ $statusInfo['title'] }}
                                    </h4>
                                    
                                    @if($historyEntry)
                                        <span class="text-sm text-gray-500">
                                            {{ $historyEntry->created_at->format('M d, h:i A') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-sm 
                                    @if($isCurrent)
                                        text-blue-700
                                    @elseif($isCompleted)
                                        text-green-700
                                    @else
                                        text-gray-400
                                    @endif mt-1">
                                    {{ $statusInfo['description'] }}
                                </p>
                                
                                @if($historyEntry && $historyEntry->notes)
                                    <p class="text-sm text-gray-600 mt-1">{{ $historyEntry->notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh page every 30 seconds for live updates
        @if(in_array($order->status, ['assigned', 'pickup_confirmed', 'out_for_delivery']))
            setInterval(function() {
                window.location.reload();
            }, 30000);
        @endif
    </script>
@endsection