@extends('layout.rider')

@section('title', 'Rider Profile')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 lg:mb-0">Rider Profile</h2>
                <button onclick="toggleEditMode()" id="editBtn" 
                    class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 text-white px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-2 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                </button>
            </div>

            <!-- Profile Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Image Section -->
                <div class="lg:col-span-1">
                    <div class="text-center">
                        <div class="relative inline-block">
                            <img src="{{ $rider->user->profile_image_url ?? 'https://via.placeholder.com/150x150/e5e7eb/6b7280?text=No+Image' }}" 
                                 alt="Profile Image" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-green-100">
                            <div class="absolute bottom-0 right-0 bg-green-600 text-white p-2 rounded-full shadow-lg">
                                @if($rider->is_available)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mt-4">{{ $rider->user->name }}</h3>
                        <p class="text-gray-600">{{ ucfirst($rider->user->role) }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $rider->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $rider->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="lg:col-span-2">
                    <form id="profileForm" method="POST" action="{{ route('rider.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Personal Information</h4>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" name="first_name" value="{{ $rider->user->first_name }}" 
                                           class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                           readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" name="last_name" value="{{ $rider->user->last_name }}" 
                                           class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                           readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" value="{{ $rider->user->email }}" 
                                           class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                           readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="tel" name="phone_number" value="{{ $rider->user->phone_number }}" 
                                           class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                           readonly>
                                </div>
                            </div>

                            <!-- Rider Information -->
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Rider Information</h4>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">License Number</label>
                                    <input type="text" name="license_number" value="{{ $rider->license_number }}" 
                                           class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                           readonly>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                                    <select name="vehicle_type" class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" disabled>
                                        <option value="bike" {{ $rider->vehicle_type == 'bike' ? 'selected' : '' }}>Bike</option>
                                        <option value="motorcycle" {{ $rider->vehicle_type == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                        <option value="car" {{ $rider->vehicle_type == 'car' ? 'selected' : '' }}>Car</option>
                                        <option value="van" {{ $rider->vehicle_type == 'van' ? 'selected' : '' }}>Van</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Verification Status</label>
                                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium 
                                            {{ $rider->verification_status == 'verified' ? 'bg-green-100 text-green-800' : 
                                               ($rider->verification_status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($rider->verification_status) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_available" id="is_available" 
                                           {{ $rider->is_available ? 'checked' : '' }} 
                                           class="profile-checkbox h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" 
                                           disabled>
                                    <label for="is_available" class="ml-2 block text-sm text-gray-700">
                                        Available for deliveries
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions (hidden by default) -->
                        <div id="formActions" class="hidden mt-6 flex flex-col sm:flex-row gap-3">
                            <button type="submit" 
                                class="bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:from-emerald-700 hover:via-emerald-700 hover:to-teal-700 text-white px-6 py-2 rounded-lg transition-all duration-300 flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Changes
                            </button>
                            <button type="button" onclick="cancelEdit()" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 px-6 py-2 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Rating Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Average Rating</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $rider->average_rating ? number_format($rider->average_rating, 1) : 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $rider->average_rating && $i <= $rider->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            </div>

            <!-- Total Deliveries Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Deliveries</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($rider->total_deliveries) }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Daily Deliveries Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Today's Deliveries</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $rider->daily_deliveries }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
            <div class="text-center py-8">
                <div class="bg-gray-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-gray-500">No recent activity to display</p>
                <p class="text-sm text-gray-400 mt-1">Your delivery history will appear here</p>
            </div>
        </div>
    </div>

    <script>
        function toggleEditMode() {
            const inputs = document.querySelectorAll('.profile-input');
            const checkbox = document.querySelector('.profile-checkbox');
            const formActions = document.getElementById('formActions');
            const editBtn = document.getElementById('editBtn');
            
            // Toggle readonly/disabled state
            inputs.forEach(input => {
                if (input.readOnly !== undefined) {
                    input.readOnly = !input.readOnly;
                } else {
                    input.disabled = !input.disabled;
                }
            });
            
            if (checkbox) {
                checkbox.disabled = !checkbox.disabled;
            }
            
            // Show/hide form actions
            formActions.classList.toggle('hidden');
            
            // Update button text
            if (formActions.classList.contains('hidden')) {
                editBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                `;
            } else {
                editBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel Edit
                `;
            }
        }
        
        function cancelEdit() {
            // Reset form and toggle edit mode
            document.getElementById('profileForm').reset();
            toggleEditMode();
        }
    </script>
@endsection