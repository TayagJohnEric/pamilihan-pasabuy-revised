@extends('layout.admin')

@section('title', 'Vendor Payout Details')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.payouts.vendors') }}" 
               class="inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Vendor Payouts
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Vendor Payout Details</h2>
                    <p class="text-gray-600">Payout ID: #{{ $payout->id }}</p>
                </div>
                <div class="text-right">
                    @switch($payout->status)
                        @case('pending_payment')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                Pending Payment
                            </span>
                            @break
                        @case('paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                            @break
                        @case('failed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Failed
                            </span>
                            @break
                    @endswitch
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Vendor Information -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Vendor Information</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                @if($payout->vendor && $payout->vendor->shop_logo_url)
                                    <img class="h-16 w-16 rounded-full object-cover" 
                                         src="{{ $payout->vendor->shop_logo_url }}" alt="">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-lg font-medium text-gray-700">
                                            {{ $payout->vendor ? substr($payout->vendor->vendor_name, 0, 1) : 'V' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-xl font-medium text-gray-900">
                                    {{ $payout->vendor->vendor_name ?? 'Unknown Vendor' }}
                                </h4>
                                <p class="text-gray-600">{{ $payout->vendor->user->first_name ?? '' }} {{ $payout->vendor->user->last_name ?? '' }}</p>
                                <p class="text-gray-600">{{ $payout->vendor->user->email ?? 'No email' }}</p>
                                <p class="text-gray-600">{{ $payout->vendor->user->phone_number ?? 'No phone' }}</p>
                                <div class="mt-2">
                                    @if($payout->vendor->stall_number)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Stall: {{ $payout->vendor->stall_number }}
                                        </span>
                                    @endif
                                    @if($payout->vendor->market_section)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Section: {{ $payout->vendor->market_section }}
                                        </span>
                                    @endif
                                    @if($payout->vendor->average_rating)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ⭐ {{ number_format($payout->vendor->average_rating, 1) }} Rating
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payout Breakdown -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payout Breakdown</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">Payout Period</span>
                                <span class="font-medium">
                                    {{ $payout->payout_period_start_date->format('M d, Y') }} - {{ $payout->payout_period_end_date->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">Total Sales Amount</span>
                                <span class="font-medium text-blue-600">₱{{ number_format($payout->total_sales_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">Platform Commission</span>
                                <span class="font-medium text-red-600">-₱{{ number_format($payout->platform_commission_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">Net Sales Amount</span>
                                <span class="font-medium text-green-600">₱{{ number_format($payout->total_sales_amount - $payout->platform_commission_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-700">Adjustments</span>
                                <span class="font-medium {{ $payout->adjustments_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $payout->adjustments_amount >= 0 ? '+' : '' }}₱{{ number_format($payout->adjustments_amount, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-3 pt-4 border-t-2 border-gray-300">
                                <span class="text-lg font-semibold text-gray-800">Total Payout Amount</span>
                                <span class="text-lg font-bold text-gray-900">₱{{ number_format($payout->total_payout_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Adjustment Form -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Manual Adjustments</h3>
                        <form id="adjustment-form" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Adjustment Amount
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                    <input type="number" 
                                           id="adjustments_amount" 
                                           name="adjustments_amount" 
                                           step="0.01"
                                           value="{{ $payout->adjustments_amount }}"
                                           class="pl-8 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="0.00">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Use negative values for deductions, positive for additions</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Adjustment Notes
                                </label>
                                <textarea id="adjustments_notes" 
                                          name="adjustments_notes" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Reason for adjustment...">{{ $payout->adjustments_notes }}</textarea>
                            </div>
                            <button type="submit" 
                                    id="save-adjustment-btn"
                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Adjustments
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Actions Sidebar -->
                <div class="space-y-6">
                    <!-- Payment Status -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Status</h3>
                        
                        @if($payout->status === 'pending_payment')
                            <!-- Mark as Paid Form -->
                            <div class="space-y-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Transaction Reference
                                    </label>
                                    <input type="text" 
                                           id="transaction_reference" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter transaction reference">
                                </div>
                                <button type="button" 
                                        id="mark-paid-btn"
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Mark as Paid
                                </button>
                            </div>
                            
                            <div class="border-t pt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Failure Reason (Optional)
                                    </label>
                                    <textarea id="failure_reason" 
                                              rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                              placeholder="Reason for payment failure..."></textarea>
                                </div>
                                <button type="button" 
                                        id="mark-failed-btn"
                                        class="w-full mt-2 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Mark as Failed
                                </button>
                            </div>
                            
                        @elseif($payout->status === 'paid')
                            <div class="text-center py-4">
                                <div class="text-green-600 mb-2">
                                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="font-medium text-gray-900">Payment Completed</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $payout->paid_at->format('M d, Y g:i A') }}</p>
                                @if($payout->transaction_reference)
                                    <p class="text-xs text-gray-500 mt-1">
                                        Ref: {{ $payout->transaction_reference }}
                                    </p>
                                @endif
                            </div>
                            
                        @elseif($payout->status === 'failed')
                            <div class="text-center py-4">
                                <div class="text-red-600 mb-2">
                                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <p class="font-medium text-gray-900">Payment Failed</p>
                                <p class="text-sm text-gray-600 mt-1">This payout requires attention</p>
                            </div>
                        @endif
                    </div>

                    <!-- Commission Info -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Commission Details</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Commission Rate:</span>
                                <span class="font-medium">
                                    {{ $payout->total_sales_amount > 0 ? number_format(($payout->platform_commission_amount / $payout->total_sales_amount) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Commission Amount:</span>
                                <span class="font-medium text-red-600">₱{{ number_format($payout->platform_commission_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Net Earnings:</span>
                                <span class="font-medium text-green-600">₱{{ number_format($payout->total_sales_amount - $payout->platform_commission_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payout History/Notes -->
                    @if($payout->adjustments_notes)
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notes & History</h3>
                            <div class="bg-gray-50 rounded p-3">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $payout->adjustments_notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Info -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Info</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created:</span>
                                <span>{{ $payout->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Updated:</span>
                                <span>{{ $payout->updated_at->format('M d, Y') }}</span>
                            </div>
                            @if($payout->paid_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Paid:</span>
                                    <span>{{ $payout->paid_at->format('M d, Y g:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // CSRF token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Calculate and display updated total when adjustment amount changes
            $('#adjustments_amount').on('input', function() {
                const netSalesAmount = {{ $payout->total_sales_amount - $payout->platform_commission_amount }};
                const adjustment = parseFloat($(this).val()) || 0;
                const newTotal = netSalesAmount + adjustment;
                
                // You could add a preview element here if needed
                console.log('New total would be:', newTotal);
            });

            // Handle adjustment form submission
            $('#adjustment-form').on('submit', function(e) {
                e.preventDefault();
                
                const $btn = $('#save-adjustment-btn');
                const originalText = $btn.text();
                
                // Disable button and show loading state
                $btn.prop('disabled', true).text('Saving...');
                
                $.ajax({
                    url: '{{ route("admin.payouts.vendors.update", $payout->id) }}',
                    method: 'PATCH',
                    data: {
                        adjustments_amount: $('#adjustments_amount').val(),
                        adjustments_notes: $('#adjustments_notes').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showMessage(response.message, 'success');
                            
                            // Update the total payout amount display
                            location.reload(); // Simple reload for now
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to save adjustments';
                        showMessage(message, 'error');
                    },
                    complete: function() {
                        // Re-enable button
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Handle mark as paid
            $('#mark-paid-btn').on('click', function() {
                const transactionRef = $('#transaction_reference').val().trim();
                
                if (!transactionRef) {
                    showMessage('Please enter a transaction reference', 'error');
                    return;
                }
                
                const $btn = $(this);
                const originalText = $btn.text();
                
                $btn.prop('disabled', true).text('Processing...');
                
                $.ajax({
                    url: '{{ route("admin.payouts.vendors.mark-paid", $payout->id) }}',
                    method: 'PATCH',
                    data: {
                        transaction_reference: transactionRef
                    },
                    success: function(response) {
                        if (response.success) {
                            showMessage(response.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to mark as paid';
                        showMessage(message, 'error');
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Handle mark as failed
            $('#mark-failed-btn').on('click', function() {
                const failureReason = $('#failure_reason').val().trim();
                
                const $btn = $(this);
                const originalText = $btn.text();
                
                $btn.prop('disabled', true).text('Processing...');
                
                $.ajax({
                    url: '{{ route("admin.payouts.vendors.mark-failed", $payout->id) }}',
                    method: 'PATCH',
                    data: {
                        failure_reason: failureReason
                    },
                    success: function(response) {
                        if (response.success) {
                            showMessage(response.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Failed to mark as failed';
                        showMessage(message, 'error');
                        $btn.prop('disabled', false).text(originalText);
                    }
                });
            });

            // Simple message display function
            function showMessage(message, type) {
                // Remove existing messages
                $('.alert-message').remove();
                
                const alertClass = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
                const alertHTML = `
                    <div class="alert-message fixed top-4 right-4 z-50 max-w-sm p-4 border-l-4 ${alertClass} rounded shadow-lg">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                `;
                
                $('body').append(alertHTML);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    $('.alert-message').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    </script>
@endsection