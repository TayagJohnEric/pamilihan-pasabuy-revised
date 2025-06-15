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
    <title>Vendor Application</title>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        </style>
</head>
<body>
   

<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Vendor Application Form</h1>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form action="{{ route('vendor-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block">First Name</label>
                <input type="text" name="applicant_first_name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block">Last Name</label>
                <input type="text" name="applicant_last_name" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block">Email</label>
                <input type="email" name="applicant_email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block">Phone Number</label>
                <input type="text" name="applicant_phone_number" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div>
            <label class="block">Proposed Vendor Name</label>
            <input type="text" name="proposed_vendor_name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block">Stall Number Preference</label>
                <input type="text" name="stall_number_preference" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block">Market Section Preference</label>
                <input type="text" name="market_section_preference" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block">Business Description</label>
            <textarea name="business_description" class="w-full border rounded px-3 py-2" required></textarea>
        </div>

        <div>
            <label class="block">Primary Product Categories</label>
            <textarea name="primary_product_categories_description" class="w-full border rounded px-3 py-2" required></textarea>
        </div>

        <div>
            <label class="block">Business Permit Document (PDF/Image)</label>
            <input type="file" name="business_permit_document_url" accept="application/pdf,image/*" class="w-full">
        </div>

        <div>
            <label class="block">DTI Registration (PDF/Image)</label>
            <input type="file" name="dti_registration_url" accept="application/pdf,image/*" class="w-full">
        </div>

        <div>
            <label class="block">BIR Registration (PDF/Image)</label>
            <input type="file" name="bir_registration_url" accept="application/pdf,image/*" class="w-full">
        </div>

        <div>
            <label class="block">Other Documents (You can select multiple)</label>
            <input type="file" name="other_documents[]" multiple accept="application/pdf,image/*" class="w-full">
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="agreed_to_terms" class="mr-2" required>
            <span>I agree to the terms and conditions.</span>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Submit Application</button>
        </div>
    </form>
</div>



</body>
</html>