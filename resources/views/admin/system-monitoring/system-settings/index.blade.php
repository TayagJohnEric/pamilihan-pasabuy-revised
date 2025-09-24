@extends('layout.admin')
@section('title', 'System Settings Management')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-[90rem] mx-auto px-4 py-8">
        <!-- Header Section with Gradient Background -->
        <div class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 rounded-xl shadow-lg p-8 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center text-white">
                <div class="mb-6 lg:mb-0">
                    <h2 class="text-2xl lg:text-2xl font-bold text-white drop-shadow-sm">
                        System Settings Management
                    </h2>
                    <p class="text-emerald-100 text-md">
                        Configure and manage your system settings
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('system-settings.create') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-white hover:bg-gray-50 text-emerald-600 text-sm font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Setting
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-8">
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="flex-1 w-full sm:w-auto">
                    <form method="GET" action="{{ route('system-settings.index') }}" class="flex">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}" 
                                   placeholder="Search settings..." 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-medium rounded-r-xl transition-all duration-300 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Success Messages -->
            @if(session('success'))
                <div class="mx-6 mt-6 mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Settings Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Setting Key
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden md:table-cell">
                                Value
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider hidden lg:table-cell">
                                Description
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($settings as $setting)
                            <tr class="hover:bg-gradient-to-r hover:from-emerald-50/50 hover:to-teal-50/50 transition-all duration-200 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors duration-200">
                                                {{ $setting->setting_key }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <div class="text-sm text-gray-600 max-w-xs">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-800 border border-emerald-200/50">
                                            {{ Str::limit($setting->setting_value, 30) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden lg:table-cell">
                                    <div class="text-sm text-gray-600 max-w-sm">
                                        {{ $setting->description ?? 'No description available' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        <a href="{{ route('system-settings.edit', $setting) }}" 
                                           class="inline-flex items-center justify-center p-2 text-emerald-600 hover:text-white hover:bg-emerald-600 rounded-lg transition-all duration-200 hover:shadow-md group/btn"
                                           title="Edit Setting">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('system-settings.destroy', $setting) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 hover:shadow-md group/btn"
                                                    title="Delete Setting">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center mb-6 shadow-lg">
                                            <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No settings found</h3>
                                        <p class="text-gray-600 mb-6 max-w-md text-center">
                                            Get started by creating your first system setting to configure your application
                                        </p>
                                        <a href="{{ route('system-settings.create') }}" 
                                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Create First Setting
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($settings->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    {{ $settings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection