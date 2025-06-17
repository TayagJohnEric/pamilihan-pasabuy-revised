<!-- Edit Modal -->
<div id="editModal-{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Edit User</h2>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" value="{{ $user->first_name }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" value="{{ $user->last_name }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="col-span-2">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ $user->phone_number }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="col-span-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="vendor" {{ $user->role == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            <option value="rider" {{ $user->role == 'rider' ? 'selected' : '' }}>Rider</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}
                                   class="form-checkbox">
                            <span class="ml-2 text-sm">Is Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal({{ $user->id }})"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


