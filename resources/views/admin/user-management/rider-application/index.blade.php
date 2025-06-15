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
                        <form action="{{ route('admin.rider_applications.destroy', $application->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600" onclick="return confirm('Delete this application?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
