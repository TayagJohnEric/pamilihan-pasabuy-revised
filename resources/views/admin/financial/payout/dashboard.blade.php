@extends('layout.admin')

@section('title', 'Payout Dashboard')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Payout Dashboard</h2>
                    <p class="text-gray-600">Generate and manage rider and vendor payouts</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.payouts.riders') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        View Rider Payouts
                    </a>
                    <a href="{{ route('admin.payouts.vendors') }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        View Vendor Payouts
                    </a>
                </div>
            </div>
        </div>

        <!-- Payout Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Rider Payouts</p>
                        <p class="text-2xl font-bold text-gray-900" id="pending-rider-count">{{ $summary['total_pending_rider_payouts'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Vendor Payouts</p>
                        <p class="text-2xl font-bold text-gray-900" id="pending-vendor-count">{{ $summary['total_pending_vendor_payouts'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Pending Amount</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-pending-amount">₱{{ number_format($summary['total_pending_amount'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Orders to Process</p>
                        <p class="text-2xl font-bold text-gray-900" id="orders-to-process">{{ $summary['orders_to_process'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Generate Payouts -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Generate Payouts</h3>
                
                <div class="space-y-4">
                    <!-- Quick Generate Buttons -->
                    <div class="flex gap-3">
                        <button onclick="generateWeeklyPayouts()" 
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Generate Weekly Payouts
                        </button>
                        <button onclick="generateMonthlyPayouts()" 
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Generate Monthly Payouts
                        </button>
                    </div>

                    <!-- Custom Period Form -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Custom Period</h4>
                        <form id="custom-payout-form" class="space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Start Date</label>
                                    <input type="date" id="start-date" name="start_date" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">End Date</label>
                                    <input type="date" id="end-date" name="end_date" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                Generate Custom Period Payouts
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Payout Activity</h3>
                <div id="recent-activity" class="space-y-3">
                    <div class="text-center text-gray-500 py-8">
                        Loading recent activity...
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="text-gray-700">Processing payouts...</span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="message-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure CSRF token is available
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }
            loadDashboardSummary();
            loadRecentActivity();
        });

        // Load payout summary data
        function loadDashboardSummary() {
            fetch('{{ route("admin.payouts.summary") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateSummaryCards(data.data);
                    } else {
                        console.error('API returned error:', data.message);
                        showMessage('Failed to load dashboard data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading dashboard summary:', error);
                    showMessage('Failed to load dashboard data', 'error');
                });
        }

        // Update summary cards with data
        function updateSummaryCards(data) {
            document.getElementById('pending-rider-count').textContent = data.total_pending_rider_payouts || 0;
            document.getElementById('pending-vendor-count').textContent = data.total_pending_vendor_payouts || 0;
            
            // Format the amount with proper number formatting
            const amount = parseFloat(data.total_pending_amount || 0);
            document.getElementById('total-pending-amount').textContent = '₱' + amount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            
            document.getElementById('orders-to-process').textContent = data.orders_to_process || 0;
        }

        // Load recent payout activity
        function loadRecentActivity() {
            fetch('{{ route("admin.payouts.recent-activity") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateRecentActivity(data.data);
                    } else {
                        showNoRecentActivity('Failed to load recent activity');
                    }
                })
                .catch(error => {
                    console.error('Error loading recent activity:', error);
                    showNoRecentActivity('Error loading recent activity');
                });
        }

        // Update recent activity section
        function updateRecentActivity(activities) {
            const container = document.getElementById('recent-activity');
            
            if (!activities || activities.length === 0) {
                showNoRecentActivity('No recent payout activity');
                return;
            }

            let html = '';
            activities.forEach(activity => {
                const statusColor = getStatusColor(activity.status);
                const timeAgo = getTimeAgo(activity.timestamp);
                
                html += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${statusColor}">
                                    ${activity.status.replace('_', ' ')}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${activity.title}</p>
                                <p class="text-xs text-gray-500">${activity.description}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-gray-400">${timeAgo}</span>
                            <a href="${activity.url}" class="text-blue-600 hover:text-blue-800 text-xs">
                                View
                            </a>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Show no activity message
        function showNoRecentActivity(message) {
            const container = document.getElementById('recent-activity');
            container.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm">${message}</p>
                </div>
            `;
        }

        // Get status color classes
        function getStatusColor(status) {
            switch(status) {
                case 'pending_payment':
                    return 'bg-yellow-100 text-yellow-800';
                case 'paid':
                    return 'bg-green-100 text-green-800';
                case 'failed':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        // Get time ago string
        function getTimeAgo(timestamp) {
            const now = new Date();
            const time = new Date(timestamp);
            const diffInSeconds = Math.floor((now - time) / 1000);
            
            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
            return Math.floor(diffInSeconds / 86400) + 'd ago';
        }

        // Generate weekly payouts
        function generateWeeklyPayouts() {
            showLoading();
            fetch('{{ route("admin.payouts.generate-weekly") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showMessage('Weekly payouts generated successfully!', 'success');
                    loadDashboardSummary();
                } else {
                    showMessage(data.message || 'Failed to generate payouts', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showMessage('Error generating payouts', 'error');
                console.error('Error:', error);
            });
        }

        // Generate monthly payouts
        function generateMonthlyPayouts() {
            showLoading();
            fetch('{{ route("admin.payouts.generate-monthly") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showMessage('Monthly payouts generated successfully!', 'success');
                    loadDashboardSummary();
                } else {
                    showMessage(data.message || 'Failed to generate payouts', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showMessage('Error generating payouts', 'error');
                console.error('Error:', error);
            });
        }

        // Handle custom period form submission
        document.getElementById('custom-payout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            
            if (!startDate || !endDate) {
                showMessage('Please select both start and end dates', 'error');
                return;
            }

            showLoading();
            fetch('{{ route("admin.payouts.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate,
                    payout_type: 'custom'
                })
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showMessage('Custom period payouts generated successfully!', 'success');
                    loadDashboardSummary();
                    document.getElementById('custom-payout-form').reset();
                } else {
                    showMessage(data.message || 'Failed to generate payouts', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showMessage('Error generating payouts', 'error');
                console.error('Error:', error);
            });
        });

        // Show loading overlay
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
        }

        // Show message
        function showMessage(message, type) {
            const container = document.getElementById('message-container');
            const messageDiv = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            messageDiv.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-2 transform transition-all duration-300`;
            messageDiv.textContent = message;
            
            container.appendChild(messageDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    </script>
@endsection
