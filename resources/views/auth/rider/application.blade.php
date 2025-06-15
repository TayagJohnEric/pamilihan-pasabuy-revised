<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <title>Rider Application</title>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        </style>
</head>
<body>
   
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Rider Application Form</h1>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form action="{{ route('rider-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
        @csrf

        <div>
            <label class="block">Full Name</label>
            <input type="text" name="full_name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">Contact Number</label>
            <input type="text" name="contact_number" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">Birth Date</label>
            <input type="date" name="birth_date" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">Address</label>
            <textarea name="address" class="w-full border rounded px-3 py-2" required></textarea>
        </div>

        <div>
            <label class="block">Vehicle Type</label>
            <input type="text" name="vehicle_type" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">Vehicle Model</label>
            <input type="text" name="vehicle_model" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">License Plate Number</label>
            <input type="text" name="license_plate_number" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">Driver License Number</label>
            <input type="text" name="driver_license_number" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">License Expiry Date</label>
            <input type="date" name="license_expiry_date" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block">NBI Clearance (PDF or Image)</label>
            <input type="file" name="nbi_clearance_url" accept="application/pdf,image/*" class="w-full">
        </div>

        <div>
            <label class="block">Valid ID (PDF or Image)</label>
            <input type="file" name="valid_id_url" accept="application/pdf,image/*" class="w-full">
        </div>

        <div>
            <label class="block">Selfie with ID (Image)</label>
            <input type="file" name="selfie_with_id_url" accept="image/*" class="w-full">
        </div>

        <div>
            <label class="block">TIN Number</label>
            <input type="text" name="tin_number" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Submit Application</button>
        </div>
    </form>
</div>


</body>
</html>