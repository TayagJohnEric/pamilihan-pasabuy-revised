<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;




class RiderProfileController extends Controller
{
    
/**
     * Display the authenticated rider's profile.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Ensure the user is a rider
        if ($user->role !== 'rider' || !$user->rider) {
            abort(403, 'Access denied. Rider profile required.');
        }

        $rider = $user->rider;

        return view('rider.profile.show', compact('user', 'rider'));
    }

    /**
     * Show the form for editing the authenticated rider's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Ensure the user is a rider
        if ($user->role !== 'rider' || !$user->rider) {
            abort(403, 'Access denied. Rider profile required.');
        }

        $rider = $user->rider;

        return view('rider.profile.edit', compact('user', 'rider'));
    }

    /**
     * Update the authenticated rider's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Ensure the user is a rider
        if ($user->role !== 'rider' || !$user->rider) {
            abort(403, 'Access denied. Rider profile required.');
        }

        $rider = $user->rider;

        // Validation rules
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id)
            ],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'license_number' => 'nullable|string|max:100',
            'vehicle_type' => 'nullable|string|max:50',
            'is_available' => 'boolean'
        ]);

        try {
            // Handle profile image upload
            $profileImageUrl = $user->profile_image_url;
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($profileImageUrl) {
                    Storage::disk('public')->delete($profileImageUrl);
                }
                
                $profileImageUrl = $request->file('profile_image')->store('profile_images', 'public');
            }

            // Update user information
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'profile_image_url' => $profileImageUrl,
            ]);

            // Update rider information
            $rider->update([
                'license_number' => $request->license_number,
                'vehicle_type' => $request->vehicle_type,
                'is_available' => $request->boolean('is_available'),
            ]);

            return redirect()->route('rider.profile.show')
                           ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update profile. Please try again.'])
                        ->withInput();
        }
    }
}
