@extends('layout.customer')

@section('title', $rider->first_name . ' ' . $rider->last_name . ' - Rider Profile')

@section('content')

<style>
     /* Custom animations */
     @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Set initial state to guarantee the animation starts from hidden/offset state */
    .animate-slide-in {
        opacity: 0;
        transform: translateY(16px);
        will-change: transform, opacity;
        animation: slideIn 0.4s ease-out forwards;
    }

</style>
    <div class="max-w-[90rem] mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('merit-system.index') }}" class="inline-flex items-center space-x-2 text-gray-600 hover:text-gray-800 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to All Riders</span>
            </a>
        </div>

        <!-- Main Profile Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6 animate-slide-in">
            <!-- Header Section with Cover -->
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 px-8 py-12 relative">
                <div class="flex items-center space-x-8">
                    <!-- Profile Image -->
                    <div class="w-32 h-32 bg-white rounded-full overflow-hidden border-4 border-white shadow-lg flex-shrink-0">
                        @if($rider->profile_image_url)
                            <img src="{{ asset('storage/' . $rider->profile_image_url) }}" alt="{{ $rider->first_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500 text-4xl font-bold">
                                {{ strtoupper(substr($rider->first_name, 0, 1)) }}{{ strtoupper(substr($rider->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Rider Info -->
                    <div class="flex-grow text-white">
                        <div class="flex items-center space-x-4 mb-2">
                            <h1 class="text-4xl font-bold">{{ $rider->first_name }} {{ $rider->last_name }}</h1>
                            
                            <!-- Availability Status -->
                            <div class="flex items-center space-x-2 bg-white bg-opacity-20 rounded-full px-4 py-2">
                                <div class="w-3 h-3 rounded-full {{ $rider->is_available ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                <span class="font-medium">{{ $rider->is_available ? 'Available Now' : 'Currently Busy' }}</span>
                            </div>
                        </div>
                        
                        <p class="text-xl mb-4 capitalize opacity-90">
                            {{ $rider->vehicle_type ? ucfirst($rider->vehicle_type) . ' Delivery Partner' : 'Delivery Partner' }}
                        </p>
                        
                        <div class="flex items-center space-x-6">
                            <!-- Merit Score -->
                            <div class="bg-white bg-opacity-20 rounded-lg px-6 py-3">
                                <div class="text-3xl font-bold">{{ number_format($rider->merit_score, 1) }}</div>
                                <div class="text-sm opacity-80">Merit Score</div>
                            </div>
                            
                            <!-- Rating -->
                            <div class="bg-white bg-opacity-20 rounded-lg px-6 py-3">
                                <div class="text-3xl font-bold flex items-center space-x-2">
                                    <span>{{ number_format($rider->average_rating ?: 0, 1) }}</span>
                                    <span class="text-yellow-300">★</span>
                                </div>
                                <div class="text-sm opacity-80">Average Rating</div>
                            </div>
                            
                            <!-- Total Deliveries -->
                            <div class="bg-white bg-opacity-20 rounded-lg px-6 py-3">
                                <div class="text-3xl font-bold">{{ number_format($rider->total_deliveries) }}</div>
                                <div class="text-sm opacity-80">Total Deliveries</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-slide-in">
            <!-- Left Column - Statistics and Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Statistics</h3>
                    
                    <div class="space-y-4">
                        <!-- Merit Components Breakdown -->
                        <div class="border-b pb-4">
                            <h4 class="font-medium text-gray-700 mb-3">Merit Score Breakdown</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rating Score (60%):</span>
                                    <span class="font-medium">{{ number_format(($rider->average_rating ?: 0) * 0.6, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Experience Score (30%):</span>
                                    <span class="font-medium">{{ number_format(log($rider->total_deliveries + 1) * 0.3, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Engagement Score (10%):</span>
                                    <span class="font-medium">{{ number_format($rider->comment_count * 0.1, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Stats -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_ratings'] }}</div>
                                <div class="text-xs text-gray-500">Total Reviews</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $stats['monthly_deliveries'] }}</div>
                                <div class="text-xs text-gray-500">This Month</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Distribution -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Rating Distribution</h3>
                    
                    @if($stats['total_ratings'] > 0)
                        <div class="space-y-3">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $stats['rating_distribution'][$i] ?? 0;
                                    $percentage = $stats['total_ratings'] > 0 ? ($count / $stats['total_ratings']) * 100 : 0;
                                @endphp
                                
                                <div class="flex items-center space-x-3">
                                    <span class="text-sm font-medium w-8">{{ $i }}★</span>
                                    <div class="flex-grow bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No ratings yet</p>
                    @endif
                </div>

                <!-- Rider Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Rider Information</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $rider->email }}</span>
                        </div>
                        
                        @if($rider->phone_number)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $rider->phone_number }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vehicle Type:</span>
                            <span class="font-medium capitalize">{{ $rider->vehicle_type ?: 'Not specified' }}</span>
                        </div>
                        
                        @if($rider->license_number)
                            <div class="flex justify-between">
                                <span class="text-gray-600">License:</span>
                                <span class="font-medium">{{ $rider->license_number }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Joined:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($rider->created_at)->format('M Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium text-green-600 capitalize">{{ $rider->verification_status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Recent Reviews -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Recent Customer Reviews</h3>
                    
                    @if($recentRatings->count() > 0)
                        <div class="space-y-6">
                            @foreach($recentRatings as $rating)
                                <div class="border-b border-gray-200 last:border-0 pb-6 last:pb-0">
                                    <!-- Review Header -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-semibold text-gray-600">
                                                    {{ strtoupper(substr($rating->customer_first_name, 0, 1)) }}{{ strtoupper(substr($rating->customer_last_name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-800">
                                                    {{ $rating->customer_first_name }} {{ $rating->customer_last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Order #{{ $rating->order_id }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <!-- Rating Stars -->
                                            <div class="flex items-center space-x-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $rating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            
                                            <!-- Date -->
                                            <span class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($rating->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Review Comment -->
                                    <div class="pl-13">
                                        <p class="text-gray-700 leading-relaxed">{{ $rating->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Show More Reviews Button -->
                        <div class="text-center mt-6 pt-6 border-t">
                            <button class="text-blue-600 hover:text-blue-800 font-medium">
                                View All Reviews ({{ $stats['total_ratings'] }})
                            </button>
                        </div>
                    @else
                        <!-- No Reviews Yet -->
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-5xl mb-4">💬</div>
                            <h4 class="text-lg font-semibold text-gray-600 mb-2">No Reviews Yet</h4>
                            <p class="text-gray-500">This rider hasn't received any customer reviews with comments yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- JavaScript for interactions -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle "Select This Rider" button
            $('button:contains("Select This Rider")').on('click', function() {
                // This would typically integrate with your order placement system
                // For now, we'll show a success message
                const $button = $(this);
                const originalText = $button.text();
                
                $button.prop('disabled', true)
                       .text('Selected!')
                       .removeClass('bg-green-500 hover:bg-green-600')
                       .addClass('bg-green-600');
                
                // In a real application, you would:
                // 1. Store the preferred rider selection
                // 2. Redirect to the order placement page
                // 3. Or add to a preferred riders list
                
                setTimeout(function() {
                    // Redirect to orders or show next steps
                    // window.location.href = '/orders/create?preferred_rider=' + {{ $rider->id }};
                    
                    // For demo purposes, just reset the button
                    $button.prop('disabled', false)
                           .text(originalText)
                           .removeClass('bg-green-600')
                           .addClass('bg-green-500 hover:bg-green-600');
                }, 2000);
            });

        });
    </script>
@endsection