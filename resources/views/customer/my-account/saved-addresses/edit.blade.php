@extends('layout.customer')

@section('title', 'Edit Address')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Edit Address</h2>
        <p class="text-gray-600 mb-4">Update your saved address below.</p>

        <form action="{{ route('customer.saved_addresses.update', $saved_address->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block">District</label>
                <select name="district_id" class="w-full border rounded px-3 py-2">
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ $district->id == $saved_address->district_id ? 'selected' : '' }}>
                            {{ $district->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block">Address Line 1</label>
                <textarea name="address_line1" class="w-full border rounded px-3 py-2" required>{{ $saved_address->address_line1 }}</textarea>
            </div>

            <div>
                <label class="block">Address Label</label>
                <input type="text" name="address_label" value="{{ $saved_address->address_label }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block">Delivery Notes</label>
                <textarea name="delivery_notes" class="w-full border rounded px-3 py-2">{{ $saved_address->delivery_notes }}</textarea>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_default" value="1" {{ $saved_address->is_default ? 'checked' : '' }} class="mr-2">
                    Set as default address
                </label>
            </div>

            <div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
