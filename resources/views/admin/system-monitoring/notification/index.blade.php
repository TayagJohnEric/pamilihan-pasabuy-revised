@extends('layout.admin')

@section('title', 'Notification Log')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-[90rem] mx-auto px-4 py-8">
        <!-- Header Section with Gradient Background -->
        <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl shadow-lg p-8 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center text-white">
                <div class="mb-6 lg:mb-0">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-3 text-white drop-shadow-sm">
                        Notification Log
                    </h2>
                    <p class="text-emerald-100 text-lg">
                        Track all notifications happening in the system
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4 border border-white/20">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 17h5l-5 5v-5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11V9a7 7 0 00-14 0v2M5 11v6a2 2 0 002 2h6M15 11v6a2 2 0 01-2 2H7"/>
                        </svg>
                        <div>
                            <div class="text-2xl font-bold text-white">{{ $notifications->total() }}</div>
                            <div class="text-emerald-100 text-sm">Total Notifications</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Filters Section -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-100">
                <form method="GET" action="{{ route('admin.notifications.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Notification Type</label>
                            <select name="type" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors duration-200">
                                <option value="">All Types</option>
                                <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>Order</option>
                                <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>System</option>
                                <option value="rating" {{ request('type') === 'rating' ? 'selected' : '' }}>Rating</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Read Status</label>
                            <select name="read_status" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors duration-200">
                                <option value="">All</option>
                                <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Read</option>
                                <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Unread</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Notifications Table -->
            @if($notifications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                                    User
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Type
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">
                                    Title
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden xl:table-cell">
                                    Message
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($notifications as $notification)
                                <tr class="hover:bg-gradient-to-r hover:from-emerald-50/50 hover:to-teal-50/50 transition-all duration-200 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center shadow-sm">
                                                <span class="text-xs font-bold text-gray-600">#{{ $notification->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center shadow-md">
                                                <span class="text-sm font-bold text-blue-700">
                                                    {{ $notification->user ? substr($notification->user->name, 0, 1) : 'N' }}
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors duration-200">
                                                    {{ $notification->user->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @switch($notification->type)
                                            @case('order')
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                    </svg>
                                                    Order
                                                </span>
                                                @break
                                            @case('system')
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    System
                                                </span>
                                                @break
                                            @case('rating')
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                    </svg>
                                                    Rating
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                    <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                                    {{ ucfirst($notification->type) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <div class="text-sm font-medium text-gray-900 max-w-xs truncate">
                                            {{ $notification->title ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden xl:table-cell">
                                        <div class="text-sm text-gray-600 max-w-md">
                                            <div class="line-clamp-2">
                                                {{ is_array($notification->message) ? json_encode($notification->message) : $notification->message }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($notification->read_at)
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                Read
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                                                Unread
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $notification->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $notification->created_at->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                            <div class="text-sm text-gray-700">
                                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} results
                            </div>
                            <div>
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-5 5v-5zM9 17h5l-5 5v-5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11V9a7 7 0 00-14 0v2M5 11v6a2 2 0 002 2h6M15 11v6a2 2 0 01-2 2H7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No notifications found</h3>
                        <p class="text-gray-600 mb-6 max-w-md text-center">
                            @if(request()->hasAny(['type', 'read_status']))
                                No notifications match your current filters.
                            @else
                                No notifications have been generated yet.
                            @endif
                        </p>
                        @if(request()->hasAny(['type', 'read_status']))
                            <a href="{{ route('admin.notifications.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection