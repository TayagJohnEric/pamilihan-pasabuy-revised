@extends('layout.admin')
@section('title', 'District Details')
@section('content')
<div class="max-w-[90rem] mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">District: {{ $district->name }}</h2>
                <p class="text-gray-600">Viewing details for the selected district.</p>
            </div>
            <div class="flex items-center space-x-3 mt-4 sm:mt-0">
                 <a href="{{ route('admin.districts.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
                <a href="{{ route('admin.districts.edit', $district) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-8">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Distance</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($district->distance_km, 2) }} KM</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Delivery Fee</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">₱{{ number_format($district->delivery_fee, 2) }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if ($district->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Inactive
                            </span>
                        @endif
                    </dd>
                </div>
                 <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Date Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $district->created_at?->format('M d, Y, h:i A') ?? 'N/A' }}</dd>
                </div>
                 <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $district->updated_at?->format('M d, Y, h:i A') ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
            Recent Saved Addresses in this District ({{ $district->savedAddresses->count() }})
        </h3>
        <div class="overflow-x-auto">
            @if($district->savedAddresses->isNotEmpty())
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address Line</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Added</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($district->savedAddresses as $address)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $address->user?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $address->street_address }}, {{ $address->city }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $address->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10 px-6 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">No saved addresses found in this district yet.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-red-50 border border-red-200 rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-red-800">Delete District</h3>
        <div class="mt-2 max-w-xl text-sm text-red-700">
            <p>Once you delete a district, this action cannot be undone. You can only delete districts that do not have any customer addresses associated with them.</p>
        </div>
        <div class="mt-5">
            <form action="{{ route('admin.districts.destroy', $district) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Are you sure you want to delete this district? This action cannot be undone.')"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                    Delete District
                </button>
            </form>
        </div>
    </div>
</div>
@endsection