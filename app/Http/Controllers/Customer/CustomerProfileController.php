<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CustomerProfileController extends Controller
{
    public function show()
{
    
    $user = Auth::user()->load(['savedAddresses' => function ($query) {
        $query->where('is_default', true)->with('district');
    }]);

    $defaultAddress = $user->savedAddresses->first();

    return view('customer.my-account.profile.show', compact('user', 'defaultAddress'));
}

    public function edit()
{
    $user = Auth::user();
    return view('customer.my-account.profile.edit', compact('user'));
}

public function update(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone_number' => 'nullable|string|unique:users,phone_number,' . $user->id,
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean',
    ]);

    // Handle image upload if present
    if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');
$validated['profile_image_url'] = $imagePath; // just the relative path
    }

    $user->update($validated);

    return redirect()->route('customer.profile.edit')->with('success', 'Profile updated successfully.');
}

}
