@extends('layout.admin')

@section('title', 'Vendor Applications')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Vendor Applications</h2>
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Proposed Shop</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                    <tr>
                        <td>{{ $app->applicant_first_name }} {{ $app->applicant_last_name }}</td>
                        <td>{{ $app->proposed_vendor_name }}</td>
                        <td>{{ $app->applicant_email }}</td>
                        <td>{{ ucfirst($app->status) }}</td>
                        <td>
                            <a href="{{ route('admin.vendor_applications.show', $app->id) }}" class="text-blue-600">View</a> |
                                        <button 
    onclick="openDeleteModal({{ $app->id }})" 
    type="button" 
    class="text-red-600 hover:underline"
>
    Reject
</button>   
                        </td>
                    </tr>

                       <!--Delete Modal-->
                                                @include('admin.user-management.vendor-application.modal.delete-modal')

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>

     function openDeleteModal(applicantId) {
        document.getElementById('deleteModal-' + applicantId).classList.remove('hidden');
    }

    function closeDeleteModal(applicantId) {
        document.getElementById('deleteModal-' + applicantId).classList.add('hidden');
    }
</script>

@endsection
