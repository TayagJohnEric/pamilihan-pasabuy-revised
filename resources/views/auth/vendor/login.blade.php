<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token -->

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
<div class="bg-white p-8 rounded-xl shadow w-full max-w-md border border-green-100">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
        <p class="text-gray-600">Sign in to your vendor account</p>
    </div>
    
    <div id="error-msg" class="mb-4 p-3 text-red-600 bg-red-50 border border-red-200 rounded-lg hidden"></div>
    
    <form id="login-form">
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
            <input type="email" name="email" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200" required placeholder="Enter your email">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input type="password" name="password" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none transition-all duration-200" required placeholder="Enter your password">
        </div>
        <div>
            <button type="submit" id="login-btn" class="w-full bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 hover:to-green-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-green-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                <span id="btn-text">Sign In</span>
                <svg id="loading-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </div>
    </form>
    
    <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
            Don't have an account? 
            <a href="{{ route('vendor-applications.create') }}" class="text-green-600 hover:text-green-700 font-semibold">Apply here</a>
        </p>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Set CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $('#login-form').on('submit', function (e) {
            e.preventDefault();
            
            // Set button to loading state
            const $loginBtn = $('#login-btn');
            const $btnText = $('#btn-text');
            const $spinner = $('#loading-spinner');
            
            $loginBtn.prop('disabled', true);
            $btnText.text('Logging in');
            $spinner.removeClass('hidden');
            
            // Hide any previous error messages
            $('#error-msg').addClass('hidden');
            
            $.ajax({
                url: "{{ route('vendor.login') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    // Redirect to dashboard if login is successful
                    window.location.href = "{{ route('vendor.dashboard') }}";
                },
                error: function (xhr) {
                    // Reset button state
                    $loginBtn.prop('disabled', false);
                    $btnText.text('Sign In');
                    $spinner.addClass('hidden');
                    
                    const res = xhr.responseJSON;
                    const errorMsg = res?.errors?.email ? res.errors.email[0] : 'Login failed.';
                    $('#error-msg').text(errorMsg).removeClass('hidden');
                }
            });
        });
    });
</script>
</body>
</html>