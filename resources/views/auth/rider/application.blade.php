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
            background: #ffffff;
            min-height: 100vh;
        }

        .form-section {
            border-left: 4px solid #3bf689;
            background: #F8FAFC;
        }

        .file-upload {
            border: 2px dashed #CBD5E1;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: #35ff86;
            background-color: #F1F5F9;
        }

        .progress-bar {
            background: linear-gradient(90deg,  #5cff9d,  #35ff86);
            height: 4px;
            border-radius: 2px;
        }
    </style>
</head>
<body>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-10">
            <div class="mx-auto flex items-center justify-center mb-4">
               <div>
                <span class="text-2xl font-bold text-gray-800">PamilihanPasabuy</span>
                <div class="text-xs text-gray-800 leading-none">San Fernando Market</div>
            </div>
            </div>

          <div class="flex items-center space-x-3">
            
        </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-2">Join Our Rider Network</h1>
            <p class="text-gray-600 text-lg">Fill out the application form below to become a verified rider</p>

            <!-- Progress Bar -->
            <div class="mt-6 bg-gray-200 rounded-full h-2 max-w-md mx-auto">
                <div class="progress-bar w-0 h-full rounded-full transition-all duration-500"></div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <form action="{{ route('rider-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Personal Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Personal Information
                    </h2>
                </div>

                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="full_name" placeholder="Enter your full name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" placeholder="your@email.com" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number *</label>
                            <input type="text" name="contact_number" placeholder="+63 xxx xxx xxxx" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth Date *</label>
                            <input type="date" name="birth_date" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Complete Address *</label>
                        <textarea name="address" rows="3" placeholder="Enter your complete address including street, barangay, city, and province" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200 resize-none" required></textarea>
                    </div>
                </div>
            </div>


            <!-- Vehicle Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        Vehicle Information
                    </h2>
                </div>
                
                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type *</label>
                            <input type="text" name="vehicle_type" placeholder="e.g., Motorcycle, Bicycle, Car" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model *</label>
                            <input type="text" name="vehicle_model" placeholder="e.g., Honda Beat, Yamaha Mio" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">License Plate Number *</label>
                        <input type="text" name="license_plate_number" placeholder="ABC 1234" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                    </div>
                </div>
            </div>

            <!-- License Information Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        License Information
                    </h2>
                </div>
                
                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Driver License Number *</label>
                            <input type="text" name="driver_license_number" placeholder="Enter license number" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">License Expiry Date *</label>
                            <input type="date" name="license_expiry_date" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">TIN Number</label>
                        <input type="text" name="tin_number" placeholder="xxx-xxx-xxx-xxx (Optional)" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-green-600 focus:outline-none transition duration-200">
                        <p class="text-sm text-gray-500 mt-1">Tax Identification Number (if available)</p>
                    </div>
                </div>
            </div>


            <!-- Document Upload Section -->
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="form-section px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Required Documents
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Upload the following documents for verification</p>
                </div>
                
                <div class="px-8 py-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NBI Clearance</label>
                            <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center focus-within:border-green-600 transition duration-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input type="file" name="nbi_clearance_url" accept="application/pdf,image/*" class="hidden" id="nbi_clearance">
                                <label for="nbi_clearance" class="cursor-pointer">
                                    <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                    <p class="text-xs text-gray-500 mt-1">PDF or Image (Max 2MB)</p>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid ID</label>
                            <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center focus-within:border-green-600 transition duration-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <input type="file" name="valid_id_url" accept="application/pdf,image/*" class="hidden" id="valid_id">
                                <label for="valid_id" class="cursor-pointer">
                                    <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                    <p class="text-xs text-gray-500 mt-1">PDF or Image (Max 2MB)</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="max-w-md">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Selfie with ID</label>
                        <div class="file-upload border-2 border-gray-200 rounded-lg p-6 text-center focus-within:border-green-600 transition duration-200">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <input type="file" name="selfie_with_id_url" accept="image/*" class="hidden" id="selfie_with_id">
                            <label for="selfie_with_id" class="cursor-pointer">
                                <span class="text-sm text-gray-600">Click to upload or drag and drop</span>
                                <p class="text-xs text-gray-500 mt-1">Image only (Max 2MB)</p>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Take a clear photo of yourself holding your valid ID next to your face</p>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="bg-white rounded-2xl shadow p-8">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ready to Submit?</h3>
                    <p class="text-gray-600 mb-6">Please review your information before submitting your application.</p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                            Review Application
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-blue-green hover:to-green-800 transition duration-200 font-medium shadow-lg transform hover:scale-105">
                            Submit Application
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 mt-4">
                        By submitting this application, you agree to our terms and conditions and privacy policy.
                    </p>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">Need help? Contact our support team for assistance.</p>
        </div>
    </div>
</div>

<script>
    const form = document.querySelector('form');
    const progressBar = document.querySelector('.progress-bar');
    const inputs = form.querySelectorAll('input[required], textarea[required]');

    function updateProgress() {
        let filledInputs = 0;
        inputs.forEach(input => {
            if (input.value.trim() !== '') {
                filledInputs++;
            }
        });

        const progress = (filledInputs / inputs.length) * 100;
        progressBar.style.width = progress + '%';
    }

    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
</script>

</body>
</html>
