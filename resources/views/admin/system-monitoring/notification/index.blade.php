
@extends('layout.admin')

@section('title', 'Notification Log')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Notification Log</h2>
        <p class="text-gray-600 mb-4">Track all notifications happening in the system</p>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.notifications.index') }}" class="mb-4 flex gap-4 flex-wrap">
            <select name="type" class="border rounded p-2">
                <option value="">All Types</option>
                <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>Order</option>
                <option value="system" {{ request('type') === 'system' ? 'selected' : '' }}>System</option>
                <option value="rating" {{ request('type') === 'rating' ? 'selected' : '' }}>Rating</option>
                <!-- Add more types as needed -->
            </select>

            <select name="read_status" class="border rounded p-2">
                <option value="">All</option>
                <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Read</option>
                <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Unread</option>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
        </form>

        <!-- Notifications Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">User</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Message</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $notification->id }}</td>
                            <td class="px-4 py-2">{{ $notification->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ ucfirst($notification->type) }}</td>
                            <td class="px-4 py-2">{{ $notification->title ?? '-' }}</td>
                            <td class="px-4 py-2">{{ is_array($notification->message) ? json_encode($notification->message) : $notification->message }}</td>
                            <td class="px-4 py-2">
                                @if($notification->read_at)
                                    <span class="text-green-600 font-medium">Read</span>
                                @else
                                    <span class="text-red-600 font-medium">Unread</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-500">No notifications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
