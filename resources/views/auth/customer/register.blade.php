<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Customer Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Customer Registration</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="registerForm">
    @csrf

    <div class="mb-4">
        <label>First Name</label>
        <input type="text" name="first_name" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
        <label>Last Name</label>
        <input type="text" name="last_name" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
        <label>Email</label>
        <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
        <label>Password</label>
        <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
    </div>

    <div id="errorContainer" class="mb-4 text-red-500"></div>

    <div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">Register</button>
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
        <span id="loadingMessage" class="text-gray-800 text-lg font-medium">Registering...</span>
    </div>
</div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed flex justify-center items-center inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
            <h3 class="text-lg font-semibold mb-4 text-green-600">Success!</h3>
            <p class="mb-6" id="successMessage">You has been successfully registered.</p>
            <button onclick="closeSuccessModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                OK
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
        let submitButton = form.find('button[type="submit"]');

        errorContainer.html('');
        submitButton.prop('disabled', true); // Disable submit
        $('#loadingModal').removeClass('hidden');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#loadingModal').addClass('hidden');
                submitButton.prop('disabled', false); // Re-enable submit

                // Show success modal with message
                $('#successModal').removeClass('hidden');

                // Optional: Redirect after 2 seconds
                setTimeout(function () {
                    window.location.href = "{{ route('customer.dashboard') }}";
                }, 2000);
            },
            error: function (xhr) {
                $('#loadingModal').addClass('hidden');
                submitButton.prop('disabled', false); // Re-enable submit

                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        messages.forEach(function (message) {
                            errorContainer.append(`<li>${message}</li>`);
                        });
                    });
                } else {
                    errorContainer.append('<li>An unexpected error occurred. Please try again.</li>');
                }
            }
        });
    });
</script>



</body>
</html>
