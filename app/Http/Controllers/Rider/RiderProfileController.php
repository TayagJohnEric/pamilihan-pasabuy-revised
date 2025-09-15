<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Rider;
use Illuminate\Http\Request;



class RiderProfileController extends Controller
{
    
/**
     * Display the rider's profile.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Ensure the authenticated user is a rider
        if ($user->role !== 'rider') {
            abort(403, 'Access denied. You must be a rider to view this page.');
        }
        
        $rider = $user->rider;
        
        if (!$rider) {
            abort(404, 'Rider profile not found.');
        }
        
        return view('rider.profile.show', compact('rider'));
    }
    
    /**
     * Show the form for editing the rider's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Ensure the authenticated user is a rider
        if ($user->role !== 'rider') {
            abort(403, 'Access denied. You must be a rider to view this page.');
        }
        
        $rider = $user->rider;
        
        if (!$rider) {
            abort(404, 'Rider profile not found.');
        }
        
        return view('rider.profile.edit', compact('rider'));
    }

    /**
     * Update the rider's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Ensure the authenticated user is a rider
        if ($user->role !== 'rider') {
            return redirect()->back()->with('error', 'Access denied. You must be a rider to update this profile.');
        }
        
        $rider = $user->rider;
        
        if (!$rider) {
            return redirect()->back()->with('error', 'Rider profile not found.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'license_number' => ['nullable', 'string', 'max:255'],
            'vehicle_type' => ['nullable', 'string', 'in:bike,motorcycle,car,van'],
            'is_available' => ['boolean'],
        ]);
        
        try {
            // Update user information
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? $user->phone_number,
            ]);
            
            // Update rider-specific information
            $rider->update([
                'license_number' => $validated['license_number'] ?? $rider->license_number,
                'vehicle_type' => $validated['vehicle_type'] ?? $rider->vehicle_type,
                'is_available' => $request->has('is_available') ? true : false,
            ]);
            
            return redirect()->back()->with('success', 'Profile updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }
    
    /**
     * Update the rider's availability status.
     */
    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'rider') {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        $rider = $user->rider;
        
        if (!$rider) {
            return response()->json(['error' => 'Rider profile not found'], 404);
        }
        
        try {
            $rider->update([
                'is_available' => !$rider->is_available
            ]);
            
            return response()->json([
                'success' => true,
                'is_available' => $rider->is_available,
                'message' => $rider->is_available ? 'You are now available for deliveries' : 'You are now unavailable for deliveries'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update availability status'], 500);
        }
    }
}
