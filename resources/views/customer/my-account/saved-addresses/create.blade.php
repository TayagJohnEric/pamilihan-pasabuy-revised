@extends('layout.customer')

@section('title', 'Add New Address')

@section('content')
<div class="max-w-[90rem] mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Add New Address</h2>
        <p class="text-gray-600 mb-4">Fill in the form below to add a new address.</p>

        <form action="{{ route('customer.saved_addresses.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block">District</label>
                <select name="district_id" class="w-full border rounded px-3 py-2">
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block">Address Line 1</label>
                <textarea name="address_line1" class="w-full border rounded px-3 py-2" required></textarea>
            </div>

            <div>
                <label class="block">Address Label</label>
                <input type="text" name="address_label" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block">Delivery Notes</label>
                <textarea name="delivery_notes" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_default" value="1" class="mr-2">
                    Set as default address
                </label>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
