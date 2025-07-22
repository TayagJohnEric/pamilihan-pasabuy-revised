@extends('layout.rider')
@section('title', 'Customer Ratings & Feedback')
@section('content')
<div class="max-w-[90rem] mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Customer Ratings & Feedback</h1>
                <p class="text-gray-600 mt-1">See what customers are saying about your delivery service</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <a href="{{ route('rider.earnings') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    Back to Earnings
                </a>
                <a href="{{ route('rider.payouts') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    View Payouts
                </a>
            </div>
        </div>
    </div>

    <!-- Rating Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Overall Rating -->
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Overall Rating</p>
                    <div class="flex items-center mt-1">
                        <p class="text-3xl font-bold">{{ $totalRatings > 0 ? number_format($averageRating, 1) : '0.0' }}</p>
                        <svg class="w-6 h-6 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <p class="text-yellow-100 text-sm">Based on {{ $totalRatings }} ratings</p>
                </div>
                <div class="bg-yellow-300 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Reviews -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Reviews</p>
                    <p class="text-3xl font-bold">{{ $totalRatings }}</p>
                    <p class="text-blue-100 text-sm">Customer feedback</p>
                </div>
                <div class="bg-blue-500 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 5-Star Percentage -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Excellent Rating</p>
                    <p class="text-3xl font-bold">{{ $totalRatings > 0 ? number_format($ratingDistribution[5]['percentage'], 1) : 0 }}%</p>
                    <p class="text-green-100 text-sm">5-star reviews</p>
                </div>
                <div class="bg-green-500 p-3 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Distribution & Recent Feedback -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Rating Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rating Distribution</h3>
            <div class="space-y-3">
                @for($i = 5; $i >= 1; $i--)
                <div class="flex items-center">
                    <div class="flex items-center w-16">
                        <span class="text-sm font-medium text-gray-600">{{ $i }}</span>
                        <svg class="w-4 h-4 text-yellow-400 ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div class="flex-1 mx-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $ratingDistribution[$i]['percentage'] }}%"></div>
                        </div>
                    </div>
                    <div class="w-12 text-right">
                        <span class="text-sm text-gray-600">{{ $ratingDistribution[$i]['count'] }}</span>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Quick Feedback Overview -->
        <div class="space-y-6">
            <!-- Recent Positive Feedback -->
            @if($recentPositive->count() > 0)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Recent Positive Feedback
                </h4>
                <div class="space-y-2">
                    @foreach($recentPositive->take(2) as $rating)
                    <div class="bg-white p-3 rounded border border-green-100">
                        <div class="flex items-center mb-1">
                            @for($j = 1; $j <= 5; $j++)
                                <svg class="w-4 h-4 {{ $j <= $rating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="ml-2 text-xs text-gray-500">{{ $rating->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-sm text-gray-700">{{ Str::limit($rating->comment, 120) }}</p>
                        @if($rating->order && $rating->order->customer)
                        <p class="text-xs text-gray-500 mt-1">Order #{{ $rating->order->id }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Negative Feedback -->
            @if($recentNegative->count() > 0)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-red-800 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Areas for Improvement
                </h4>
                <div class="space-y-2">
                    @foreach($recentNegative->take(2) as $rating)
                    <div class="bg-white p-3 rounded border border-red-100">
                        <div class="flex items-center mb-1">
                            @for($j = 1; $j <= 5; $j++)
                                <svg class="w-4 h-4 {{ $j <= $rating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="ml-2 text-xs text-gray-500">{{ $rating->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-sm text-gray-700">{{ Str::limit($rating->comment, 120) }}</p>
                        @if($rating->order && $rating->order->customer)
                        <p class="text-xs text-gray-500 mt-1">Order #{{ $rating->order->id }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($recentPositive->count() == 0 && $recentNegative->count() == 0)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                <p class="text-gray-500 text-sm">No recent feedback with comments yet</p>
                <p class="text-gray-400 text-xs mt-1">Complete more deliveries to start receiving feedback</p>
            </div>
            @endif
        </div>
    </div>

    <!-- All Ratings List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">All Customer Ratings</h3>
            <p class="text-sm text-gray-600">Complete history of customer feedback on your deliveries</p>
        </div>
        
        <div class="p-6">
            @if($ratings->count() > 0)
                <div class="space-y-4">
                    @foreach($ratings as $rating)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <!-- Customer Avatar -->
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    @if($rating->order && $rating->order->customer)
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ strtoupper(substr($rating->order->customer->name, 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="text-sm font-medium text-gray-700">?</span>
                                    @endif
                                </div>
                                
                                <div>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $rating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $rating->rating_value }}/5</span>
                                    </div>
                                    @if($rating->order && $rating->order->customer)
                                        <p class="text-sm text-gray-600">{{ $rating->order->customer->name }}</p>
                                    @else
                                        <p class="text-sm text-gray-600">Customer</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right mt-2 sm:mt-0">
                                <p class="text-sm text-gray-500">{{ $rating->created_at->format('M d, Y g:i A') }}</p>
                                @if($rating->order)
                                    <p class="text-xs text-gray-400">Order #{{ $rating->order->id }}</p>
                                @endif
                            </div>
                        </div>
                        
                        @if($rating->comment)
                        <div class="bg-gray-50 rounded-lg p-3 mt-3">
                            <p class="text-sm text-gray-700 italic">"{{ $rating->comment }}"</p>
                        </div>
                        @else
                        <div class="text-xs text-gray-400 mt-2">
                            No comment provided
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $ratings->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Ratings Yet</h3>
                    <p class="text-gray-500">You haven't received any customer ratings yet. Complete some deliveries to start building your rating profile!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection