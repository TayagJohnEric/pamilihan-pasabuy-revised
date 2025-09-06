@extends('layout.customer')

@section('title', 'Rate Order #' . $order->id)

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Rate Your Experience</h2>
                <p class="text-gray-600 mb-4">Help us improve by sharing your feedback about Order #{{ $order->id }}</p>
                
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

            @if($hasRated)
                <!-- Already Rated Message -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-green-900">Thank You!</h3>
                    </div>
                    <p class="text-green-700 mt-2">You have already submitted your rating for this order. Your feedback is valuable to us!</p>
                    
                    <!-- Show existing ratings -->
                    <div class="mt-4 space-y-4">
                        @if($existingRiderRating && $order->rider)
                            <div class="bg-white rounded-lg p-4 border">
                                <h4 class="font-medium text-gray-900 mb-2">Your Rating for Rider</h4>
                                <div class="flex items-center mb-2">
                                    <span class="font-medium text-gray-700 mr-2">{{ $order->rider->first_name }} {{ $order->rider->last_name }}:</span>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $existingRiderRating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">({{ $existingRiderRating->rating_value }}/5)</span>
                                    </div>
                                </div>
                                @if($existingRiderRating->comment)
                                    <p class="text-sm text-gray-600">{{ $existingRiderRating->comment }}</p>
                                @endif
                            </div>
                        @endif

                        @foreach($vendors as $vendor)
                            @if(isset($existingVendorRatings[$vendor->id]))
                                @php $vendorRating = $existingVendorRatings[$vendor->id]; @endphp
                                <div class="bg-white rounded-lg p-4 border">
                                    <h4 class="font-medium text-gray-900 mb-2">Your Rating for Vendor</h4>
                                    <div class="flex items-center mb-2">
                                        <span class="font-medium text-gray-700 mr-2">{{ $vendor->vendor_name }}:</span>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $vendorRating->rating_value ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">({{ $vendorRating->rating_value }}/5)</span>
                                        </div>
                                    </div>
                                    @if($vendorRating->comment)
                                        <p class="text-sm text-gray-600">{{ $vendorRating->comment }}</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Rating Form -->
                <form id="ratingForm" action="{{ route('customer.orders.rate.submit', $order) }}" method="POST">
                    @csrf
                    
                    <!-- Rider Rating -->
                    @if($order->rider)
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rate Your Rider</h3>
                            
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
                                    <p class="text-gray-600">Delivery Rider</p>
                                </div>
                            </div>
                            
                            <!-- Star Rating -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" 
                                                class="star-button w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                                data-target="rider_rating" 
                                                data-value="{{ $i }}">
                                            <svg fill="currentColor" viewBox="0 0 20 20" class="w-full h-full">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rider_rating" id="rider_rating" required>
                                @error('rider_rating')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Comment -->
                            <div>
                                <label for="rider_comment" class="block text-sm font-medium text-gray-700 mb-2">
                                    Comment (Optional)
                                </label>
                                <textarea name="rider_comment" 
                                          id="rider_comment" 
                                          rows="3" 
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Share your experience with the delivery service...">{{ old('rider_comment') }}</textarea>
                                @error('rider_comment')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                    
                    <!-- Vendor Ratings -->
                    @if($vendors->count() > 0)
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900">Rate the Vendors</h3>
                            
                            @foreach($vendors as $vendor)
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <div class="flex items-center gap-4 mb-4">
                                        @if($vendor->shop_logo_url)
                                            <img src="{{ $vendor->shop_logo_url }}" 
                                                 alt="{{ $vendor->vendor_name }}"
                                                 class="w-16 h-16 rounded-lg object-cover">
                                        @else
                                            <div class="w-16 h-16 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900 text-lg">{{ $vendor->vendor_name }}</h4>
                                            <p class="text-gray-600">{{ $vendor->market_section ?? 'Vendor' }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Star Rating -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" 
                                                        class="star-button w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                                        data-target="vendor_rating_{{ $vendor->id }}" 
                                                        data-value="{{ $i }}">
                                                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-full h-full">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="vendor_ratings[{{ $vendor->id }}]" id="vendor_rating_{{ $vendor->id }}" required>
                                        @error("vendor_ratings.{$vendor->id}")
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Comment -->
                                    <div>
                                        <label for="vendor_comment_{{ $vendor->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Comment (Optional)
                                        </label>
                                        <textarea name="vendor_comments[{{ $vendor->id }}]" 
                                                  id="vendor_comment_{{ $vendor->id }}" 
                                                  rows="3" 
                                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Share your experience with {{ $vendor->vendor_name }}...">{{ old("vendor_comments.{$vendor->id}") }}</textarea>
                                        @error("vendor_comments.{$vendor->id}")
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                id="submitBtn"
                                class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:bg-blue-400 disabled:cursor-not-allowed">
                            <span class="btn-text">Submit Ratings</span>
                            <span class="btn-loading hidden">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Star rating functionality
            $('.star-button').on('click', function() {
                const target = $(this).data('target');
                const value = $(this).data('value');
                const container = $(this).parent();
                
                // Set the hidden input value
                $('#' + target).val(value);
                
                // Update star colors
                container.find('.star-button').each(function(index) {
                    const starValue = $(this).data('value');
                    if (starValue <= value) {
                        $(this).removeClass('text-gray-300').addClass('text-yellow-400');
                    } else {
                        $(this).removeClass('text-yellow-400').addClass('text-gray-300');
                    }
                });
            });
            
            // Star rating hover effects
            $('.star-button').on('mouseenter', function() {
                const value = $(this).data('value');
                const container = $(this).parent();
                
                container.find('.star-button').each(function(index) {
                    const starValue = $(this).data('value');
                    if (starValue <= value) {
                        $(this).addClass('text-yellow-400');
                    }
                });
            });
            
            $('.star-button').parent().on('mouseleave', function() {
                const container = $(this);
                const target = container.find('.star-button:first').data('target');
                const selectedValue = $('#' + target).val();
                
                container.find('.star-button').each(function(index) {
                    const starValue = $(this).data('value');
                    if (selectedValue && starValue <= selectedValue) {
                        $(this).removeClass('text-gray-300').addClass('text-yellow-400');
                    } else {
                        $(this).removeClass('text-yellow-400').addClass('text-gray-300');
                    }
                });
            });
            
            // Form submission with AJAX
            $('#ratingForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate that all required ratings are selected
                let isValid = true;
                const requiredRatings = ['rider_rating'];
                
                @foreach($vendors as $vendor)
                    requiredRatings.push('vendor_rating_{{ $vendor->id }}');
                @endforeach
                
                requiredRatings.forEach(function(ratingName) {
                    const ratingValue = $('#' + ratingName).val();
                    if (!ratingValue) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    // Show error message
                    if ($('.rating-error').length === 0) {
                        $('#ratingForm').prepend('<div class="rating-error bg-red-50 border border-red-200 rounded-lg p-4 mb-6"><p class="text-red-800">Please provide ratings for all items before submitting.</p></div>');
                    }
                    return;
                } else {
                    $('.rating-error').remove();
                }
                
                // Disable submit button and show loading state
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.find('.btn-text').hide();
                submitBtn.find('.btn-loading').show();
                
                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $('#ratingForm').hide();
                            $('.bg-white.rounded-lg.shadow.p-6').prepend(`
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <h3 class="text-lg font-medium text-green-900">${response.message}</h3>
                                    </div>
                                    <p class="text-green-700 mt-2">Your feedback helps improve our service quality.</p>
                                </div>
                            `);
                            
                            // Redirect after a short delay
                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.btn-text').show();
                        submitBtn.find('.btn-loading').hide();
                        
                        let errorMessage = 'Failed to submit ratings. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        // Show error message
                        $('.rating-error').remove();
                        $('#ratingForm').prepend(`
                            <div class="rating-error bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <p class="text-red-800">${errorMessage}</p>
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
@endsection