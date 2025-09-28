@extends('layout.admin')

@section('title', 'Admin Profile')

@section('content')
<div class="max-w-4xl mx-auto p-6">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-6 border border-emerald-200">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-3xl font-bold text-emerald-900 mb-2">Profile Management</h2>
          <p class="text-emerald-700 font-medium">Update your personal information and profile photo</p>
        </div>
        <a href="{{ route('admin.password.edit') }}" 
           class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2v6m0 0v6a2 2 0 01-2 2h-6M9 7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V9a2 2 0 00-2-2H9z"></path>
          </svg>
          Change Password
        </a>
      </div>
    </div>
  </div>

  <!-- Success Message -->
  @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-400 rounded-r-lg shadow-sm">
      <div class="flex items-center">
        <svg class="w-5 h-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
      </div>
    </div>
  @endif

  <!-- Main Form Card -->
  <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <input type="hidden" name="remove_profile_image" id="remove_profile_image" value="0">

      <div class="p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Profile Photo Section -->
          <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-6 border border-emerald-200">
              <h3 class="text-lg font-semibold text-emerald-900 mb-4">Profile Photo</h3>
              
              <div class="flex flex-col items-center">
                <!-- Avatar Container -->
                <div class="relative mb-4">
                  <div class="h-32 w-32 rounded-full ring-4 ring-emerald-200 overflow-hidden bg-gradient-to-br from-emerald-100 to-emerald-200 shadow-lg">
                    <img id="avatarPreview" 
                         src="{{ $user->profile_image_url ? asset('storage/' . $user->profile_image_url) : 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name.' '.$user->last_name) . '&background=10B981&color=ffffff' }}" 
                         alt="Avatar" 
                         class="h-full w-full object-cover">
                  </div>
                  <!-- Upload Overlay -->
                  <div class="absolute inset-0 rounded-full bg-black bg-opacity-40 opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-center justify-center cursor-pointer"
                       onclick="document.getElementById('profile_image').click()">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                  </div>
                </div>

                <!-- Upload Controls -->
                <div class="text-center">
                  <label for="profile_image" 
                         class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:shadow-lg cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Photo
                  </label>
                  <input id="profile_image" name="profile_image" type="file" 
                         accept="image/png,image/jpeg,image/jpg,image/webp" class="hidden">
                  
                  <button type="button" id="removeAvatar" 
                          class="ml-3 px-3 py-2 text-sm text-emerald-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors duration-200 {{ $user->profile_image_url ? '' : 'hidden' }}">
                    Remove
                  </button>
                  
                  <p class="text-xs text-emerald-600 mt-3 font-medium">JPG, PNG, or WEBP up to 5MB</p>
                  @error('profile_image')
                    <p class="text-sm text-red-600 mt-2 font-medium">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <!-- Form Fields Section -->
          <div class="lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <!-- First Name -->
              <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  First Name <span class="text-emerald-500">*</span>
                </label>
                <div class="relative">
                  <input type="text" name="first_name" 
                         value="{{ old('first_name', $user->first_name) }}" 
                         class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white hover:border-emerald-300">
                </div>
                @error('first_name')
                  <p class="text-sm text-red-600 mt-2 font-medium">{{ $message }}</p>
                @enderror
              </div>

              <!-- Last Name -->
              <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Last Name <span class="text-emerald-500">*</span>
                </label>
                <div class="relative">
                  <input type="text" name="last_name" 
                         value="{{ old('last_name', $user->last_name) }}" 
                         class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white hover:border-emerald-300">
                </div>
                @error('last_name')
                  <p class="text-sm text-red-600 mt-2 font-medium">{{ $message }}</p>
                @enderror
              </div>

              <!-- Email -->
              <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Email Address <span class="text-emerald-500">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                  </div>
                  <input type="email" name="email" 
                         value="{{ old('email', $user->email) }}" 
                         class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white hover:border-emerald-300">
                </div>
                @error('email')
                  <p class="text-sm text-red-600 mt-2 font-medium">{{ $message }}</p>
                @enderror
              </div>

              <!-- Phone Number -->
              <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                  </div>
                  <input type="text" name="phone_number" 
                         value="{{ old('phone_number', $user->phone_number) }}" 
                         placeholder="Enter your phone number"
                         class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white hover:border-emerald-300">
                </div>
                @error('phone_number')
                  <p class="text-sm text-red-600 mt-2 font-medium">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
        <div class="flex justify-end">
          <button type="submit" 
                  class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Save Changes
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('profile_image');
    const preview = document.getElementById('avatarPreview');
    const removeBtn = document.getElementById('removeAvatar');

    // Trigger native file dialog when clicking label
    document.querySelector('label[for="profile_image"]').addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function () {
      const file = this.files && this.files[0];
      if (!file) return;
      const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
      if (!validTypes.includes(file.type)) {
        alert('Invalid file type. Please upload JPG, PNG, or WEBP.');
        this.value = '';
        return;
      }
      if (file.size > 5 * 1024 * 1024) {
        alert('File too large. Max size is 5MB.');
        this.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = e => { preview.src = e.target.result; };
      reader.readAsDataURL(file);
      removeBtn.classList.remove('hidden');
      document.getElementById('remove_profile_image').value = '0';
    });

    removeBtn?.addEventListener('click', function () {
      fileInput.value = '';
      // Reset to initials avatar if available or keep current image if none
      @if($user->profile_image_url)
        preview.src = '{{ asset('storage/' . $user->profile_image_url) }}';
      @else
        preview.src = 'https://ui-avatars.com/api/?name={{ urlencode($user->first_name.' '.$user->last_name) }}&background=10B981&color=ffffff';
      @endif
      this.classList.add('hidden');
      document.getElementById('remove_profile_image').value = '1';
    });
  });
</script>
@endpush
@endsection