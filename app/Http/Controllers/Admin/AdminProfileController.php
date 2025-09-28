<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


class AdminProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone_number' => [
                'nullable', 'string', 'max:20',
                Rule::unique('users', 'phone_number')->ignore($user->id),
            ],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'remove_profile_image' => ['nullable', 'boolean'],
        ]);

        // Normalize inputs
        $payload = [
            'first_name' => trim($validated['first_name']),
            'last_name' => trim($validated['last_name']),
            'email' => strtolower(trim($validated['email'])),
            'phone_number' => $validated['phone_number'] ?? null,
        ];

        // Handle image removal
        if ($request->boolean('remove_profile_image') && $user->profile_image_url) {
            if (str_starts_with($user->profile_image_url, 'profile-images/')) {
                Storage::disk('public')->delete($user->profile_image_url);
            }
            $payload['profile_image_url'] = null;
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = 'admin_'.$user->id.'_'.now()->format('Ymd_His').'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('profile-images', $filename, 'public');

            // Delete old image if present and within our directory
            if ($user->profile_image_url && str_starts_with($user->profile_image_url, 'profile-images/')) {
                Storage::disk('public')->delete($user->profile_image_url);
            }

            $payload['profile_image_url'] = $path;
        }

        $user->update($payload);

        return back()->with('success', 'Profile updated successfully.');
    }


    public function editPassword()
    {
        return view('admin.profile.password');
    }


    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'old_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
        ]);

        $user = Auth::user();

        // Update password securely
        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        // Invalidate other sessions for this user
        // Note: logoutOtherDevices requires the current password; we already validated it
        Auth::logoutOtherDevices($validated['password']);

        // Regenerate the session for the current request
        $request->session()->regenerate();

        return back()->with('success', 'Password updated successfully.');
    }

}
