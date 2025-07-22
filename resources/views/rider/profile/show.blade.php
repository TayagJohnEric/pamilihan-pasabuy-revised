@extends('layout.rider')

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Rider Profile</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}<br>
        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Phone Number:</strong> {{ $user->phone_number ?? 'Not provided' }}<br>
        <strong>Vehicle Type:</strong> {{ $rider->vehicle_type ?? 'Not provided' }}<br>
        <strong>License Number:</strong> {{ $rider->license_number ?? 'Not provided' }}
    </div>

    <a href="{{ route('rider.profile.edit') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Edit Profile
    </a>
</div>
@endsection
