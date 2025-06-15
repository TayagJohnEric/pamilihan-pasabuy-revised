@extends('layout.admin')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">User Management</h2>
        <p class="text-gray-600 mb-4">View, edit, and delete users. Use the filter to view specific roles.</p>

        <!-- Role Filter -->
        <form method="GET" class="mb-4 flex gap-4">
            <select name="role" onchange="this.form.submit()" class="border p-2 rounded">
                <option value="">-- Filter by Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @if(request('role'))
                <a href="{{ route('admin.users.index') }}" class="text-blue-500 underline">Clear Filter</a>
            @endif
        </form>

        <!-- User Table -->
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                    <td class="px-4 py-2">{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
