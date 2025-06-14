<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token -->
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Customer Login</h2>

    <div id="error-msg" class="mb-4 text-red-500 hidden"></div>

    <form id="login-form">
        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Login</button>
        </div>
    </form>
</div>

<!-- Custom Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50">
    <div class="flex flex-col items-center space-y-4 bg-white p-6 rounded-lg shadow-lg">
        <!-- Spinner Icon -->
        <svg class="animate-spin h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        <!-- Custom Loading Message -->
        <span id="loadingMessage" class="text-gray-800 text-lg font-medium">Logging in...</span>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Set CSRF token for all AJAX requests

         function closeSuccessModal() {
        $('#successModal').addClass('hidden');
    }
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#login-form').on('submit', function (e) {
            e.preventDefault();
                    $('#loadingModal').removeClass('hidden');

            

            $.ajax({
                url: "{{ route('customer.login') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                                    $('#loadingModal').addClass('hidden');

                    // Redirect to dashboard if login is successful
                    window.location.href = "{{ route('customer.dashboard') }}";
                },
                error: function (xhr) {
                                    $('#loadingModal').addClass('hidden');

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
