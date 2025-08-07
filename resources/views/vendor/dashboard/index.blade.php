@extends('layout.vendor')
@section('title', 'Vendor Dashboard')


<style>
.modal-overlay {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    transform: scale(0.7) translateY(-50px);
    opacity: 0;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1) translateY(0);
    opacity: 1;
}

.modal-overlay.closing {
    opacity: 0;
    visibility: hidden;
}

.modal-overlay.closing .modal-content {
    transform: scale(0.7) translateY(-50px);
    opacity: 0;
}
</style>



@section('content')
<div class="max-w-[90rem] mx-auto space-y-6">
    
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2">Welcome back, {{ $vendor->vendor_name }}!</h1>
                <p class="text-blue-100 opacity-90">Here's what's happening with your shop today</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white bg-opacity-20 backdrop-blur-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ number_format($stats['shop_rating'], 1) }} Rating
                </span>
            </div>
            
           <!-- Accepting Order Toggle Switch with Modal Trigger -->
<div class="mb-4 flex items-center justify-between">
    <label for="toggle_orders" class="flex items-center cursor-pointer">
        <span class="mr-3 text-white">Accepting Orders</span>
        <div class="relative w-14 h-8">
            <input
    type="checkbox"
    id="toggle_orders"
    class="sr-only peer"
    onchange="handleToggle(event)"
    {{ $vendor->is_accepting_orders ? 'checked' : '' }}
>
            <div class="w-full h-full bg-[#d4d9de] rounded-full shadow-inner transition-all duration-300 peer-checked:bg-green-500"></div>
            <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-6"></div>
        </div>
    </label>
</div>


        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <!-- Pending Orders Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Pending Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_orders'] }}</p>
                    <p class="text-sm text-orange-600 mt-1 font-medium">Requires attention</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Today Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Sales Today</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₱{{ number_format($stats['sales_today'], 2) }}</p>
                    <p class="text-sm text-green-600 mt-1 font-medium">From delivered orders</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop Rating Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Shop Rating</p>
                    <div class="flex items-center mt-2">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['shop_rating'], 1) }}</p>
                        <div class="ml-2 flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $stats['shop_rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-yellow-600 mt-1 font-medium">Customer feedback</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unsettled Earnings Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Unsettled Earnings</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₱{{ number_format($stats['unsettled_earnings'], 2) }}</p>
                    <p class="text-sm text-purple-600 mt-1 font-medium">Since last payout</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Urgent Orders Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Urgent Orders Awaiting Processing
                    </h2>
                    @if($urgentOrders->count() > 0)
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $urgentOrders->count() }} pending
                        </span>
                    @endif
                </div>
                
                @if($urgentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($urgentOrders as $order)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-300 hover:bg-orange-50 transition-colors duration-200 cursor-pointer" 
                                 onclick="window.location.href='{{ route('vendor.orders.show', $order['id']) ?? '#' }}'">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Order #{{ $order['id'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $order['items_count'] }} {{ Str::plural('item', $order['items_count']) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-orange-600">{{ $order['time_elapsed'] }}</p>
                                        <p class="text-xs text-gray-500">Click to process</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">All caught up!</h3>
                        <p class="mt-1 text-sm text-gray-500">No pending orders at the moment.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 17h5l-5 5v-5zM4 7h5L4 2v5zM15 7h5l-5-5v5z"/>
                    </svg>
                    Recent Activity
                </h2>
                
                @if($recentActivity->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivity as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if($activity['icon'] === 'star')
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 17h5l-5 5v-5zM4 7h5L4 2v5zM15 7h5l-5-5v5z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity['created_at']->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 17h5l-5 5v-5zM4 7h5L4 2v5zM15 7h5l-5-5v5z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No recent activity</h3>
                        <p class="mt-1 text-sm text-gray-500">Activity will appear here as it happens.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Modal for Aceppting Orders Confirmation -->
<div id="toggle-modal" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="modal-content bg-white rounded-lg p-6 max-w-md w-full">
        <h2 class="text-lg font-semibold mb-4">Confirm Toggle</h2>
        <p class="mb-4">Are you ready to start accepting orders? If not, please make sure your products are prepared before enabling this option.</p>
        <div class="flex justify-end space-x-2">
            <button onclick="closeToggleModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button onclick="confirmToggle()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Yes, I'm Ready</button>
        </div>
    </div>
</div>

<!--JQuery CDN for AJAX Purposes-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#toggle_orders').on('change', function () {
            let isAccepting = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('vendor.toggle.accepting') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_accepting_orders: isAccepting
                },
                success: function (response) {
                    console.log(response.message);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

     let toggleCheckbox;

    function handleToggle(event) {
        toggleCheckbox = event.target;

        if (toggleCheckbox.checked) {
            // Revert toggle visually and show modal
            toggleCheckbox.checked = false;

            const modal = document.getElementById('toggle-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => modal.classList.add('show'), 10);
        } else {
            // Allow direct OFF toggle without confirmation
            triggerToggleAjax(0);
        }
    }

    function closeToggleModal() {
        const modal = document.getElementById('toggle-modal');
        modal.classList.add('closing');
        modal.classList.remove('show');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'closing');
            toggleCheckbox.checked = false;
        }, 300);
    }

    function confirmToggle() {
        const modal = document.getElementById('toggle-modal');
        modal.classList.add('closing');
        modal.classList.remove('show');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'closing');
            toggleCheckbox.checked = true;
            triggerToggleAjax(1);
        }, 300);
    }

    function triggerToggleAjax(value) {
        $.ajax({
            url: '{{ route('vendor.toggle.accepting') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                is_accepting_orders: value
            },
            success: function (response) {
                console.log(response.message);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }
</script>
@endsection