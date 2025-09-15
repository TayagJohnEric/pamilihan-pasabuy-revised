@extends('layout.rider')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('rider.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- User Info -->
        <div class="mb-4">
            <label for="first_name" class="block font-semibold">First Name</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="last_name" class="block font-semibold">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="phone_number" class="block font-semibold">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="profile_image" class="block font-semibold">Profile Image</label>
            <input type="file" name="profile_image" class="w-full border rounded px-3 py-2">
            @if ($user->profile_image_url)
                <img src="{{ asset('storage/' . $user->profile_image_url) }}" class="w-20 h-20 rounded-full mt-2">
            @endif
        </div>

        <!-- Rider Info -->
        <div class="mb-4">
            <label for="vehicle_type" class="block font-semibold">Vehicle Type</label>
            <select name="vehicle_type" id="vehicle_type" class="w-full border rounded px-3 py-2">
                <option value="">-- Select --</option>
                <option value="bike" {{ old('vehicle_type', $rider->vehicle_type) == 'bike' ? 'selected' : '' }}>Bike</option>
                <option value="motorcycle" {{ old('vehicle_type', $rider->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                <option value="car" {{ old('vehicle_type', $rider->vehicle_type) == 'car' ? 'selected' : '' }}>Car</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="license_number" class="block font-semibold">License Number</label>
            <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $rider->license_number) }}" class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex justify-between">
            <a href="{{ route('rider.profile') }}" class="text-gray-600">Cancel</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save Changes</button>
        </div>
    </form>
</div>
@endsection
