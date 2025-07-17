<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RiderApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\VendorApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RiderAuthController extends Controller
{
    
   public function create()
    {
        return view('auth.rider.application');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_model' => 'required|string|max:100',
            'license_plate_number' => 'required|string|max:50',
            'driver_license_number' => 'required|string|max:100',
            'license_expiry_date' => 'required|date',
            'nbi_clearance_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'valid_id_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'selfie_with_id_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'tin_number' => 'nullable|string|max:100',
        ]);

        // Handle file uploads
        if ($request->hasFile('nbi_clearance_url')) {
            $validated['nbi_clearance_url'] = $request->file('nbi_clearance_url')->store('rider_documents');
        }

        if ($request->hasFile('valid_id_url')) {
            $validated['valid_id_url'] = $request->file('valid_id_url')->store('rider_documents');
        }

        if ($request->hasFile('selfie_with_id_url')) {
            $validated['selfie_with_id_url'] = $request->file('selfie_with_id_url')->store('rider_documents');
        }

        RiderApplication::create($validated);

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }

     public function showLoginForm()
    {
        return view('auth.rider.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $credentials['role'] = 'rider';

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Check if AJAX request
        if ($request->ajax()) {
            return response()->json(['message' => 'Login successful'], 200);
        }

        return redirect()->intended(route('rider.dashboard'));
    }

    if ($request->ajax()) {
        return response()->json([
            'errors' => ['email' => ['Invalid credentials or not a rider.']]
        ], 422);
    }

    return back()->withErrors([
        'email' => 'Invalid credentials or not a rider.',
    ]);
}

 public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('rider.login');
    }

}
