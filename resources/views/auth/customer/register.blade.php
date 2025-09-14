<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">


    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen py-8">

<div class="bg-white p-8 rounded-xl shadow w-full max-w-md border border-green-100">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 ">Create Account</h2>
        <p class="text-gray-600">Join us as a customer</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded-lg">
            <ul class="text-red-600 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="registerForm">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">First Name</label>
                <input type="text" name="first_name" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200" required placeholder="First name">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2">Last Name</label>
                <input type="text" name="last_name" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200" required placeholder="Last name">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
            <input type="email" name="email" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200" required placeholder="Enter your email">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200" required placeholder="Create a password">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200" required placeholder="Confirm your password">
        </div>

        <div id="errorContainer" class="mb-4 p-3 text-red-600 bg-red-50 border border-red-200 rounded-lg hidden">
            <ul id="errorList" class="text-sm space-y-1"></ul>
        </div>

        <div>
            <button type="submit" id="register-btn" class="w-full bg-gradient-to-r from-green-600 to-green-700 from-blue-green hover:to-green-800  text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                <span id="btn-text">Create Account</span>
                <svg id="loading-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
            Already have an account? 
            <a href="{{ route('customer.login') }}" class="text-green-600 hover:text-green-700 font-semibold">Sign in</a>
        </p>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl p-8 w-96 text-center border border-green-100">
        <div class="mb-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-2xl font-bold mb-2 text-gray-800">Welcome!</h3>
        <p class="mb-6 text-gray-600" id="successMessage">Your account has been successfully created.</p>
        <button onclick="closeSuccessModal()" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-300">
            Get Started
        </button>
    </div>
</div>

<script>
    function closeSuccessModal() {
        $('#successModal').addClass('hidden');
    }

    $('#registerForm').submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let url = "{{ route('customer.register') }}";
        let errorContainer = $('#errorContainer');
        let errorList = $('#errorList');
        
        // Button loading state elements
        const $registerBtn = $('#register-btn');
        const $btnText = $('#btn-text');
        const $spinner = $('#loading-spinner');

        // Clear previous errors and hide error container
        errorList.html('');
        errorContainer.addClass('hidden');
        
        // Set button to loading state
        $registerBtn.prop('disabled', true);
        $btnText.text('Creating Account');
        $spinner.removeClass('hidden');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Reset button state
                $registerBtn.prop('disabled', false);
                $btnText.text('Create Account');
                $spinner.addClass('hidden');

                // Show success modal
                $('#successModal').removeClass('hidden');

                // Optional: Redirect after 3 seconds
                setTimeout(function () {
                    window.location.href = "{{ route('customer.dashboard') }}";
                }, 3000);
            },
            error: function (xhr) {
                // Reset button state
                $registerBtn.prop('disabled', false);
                $btnText.text('Create Account');
                $spinner.addClass('hidden');

                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        messages.forEach(function (message) {
                            errorList.append(`<li>${message}</li>`);
                        });
                    });
                    errorContainer.removeClass('hidden');
                } else {
                    errorList.append('<li>An unexpected error occurred. Please try again.</li>');
                    errorContainer.removeClass('hidden');
                }
            }
        });
    });
</script>

</body>
</html>