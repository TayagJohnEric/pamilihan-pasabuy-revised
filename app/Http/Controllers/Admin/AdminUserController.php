<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;



class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $roles = ['customer', 'vendor', 'rider']; // roles to filter
        $roleFilter = $request->query('role');

        $users = User::where('role', '!=', 'admin')
            ->when($roleFilter, function ($query) use ($roleFilter) {
                $query->where('role', $roleFilter);
            })
            ->paginate(10);

        return view('admin.user-management.all-users.index', compact('users', 'roles', 'roleFilter'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        return view('admin.user-management.all-users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|unique:users,phone_number,' . $user->id,
            'role'       => 'required|in:customer,vendor,rider',
            'is_active'  => 'boolean',
        ]);

        $user->update($request->only('first_name', 'last_name', 'email', 'phone_number', 'role', 'is_active'));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
