<!-- Create Address Modal -->
<div id="create-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-white rounded-lg w-full max-w-md shadow-lg p-6 relative">
        <button onclick="closeCreateModal()" class="absolute top-2 right-2 text-gray-600 hover:text-black">×</button>

        <h2 class="text-lg font-semibold mb-4">Add New Address</h2>

        <form action="{{ route('customer.saved_addresses.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">District</label>
                <select name="district_id" required class="w-full mt-1 border border-gray-300 rounded">
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Address Line 1</label>
                <input type="text" name="address_line1" required class="w-full mt-1 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Address Label</label>
                <input type="text" name="address_label" required class="w-full mt-1 border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Delivery Notes</label>
                <textarea name="delivery_notes" class="w-full mt-1 border border-gray-300 rounded"></textarea>
            </div>

                <div class="mb-4 flex items-center justify-between">
                        <label for="create_is_default" class="flex items-center cursor-pointer">
                            <span class="mr-3 text-gray-700">Set as default</span>
                            <div class="relative w-14 h-8">
                                <input
                                    type="checkbox"
                                    name="is_default"
                                    id="create_is_default"
                                    value="1"
                                    class="sr-only peer"
                                >
                                <!-- The background track that turns green when checked -->
                                <div class="w-full h-full bg-[#d4d9de] rounded-full shadow-inner transition-all duration-300 peer-checked:bg-green-500"></div>
                                
                                <!-- The circular knob -->
                                <div class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition-all duration-300
                                    peer-checked:translate-x-6">
                                </div>
                            </div>
                        </label>
                </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
            </div>
        </form>
    </div>
</div>