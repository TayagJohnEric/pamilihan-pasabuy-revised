@extends('layout.admin')

@section('title', 'Edit User')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit User</h2>
        <p class="text-gray-600 mb-4">Update user information. Admin users cannot be edited.</p>

        @if ($errors->any())
            <div class="mb-4 text-red-500">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-semibold">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-semibold">Phone Number</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="block font-semibold">Role</label>
                <select name="role" required class="w-full border p-2 rounded">
                    @foreach (['customer', 'vendor', 'rider'] as $role)
                        <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="mr-2">
                    Active
                </label>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
