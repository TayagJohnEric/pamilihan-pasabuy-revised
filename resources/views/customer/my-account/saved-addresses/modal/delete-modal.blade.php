<!-- Delete Confirmation Modal -->
<div id="delete-modal-{{ $address->id }}" class="modal-overlay fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="modal-content bg-white rounded-lg w-full max-w-md shadow-lg p-6 relative">
        <button onclick="closeDeleteModal({{ $address->id }})" class="absolute top-2 right-2 text-gray-600 hover:text-black">×</button>

        <h2 class="text-lg font-semibold mb-4 text-red-600">Delete Address</h2>
        <p class="mb-6 text-sm text-gray-700">Are you sure you want to delete this address? This action cannot be undone.</p>

        <form action="{{ route('customer.saved_addresses.destroy', $address->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-4">
                <button type="button" onclick="closeDeleteModal({{ $address->id }})" class="px-4 py-2 text-gray-700 hover:text-black">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete</button>
            </div>
        </form>
    </div>
</div>
