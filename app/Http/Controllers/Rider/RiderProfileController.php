<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RiderProfileController extends Controller
{
    
public function show()
    {
        $user = Auth::user();
        $rider = $user->rider;

        return view('rider.profile.show', compact('user', 'rider'));
    }

    public function edit()
    {
        $user = Auth::user();
        $rider = $user->rider;

        return view('rider.profile.edit', compact('user', 'rider'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rider = $user->rider;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone_number' => ['nullable', 'string', Rule::unique('users', 'phone_number')->ignore($user->id)],
            'profile_image' => 'nullable|image|max:2048',
            'license_number' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|in:bike,motorcycle,car',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->update(['profile_image_url' => $path]);
        }

        $rider->update([
            'license_number' => $request->license_number,
            'vehicle_type' => $request->vehicle_type,
        ]);

        return redirect()->route('rider.profile.show')->with('success', 'Profile updated successfully.');
    }

}
