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
use Illuminate\Support\Facades\Log;


class RiderAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.rider.login');
    }
    
   public function create()
    {
        return view('auth.rider.application');
    }

    public function store(Request $request)
{
    // Debug: Log all request data
    \Log::info('Form submission received', [
        'has_files' => $request->hasFile(['nbi_clearance_url', 'valid_id_url', 'selfie_with_id_url']),
        'files' => [
            'nbi_clearance_url' => $request->hasFile('nbi_clearance_url'),
            'valid_id_url' => $request->hasFile('valid_id_url'),
            'selfie_with_id_url' => $request->hasFile('selfie_with_id_url'),
        ]
    ]);

    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'contact_number' => 'required|string|max:20',
        'birth_date' => 'required|date|before:today',
        'address' => 'required|string|max:500',
        'vehicle_type' => 'required|string|max:100',
        'vehicle_model' => 'required|string|max:100',
        'license_plate_number' => 'required|string|max:50',
        'driver_license_number' => 'required|string|max:100',
        'license_expiry_date' => 'required|date|after:today',
        'nbi_clearance_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'valid_id_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'selfie_with_id_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'tin_number' => 'nullable|string|max:100',
    ]);

    try {
        // Handle file uploads
        if ($request->hasFile('nbi_clearance_url') && $request->file('nbi_clearance_url')->isValid()) {
            $file = $request->file('nbi_clearance_url');
            $filename = time() . '_nbi_' . $file->getClientOriginalName();
            $path = $file->storeAs('rider_documents', $filename, 'public');
            $validated['nbi_clearance_url'] = $path;
            \Log::info('NBI file uploaded', ['path' => $path]);
        }

        if ($request->hasFile('valid_id_url') && $request->file('valid_id_url')->isValid()) {
            $file = $request->file('valid_id_url');
            $filename = time() . '_id_' . $file->getClientOriginalName();
            $path = $file->storeAs('rider_documents', $filename, 'public');
            $validated['valid_id_url'] = $path;
            \Log::info('Valid ID file uploaded', ['path' => $path]);
        }

        if ($request->hasFile('selfie_with_id_url') && $request->file('selfie_with_id_url')->isValid()) {
            $file = $request->file('selfie_with_id_url');
            $filename = time() . '_selfie_' . $file->getClientOriginalName();
            $path = $file->storeAs('rider_documents', $filename, 'public');
            $validated['selfie_with_id_url'] = $path;
            \Log::info('Selfie file uploaded', ['path' => $path]);
        }

        // Create the record
        $application = RiderApplication::create($validated);
        \Log::info('Application created', ['id' => $application->id]);

        return redirect()->back()->with('success', 'Application submitted successfully! We will review your application and contact you soon.');

    } catch (\Exception $e) {
        \Log::error('Application submission failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->withErrors(['error' => 'Failed to submit application: ' . $e->getMessage()])
            ->withInput();
    }
}

   // Updated login function to set is_available to true
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

        // Set is_available to true for the rider
        $user = Auth::user();
        if ($user && $user->rider) {
            $user->rider->is_available = true;
            $user->rider->save();
        }

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

// Updated logout function to set is_available to false
public function logout(Request $request)
{
    // Set is_available to false before logging out
    // uncomment this when in production for more authenticity
    
   /* $user = Auth::user();
    if ($user && $user->rider) {
        $user->rider->is_available = false;
        $user->rider->save();
    } */

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('rider.login');
}

}
