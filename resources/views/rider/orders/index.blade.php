@extends('layout.rider')

@section('title', 'Order Management Dashboard')

@section('content')
    <div class="max-w-[90rem] mx-auto space-y-6">
        
        <!-- Rider Status Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Delivery Dashboard</h2>
                    <p class="text-gray-600">Manage your delivery assignments and track your progress</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Availability Toggle -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700">Available for Deliveries:</span>
                        <button id="availability-toggle" 
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 {{ Auth::user()->rider->is_available ? 'bg-green-600' : 'bg-gray-200' }}"
                                data-available="{{ Auth::user()->rider->is_available ? 'true' : 'false' }}">
                            <span class="sr-only">Toggle availability</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ Auth::user()->rider->is_available ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                    <!-- Status Indicator -->
                    <div class="flex items-center space-x-2">
                        <div class="h-3 w-3 rounded-full {{ Auth::user()->rider->is_available ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                        <span class="text-sm font-medium {{ Auth::user()->rider->is_available ? 'text-green-600' : 'text-gray-600' }}">
                            {{ Auth::user()->rider->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Assignments Section -->
        @if($pendingAssignments->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending Assignments
                </h3>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    {{ $pendingAssignments->count() }} pending
                </span>
            </div>
            <div class="grid gap-4">
                @foreach($pendingAssignments as $order)
                <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-2">
                                <h4 class="text-lg font-semibold text-gray-800">Order #{{ $order->id }}</h4>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">ACTION REQUIRED</span>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <p><span class="font-medium">Customer:</span> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                                    <p><span class="font-medium">Phone:</span> {{ $order->customer->phone_number ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p><span class="font-medium">Delivery Fee:</span> ₱{{ number_format($order->delivery_fee, 2) }}</p>
                                    <p><span class="font-medium">Payment:</span> {{ strtoupper($order->payment_method) }}</p>
                                </div>
                            </div>
                            <!-- Pickup Locations -->
                            <div class="mt-3">
                                <p class="font-medium text-gray-700 mb-1">Pickup Locations:</p>
                                <div class="text-sm text-gray-600">
                                    @php
                                        $vendors = $order->orderItems->pluck('product.vendor')->unique('id');
                                    @endphp
                                    @foreach($vendors as $vendor)
                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 mb-1">
                                            Stall {{ $vendor->stall_number ?? 'N/A' }} - {{ $vendor->vendor_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Delivery Address -->
                            <div class="mt-2">
                                <p class="font-medium text-gray-700">Delivery to:</p>
                                <p class="text-sm text-gray-600">{{ $order->deliveryAddress->address_line1 }}, {{ $order->deliveryAddress->district->name }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button class="accept-order-btn px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium"
                                    data-order-id="{{ $order->id }}">
                                Accept
                            </button>
                            <button class="decline-order-btn px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium"
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
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Active Deliveries
                </h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ $activeDeliveries->count() }} active
                </span>
            </div>
            <div class="grid gap-4">
                @foreach($activeDeliveries as $order)
                <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-2">
                                <h4 class="text-lg font-semibold text-gray-800">Order #{{ $order->id }}</h4>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">IN PROGRESS</span>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <p><span class="font-medium">Customer:</span> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                                    <p><span class="font-medium">Phone:</span> {{ $order->customer->phone_number ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p><span class="font-medium">Total Amount:</span> ₱{{ number_format($order->final_total_amount, 2) }}</p>
                                    <p><span class="font-medium">Payment:</span> {{ strtoupper($order->payment_method) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <a href="{{ route('rider.orders.show', $order) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
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
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2m0 0V6a2 2 0 012-2h2"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">No Active Orders</h3>
            <p class="text-gray-400 mb-4">
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
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Recent Deliveries
            </h3>
            <div class="space-y-3">
                @foreach($deliveryHistory as $order)
                <div class="border rounded-lg p-4 {{ $order->status === 'delivered' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <span class="font-semibold text-gray-800">Order #{{ $order->id }}</span>
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                <span>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</span>
                                <span class="mx-2">•</span>
                                <span>₱{{ number_format($order->delivery_fee, 2) }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $order->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Performance Stats -->
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-800">Total Deliveries</h4>
                        <p class="text-2xl font-bold text-blue-600">{{ Auth::user()->rider->total_deliveries }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-800">Today's Deliveries</h4>
                        <p class="text-2xl font-bold text-green-600">{{ Auth::user()->rider->daily_deliveries }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-gray-800">Average Rating</h4>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ Auth::user()->rider->average_rating ? number_format(Auth::user()->rider->average_rating, 1) : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // CSRF token for AJAX requests
            const csrfToken = '{{ csrf_token() }}';

            // Show/hide loading overlay
            function showLoading() {
                document.getElementById('loading-overlay').classList.remove('hidden');
            }

            function hideLoading() {
                document.getElementById('loading-overlay').classList.add('hidden');
            }

            // Handle availability toggle
            document.getElementById('availability-toggle').addEventListener('click', function() {
                showLoading();
                
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
                    hideLoading();
                    
                    if (data.success) {
                        // Update toggle appearance
                        const toggle = document.getElementById('availability-toggle');
                        const indicator = toggle.querySelector('span:last-child');
                        const statusDot = document.querySelector('.h-3.w-3.rounded-full');
                        const statusText = statusDot.nextElementSibling;
                        
                        if (data.is_available) {
                            toggle.classList.add('bg-green-600');
                            toggle.classList.remove('bg-gray-200');
                            indicator.classList.add('translate-x-6');
                            indicator.classList.remove('translate-x-1');
                            statusDot.classList.add('bg-green-500');
                            statusDot.classList.remove('bg-gray-400');
                            statusText.textContent = 'Available';
                            statusText.classList.add('text-green-600');
                            statusText.classList.remove('text-gray-600');
                        } else {
                            toggle.classList.remove('bg-green-600');
                            toggle.classList.add('bg-gray-200');
                            indicator.classList.remove('translate-x-6');
                            indicator.classList.add('translate-x-1');
                            statusDot.classList.remove('bg-green-500');
                            statusDot.classList.add('bg-gray-400');
                            statusText.textContent = 'Unavailable';
                            statusText.classList.remove('text-green-600');
                            statusText.classList.add('text-gray-600');
                        }
                        
                        // Show success message
                        alert(data.message);
                    } else {
                        alert(data.message || 'Failed to update availability status.');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });

            // Handle order acceptance
            document.querySelectorAll('.accept-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    
                    if (confirm('Are you sure you want to accept this delivery?')) {
                        showLoading();
                        
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
                            hideLoading();
                            
                            if (data.success) {
                                alert(data.message);
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    location.reload();
                                }
                            } else {
                                alert(data.message || 'Failed to accept order.');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            });

            // Handle order decline
            document.querySelectorAll('.decline-order-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const orderId = this.dataset.orderId;
                    
                    if (confirm('Are you sure you want to decline this delivery? It will be assigned to another rider.')) {
                        showLoading();
                        
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
                            hideLoading();
                            
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to decline order.');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            });
        });
    </script>
@endsection