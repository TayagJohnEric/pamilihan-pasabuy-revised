@extends('layout.admin')

@section('title', 'Create Vendor Account')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Create Vendor Account</h2>

        @if($errors->any())
            <div class="mb-4 bg-red-100 p-4 rounded text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.vendor_applications.storeVendor', $application->id) }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Email</label>
                    <input type="email" name="email" class="form-input w-full" value="{{ old('email', $application->applicant_email) }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-input w-full" value="{{ old('phone_number', $application->applicant_phone_number) }}" required>
                    @error('phone_number')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" class="form-input w-full" required>
                    @error('password')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input w-full" required>
                </div>
            </div>

            <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Create Vendor</button>
        </form>
    </div>
</div>
@endsection
