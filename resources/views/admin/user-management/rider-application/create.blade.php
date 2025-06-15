@extends('layout.admin')

@section('title', 'Create Rider Account')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Create Rider Account</h2>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.rider_applications.storeRider', $application->id) }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', explode(' ', $application->full_name)[0]) }}" class="form-input w-full" required>
                    @error('first_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', explode(' ', $application->full_name)[1] ?? '') }}" class="form-input w-full" required>
                    @error('last_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $application->email) }}" class="form-input w-full" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $application->contact_number) }}" class="form-input w-full" required>
                    @error('phone_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" class="form-input w-full" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input w-full" required>
                </div>
            </div>

            <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded">Create Account</button>
        </form>
    </div>
</div>
@endsection
