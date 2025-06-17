@extends('layout.admin')

@section('title', 'Rider Applications')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Rider Applications</h2>
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td>{{ $application->full_name }}</td>
                    <td>{{ $application->email }}</td>
                    <td>{{ ucfirst($application->status) }}</td>
                    <td>{{ $application->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.rider_applications.show', $application->id) }}" class="text-blue-600">View</a>
                        |
                        <button 
    onclick="openDeleteModal({{ $application->id }})" 
    type="button" 
    class="text-red-600 hover:underline"
>
    Reject
</button>   
                    </td>
                </tr>

                <!--Delete Modal-->
                                                @include('admin.user-management.rider-application.modal.delete-modal')


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
