@extends('layout.customer')

@section('title', 'My Saved Addresses')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">My Saved Addresses</h2>
        <p class="text-gray-600 mb-4">Below is a list of your saved addresses.</p>

        <a href="{{ route('customer.saved_addresses.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Address</a>

        @foreach ($addresses as $address)
            <div class="border rounded p-4 mb-4">
                <div class="flex justify-between">
                    <div>
                        <h3 class="font-bold">{{ $address->address_label }}</h3>
                        <p>{{ $address->address_line1 }}</p>
                        <p>District: {{ $address->district->name }}</p>
                        <p>{{ $address->delivery_notes }}</p>
                        @if($address->is_default)
                            <span class="text-green-600 font-semibold">Default Address</span>
                        @endif
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('customer.saved_addresses.edit', $address->id) }}" class="text-blue-500 hover:underline">Edit</a>
                        <form action="{{ route('customer.saved_addresses.destroy', $address->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
