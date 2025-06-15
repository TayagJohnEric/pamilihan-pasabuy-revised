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
                            <form action="{{ route('admin.vendor_applications.destroy', $app->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete application?')" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
