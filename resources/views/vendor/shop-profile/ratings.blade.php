@extends('layout.vendor')
@section('title', 'Ratings & Reviews')
@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Ratings & Reviews</h2>
                <a href="{{ route('vendor.profile.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Profile
                </a>
            </div>

            <!-- Rating Summary -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Average Rating -->
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ $averageRating }}</div>
                        <div class="flex justify-center items-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <div class="text-sm text-gray-600">Average Rating</div>
                    </div>

                    <!-- Total Reviews -->
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ $totalRatings }}</div>
                        <div class="text-sm text-gray-600">Total Reviews</div>
                    </div>

                    <!-- Rating Distribution -->
                    <div class="space-y-2">
                        @for($i = 5; $i >= 1; $i--)
                            <div class="flex items-center text-sm">
                                <span class="w-3 text-gray-600">{{ $i }}</span>
                                <svg class="w-4 h-4 text-yellow-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <div class="flex-1 mx-2">
                                    <div class="bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-400 h-2 rounded-full" 
                                             style="width: {{ $totalRatings > 0 ? ($ratingDistribution[$i] / $totalRatings) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <span class="w-8 text-gray-600">{{ $ratingDistribution[$i] ?? 0 }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            @if($ratings->count() > 0)
                <div class="space-y-6">
                    @foreach($ratings as $rating)
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <!-- User Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-lg font-medium text-gray-600">
                                                {{ substr($rating->user->first_name, 0, 1) }}{{ substr($rating->user->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Review Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900">
                                                    {{ $rating->user->first_name }} {{ $rating->user->last_name }}
                                                </h4>
                                                <div class="flex items-center mt-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $rating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                    <span class="ml-2 text-sm text-gray-600">{{ $rating->rating_value }}/5</span>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:mt-0 text-sm text-gray-500">
                                                {{ $rating->created_at->format('M d, Y') }}
                                            </div>
                                        </div>

                                        @if($rating->comment)
                                            <div class="mt-4">
                                                <p class="text-gray-700">{{ $rating->comment }}</p>
                                            </div>
                                        @endif

                                        <!-- Order Info -->
                                        @if($rating->order)
                                            <div class="mt-4 bg-gray-50 rounded-lg p-3">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                    </svg>
                                                    Order #{{ $rating->order->id }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $ratings->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Your shop hasn't received any reviews yet. Keep providing excellent service to earn your first review!</p>
                </div>
            @endif
        </div>
    </div>
@endsection