@extends('layout.admin')

@section('title', 'Application Details')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Rider Application Details</h2>

        <p><strong>Full Name:</strong> {{ $application->full_name }}</p>
        <p><strong>Email:</strong> {{ $application->email }}</p>
        <p><strong>Contact:</strong> {{ $application->contact_number }}</p>
        <p><strong>Address:</strong> {{ $application->address }}</p>
        <p><strong>Vehicle:</strong> {{ $application->vehicle_type }} - {{ $application->vehicle_model }}</p>
        <p><strong>License:</strong> {{ $application->driver_license_number }}</p>
        <p><strong>License Expiry:</strong> {{ $application->license_expiry_date }}</p>
        <p><strong>TIN Number:</strong> {{ $application->tin_number }}</p>
        <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>

        <h3 class="mt-4 font-semibold">Documents:</h3>
        <ul class="list-disc ml-5">
            @if($application->nbi_clearance_url)
                <li><a href="{{ $application->nbi_clearance_url }}" target="_blank">NBI Clearance</a></li>
            @endif
            @if($application->valid_id_url)
                <li><a href="{{ $application->valid_id_url }}" target="_blank">Valid ID</a></li>
            @endif
            @if($application->selfie_with_id_url)
                <li><a href="{{ $application->selfie_with_id_url }}" target="_blank">Selfie with ID</a></li>
            @endif
        </ul>

        <a href="{{ route('admin.rider_applications.createRider', $application->id) }}" class="inline-block mt-6 bg-blue-600 text-white px-4 py-2 rounded">Create Rider Account</a>
    </div>
</div>
@endsection
