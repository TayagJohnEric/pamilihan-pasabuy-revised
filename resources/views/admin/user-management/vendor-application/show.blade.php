@extends('layout.admin')

@section('title', 'Vendor Application Details')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Vendor Application Details</h2>

        <p><strong>Applicant:</strong> {{ $application->applicant_first_name }} {{ $application->applicant_last_name }}</p>
        <p><strong>Email:</strong> {{ $application->applicant_email }}</p>
        <p><strong>Phone:</strong> {{ $application->applicant_phone_number }}</p>
        <p><strong>Proposed Vendor Name:</strong> {{ $application->proposed_vendor_name }}</p>
        <p><strong>Business Description:</strong> {{ $application->business_description }}</p>
        <p><strong>Product Categories:</strong> {{ $application->primary_product_categories_description }}</p>
        <p><strong>Market Preference:</strong> {{ $application->market_section_preference }}</p>
        <p><strong>Stall Preference:</strong> {{ $application->stall_number_preference }}</p>
        <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>

        <h3 class="mt-4 font-semibold">Documents:</h3>
        <ul class="list-disc ml-5">
            @if($application->business_permit_document_url)
                <li><a href="{{ $application->business_permit_document_url }}" target="_blank">Business Permit</a></li>
            @endif
            @if($application->dti_registration_url)
                <li><a href="{{ $application->dti_registration_url }}" target="_blank">DTI Registration</a></li>
            @endif
            @if($application->bir_registration_url)
                <li><a href="{{ $application->bir_registration_url }}" target="_blank">BIR Registration</a></li>
            @endif
            @if($application->other_documents)
                @foreach(json_decode($application->other_documents, true) as $doc)
                    <li><a href="{{ $doc }}" target="_blank">Other Document</a></li>
                @endforeach
            @endif
        </ul>

        <a href="{{ route('admin.vendor_applications.createVendor', $application->id) }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded">Create Vendor Account</a>
    </div>
</div>
@endsection
