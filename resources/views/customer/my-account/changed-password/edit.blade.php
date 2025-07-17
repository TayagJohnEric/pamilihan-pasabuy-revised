<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen py-8 px-4">
    <div class="max-w-md mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="mx-auto h-12 w-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Change Password</h1>
            <p class="mt-2 text-sm text-gray-600 max-w-sm mx-auto">
                Keep your account secure by updating your password regularly
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg" role="alert">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-8 sm:px-8">
                <form method="POST" action="{{ route('customer.password.update') }}" class="space-y-6" novalidate>
                    @csrf
                    @method('PUT')
                    <!-- Current Password Field -->
                    <div class="space-y-2">
                        <label for="current_password" class="block text-sm font-semibold text-gray-900">
                            Current Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="current_password"
                                name="current_password" 
                                required
                                autocomplete="current-password"
                                aria-describedby="current_password_error"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('current_password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Enter your current password"
                            >
                        </div>
                        @error('current_password') 
                            <p class="text-sm text-red-600 flex items-center mt-2" id="current_password_error" role="alert">
                                <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- New Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-900">
                            New Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                required
                                autocomplete="new-password"
                                aria-describedby="password_error password_help"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Enter your new password"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1" id="password_help">
                            Password should be at least 8 characters long and include a mix of letters, numbers, and symbols
                        </p>
                        @error('password') 
                            <p class="text-sm text-red-600 flex items-center mt-2" id="password_error" role="alert">
                                <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                required
                                autocomplete="new-password"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                placeholder="Confirm your new password"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full bg-green-500 hover:bg-green-600 focus:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <span class="flex items-center justify-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Update Password
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tips Footer -->
            <div class="bg-gray-50 px-6 py-4 sm:px-8 border-t border-gray-100">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Security Tips</h3>
                        <div class="mt-1 text-xs text-gray-600 space-y-1">
                            <p>• Use a unique password you don't use anywhere else</p>
                            <p>• Consider using a password manager for better security</p>
                            <p>• Change your password regularly to keep your account safe</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>