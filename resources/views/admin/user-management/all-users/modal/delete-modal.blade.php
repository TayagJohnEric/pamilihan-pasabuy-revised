<!-- Delete Confirmation Modal -->
<div id="deleteModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Delete User</h2>
            <p class="text-sm text-gray-600 mb-6">
                Are you sure you want to delete <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>? This action cannot be undone.
            </p>

            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-end space-x-3">
                    <button type="button"
                            onclick="closeDeleteModal({{ $user->id }})"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Confirm Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>