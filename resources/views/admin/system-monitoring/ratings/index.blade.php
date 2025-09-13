
@extends('layout.admin')

@section('title', 'Ratings & Reviews')

@section('content')
    <div class="max-w-[90rem] mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Ratings & Reviews</h2>
            <p class="text-gray-600 mb-4">Track and review customer feedback for Vendors and Riders.</p>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.ratings.index') }}" class="mb-6 flex flex-wrap gap-4">
                <select name="rateable_type" class="border rounded px-3 py-2">
                    <option value="">All Types</option>
                    <option value="App\\Models\\Vendor" {{ request('rateable_type') == 'App\\Models\\Vendor' ? 'selected' : '' }}>Vendors</option>
                    <option value="App\\Models\\Rider" {{ request('rateable_type') == 'App\\Models\\Rider' ? 'selected' : '' }}>Riders</option>
                </select>

                <select name="rating_value" class="border rounded px-3 py-2">
                    <option value="">All Ratings</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('rating_value') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                    @endfor
                </select>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
            </form>

            <!-- Ratings Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">User</th>
                            <th class="p-3 text-left">Type</th>
                            <th class="p-3 text-left">Target</th>
                            <th class="p-3 text-left">Rating</th>
                            <th class="p-3 text-left">Comment</th>
                            <th class="p-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ratings as $rating)
                            <tr class="border-b">
                                <td class="p-3">{{ $rating->id }}</td>
                                <td class="p-3">{{ $rating->user->name ?? 'N/A' }}</td>
                                <td class="p-3">
                                    @if ($rating->rateable_type === 'App\\Models\\Vendor')
                                        Vendor
                                    @elseif ($rating->rateable_type === 'App\\Models\\Rider')
                                        Rider
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if ($rating->rateable_type === 'App\\Models\\Vendor')
                                        {{ $rating->rateable->vendor_name ?? 'N/A' }}
                                    @elseif ($rating->rateable_type === 'App\\Models\\Rider')
                                        {{ $rating->rateable->user->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td class="p-3">{{ $rating->rating_value }} / 5</td>
                                <td class="p-3">{{ $rating->comment ?? '-' }}</td>
                                <td class="p-3">{{ $rating->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-3 text-center text-gray-500">No ratings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $ratings->links() }}
            </div>
        </div>
    </div>
@endsection
