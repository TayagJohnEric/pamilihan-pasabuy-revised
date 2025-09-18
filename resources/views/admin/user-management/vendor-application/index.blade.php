@extends('layout.admin')

@section('title', 'Vendor Applications')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl p-6 text-white shadow-lg">
                <h1 class="text-3xl font-bold mb-2">Vendor Applications</h1>
                <p class="text-emerald-100 opacity-90">Review and manage vendor applications for new shops</p>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Search Controls Section -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <form method="GET" class="space-y-4 lg:space-y-0 lg:flex lg:items-center lg:justify-between">
                    <!-- Clear Search -->
                    @if(request('search'))
                        <div class="flex items-center">
                            <a href="{{ route('admin.vendor_applications.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Search
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applicant</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Proposed Shop</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($applications as $app)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($app->applicant_first_name, 0, 1) . substr($app->applicant_last_name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $app->applicant_first_name }} {{ $app->applicant_last_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 mr-3">
                                        <div class="h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $app->proposed_vendor_name }}</div>
                                        <div class="text-xs text-gray-500">Proposed Shop</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $app->applicant_email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                    @if(strtolower($app->status) === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif(strtolower($app->status) === 'approved') bg-green-100 text-green-800
                                    @elseif(strtolower($app->status) === 'rejected') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <a 
                                        href="{{ route('admin.vendor_applications.show', $app->id) }}" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors duration-200"
                                        title="View Application Details"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    
                                    <span class="text-gray-300">|</span>
                                    
                                    <button 
                                        onclick="openDeleteModal({{ $app->id }})" 
                                        type="button" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                        title="Reject Application"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Delete Modal for each application -->
                        @include('admin.user-management.vendor-application.modal.delete-modal')
                        
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No vendor applications found</h3>
                                    <p class="text-gray-500">
                                        @if(request('search'))
                                            No applications match your search criteria.
                                        @else
                                            There are currently no vendor applications to review.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Stats/Summary Section -->
            @if($applications->isNotEmpty())
            <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <div class="flex space-x-6">
                        @php
                            $totalApplications = $applications->count();
                            $pendingCount = $applications->where('status', 'pending')->count();
                            $approvedCount = $applications->where('status', 'approved')->count();
                            $rejectedCount = $applications->where('status', 'rejected')->count();
                        @endphp
                        <div class="flex items-center">
                            <span class="inline-block w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                            <span>{{ $pendingCount }} Pending</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            <span>{{ $approvedCount }} Approved</span>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-block w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            <span>{{ $rejectedCount }} Rejected</span>
                        </div>
                    </div>
                    <div class="text-gray-500">
                        Total: {{ $totalApplications }} applications
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function openDeleteModal(applicantId) {
        document.getElementById('deleteModal-' + applicantId).classList.remove('hidden');
    }

    function closeDeleteModal(applicantId) {
        document.getElementById('deleteModal-' + applicantId).classList.add('hidden');
    }

    // Auto-submit search form with debounce
    let searchTimeout;
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>

@endsection