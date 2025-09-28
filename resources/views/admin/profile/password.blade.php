@extends('layout.admin')

@section('title', 'Change Password')

@section('content')
<div class="max-w-2xl mx-auto p-6">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-6 border border-emerald-200">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-3xl font-bold text-emerald-900 mb-2">Change Password</h2>
          <p class="text-emerald-700 font-medium">Keep your account secure with a strong password</p>
        </div>
        <a href="{{ route('admin.profile.edit') }}" 
           class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Back to Profile
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

  <!-- Security Tips Card -->
  <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
    <div class="flex items-start">
      <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
      <div>
        <h4 class="text-sm font-semibold text-blue-900 mb-1">Password Security Tips</h4>
        <ul class="text-xs text-blue-700 space-y-1">
          <li>• Use at least 8 characters with mixed case letters</li>
          <li>• Include numbers and special symbols</li>
          <li>• Avoid using personal information or common words</li>
          <li>• Don't reuse passwords from other accounts</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Main Form Card -->
  <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <form action="{{ route('admin.password.update') }}" method="POST" class="p-8 space-y-6">
      @csrf
      @method('PUT')

      <!-- Current Password -->
      <div class="group">
        <label class="block text-sm font-semibold text-gray-700 mb-2">
          Current Password <span class="text-red-500">*</span>
        </label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
          </div>
          <input id="old_password" type="password" name="old_password" 
                 class="w-full pl-10 pr-12 py-3 border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white hover:border-emerald-300" 
                 autocomplete="current-password"
                 placeholder="Enter your current password">
          <button type="button" data-target="old_password" 
                  class="toggle-visibility absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-emerald-600 transition-colors duration-200">
            <svg class="eye-open h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <svg class="eye-closed h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L17.121 17.12M17.121 17.12l4.242 4.243M17.121 17.12l-7.243-7.242"></path>
            </svg>
          </button>
        </div>
        @error('old_password')
          <p class="text-sm text-red-600 mt-2 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p>
        @enderror
      </div>

      <!-- New Password -->
      <div class="group">
        <div class="flex items-center justify-between mb-2">
          <label class="block text-sm font-semibold text-gray-700">
            New Password <span class="text-red-500">*</span>
          </label>
          <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
            Minimum 8 characters required
          </span>
        </div>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
          </div>
          <input id="password" type="password" name="password" 
                 class="w-full pl-10 pr-12 py-3 border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white hover:border-emerald-300" 
                 autocomplete="new-password"
                 placeholder="Create a strong password">
          <button type="button" data-target="password" 
                  class="toggle-visibility absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-emerald-600 transition-colors duration-200">
            <svg class="eye-open h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <svg class="eye-closed h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L17.121 17.12M17.121 17.12l4.242 4.243M17.121 17.12l-7.243-7.242"></path>
            </svg>
          </button>
        </div>
        
        <!-- Password Strength Indicator -->
        <div class="mt-3">
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-600">Password Strength:</span>
            <span id="strengthText" class="text-xs font-semibold px-2 py-1 rounded-full bg-red-100 text-red-700">Weak</span>
          </div>
          <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
            <div id="strengthBar" class="h-2 rounded-full bg-red-500 w-0 transition-all duration-300 ease-out"></div>
          </div>
          
          <!-- Password Requirements Checklist -->
          <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
            <div id="req-length" class="flex items-center text-gray-500">
              <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
              </svg>
              <span>8+ characters</span>
            </div>
            <div id="req-case" class="flex items-center text-gray-500">
              <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
              </svg>
              <span>Upper & lowercase</span>
            </div>
            <div id="req-number" class="flex items-center text-gray-500">
              <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
              </svg>
              <span>Number</span>
            </div>
            <div id="req-special" class="flex items-center text-gray-500">
              <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"></circle>
              </svg>
              <span>Special character</span>
            </div>
          </div>
        </div>
        
        @error('password')
          <p class="text-sm text-red-600 mt-2 font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
          </p>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div class="group">
        <label class="block text-sm font-semibold text-gray-700 mb-2">
          Confirm New Password <span class="text-red-500">*</span>
        </label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <input id="password_confirmation" type="password" name="password_confirmation" 
                 class="w-full pl-10 pr-12 py-3 border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 bg-white hover:border-emerald-300" 
                 autocomplete="new-password"
                 placeholder="Confirm your new password">
          <button type="button" data-target="password_confirmation" 
                  class="toggle-visibility absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-emerald-600 transition-colors duration-200">
            <svg class="eye-open h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            <svg class="eye-closed h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L17.121 17.12M17.121 17.12l4.242 4.243M17.121 17.12l-7.243-7.242"></path>
            </svg>
          </button>
        </div>
        <div id="match-indicator" class="mt-2 text-xs hidden">
          <span id="match-text" class="flex items-center">
            <svg id="match-icon" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span id="match-message">Passwords match</span>
          </span>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="pt-6 border-t border-gray-200">
        <div class="flex justify-end">
          <button type="submit" 
                  class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-lg shadow-md transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Update Password
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Enhanced password visibility toggle with icons
    document.querySelectorAll('.toggle-visibility').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = btn.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        
        if (!input) return;
        
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        
        // Toggle icons with smooth transition
        if (eyeOpen && eyeClosed) {
          if (isPassword) {
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
          } else {
            eyeClosed.classList.add('hidden');
            eyeOpen.classList.remove('hidden');
          }
        }
      });
    });

    // Enhanced password strength meter with requirements checklist
    const pwd = document.getElementById('password');
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    const confirmPwd = document.getElementById('password_confirmation');
    const matchIndicator = document.getElementById('match-indicator');
    const matchText = document.getElementById('match-text');
    const matchIcon = document.getElementById('match-icon');
    const matchMessage = document.getElementById('match-message');
    
    const requirements = [
      { id: 'req-length', check: v => v.length >= 8 },
      { id: 'req-case', check: v => /[a-z]/.test(v) && /[A-Z]/.test(v) },
      { id: 'req-number', check: v => /\d/.test(v) },
      { id: 'req-special', check: v => /[^A-Za-z0-9]/.test(v) }
    ];
    
    const strengthLevels = [
      { label: 'Weak', color: 'bg-red-500', textColor: 'text-red-700', bgColor: 'bg-red-100' },
      { label: 'Fair', color: 'bg-yellow-500', textColor: 'text-yellow-700', bgColor: 'bg-yellow-100' },
      { label: 'Good', color: 'bg-blue-500', textColor: 'text-blue-700', bgColor: 'bg-blue-100' },
      { label: 'Strong', color: 'bg-emerald-500', textColor: 'text-emerald-700', bgColor: 'bg-emerald-100' }
    ];

    function updateRequirements(value) {
      requirements.forEach(req => {
        const element = document.getElementById(req.id);
        const icon = element.querySelector('svg');
        const span = element.querySelector('span');
        const passed = req.check(value);
        
        if (passed) {
          element.classList.remove('text-gray-500');
          element.classList.add('text-emerald-600');
          icon.classList.remove('text-gray-400');
          icon.classList.add('text-emerald-500');
          icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
        } else {
          element.classList.remove('text-emerald-600');
          element.classList.add('text-gray-500');
          icon.classList.remove('text-emerald-500');
          icon.classList.add('text-gray-400');
          icon.innerHTML = '<circle cx="12" cy="12" r="10"></circle>';
        }
      });
    }

    function updateStrength(value) {
      const passed = requirements.reduce((acc, req) => acc + (req.check(value) ? 1 : 0), 0);
      const strengthIndex = Math.min(passed, 3);
      const strength = strengthLevels[strengthIndex];
      const percentage = value.length > 0 ? (passed / requirements.length) * 100 : 0;
      
      // Update strength bar
      bar.className = `h-2 rounded-full ${strength.color} transition-all duration-300 ease-out`;
      bar.style.width = `${percentage}%`;
      
      // Update strength text
      text.className = `text-xs font-semibold px-2 py-1 rounded-full ${strength.bgColor} ${strength.textColor}`;
      text.textContent = strength.label;
      
      // Update requirements checklist
      updateRequirements(value);
    }

    function updatePasswordMatch() {
      const password = pwd.value;
      const confirmation = confirmPwd.value;
      
      if (confirmation.length === 0) {
        matchIndicator.classList.add('hidden');
        return;
      }
      
      matchIndicator.classList.remove('hidden');
      
      if (password === confirmation && password.length > 0) {
        matchText.className = 'flex items-center text-emerald-600';
        matchIcon.className = 'w-3 h-3 mr-1 text-emerald-500';
        matchMessage.textContent = 'Passwords match';
        matchIcon.innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>';
      } else {
        matchText.className = 'flex items-center text-red-600';
        matchIcon.className = 'w-3 h-3 mr-1 text-red-500';
        matchMessage.textContent = 'Passwords do not match';
        matchIcon.innerHTML = '<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>';
      }
    }

    pwd?.addEventListener('input', e => {
      updateStrength(e.target.value);
      updatePasswordMatch();
    });
    
    confirmPwd?.addEventListener('input', updatePasswordMatch);
  });
</script>
@endpush
@endsection