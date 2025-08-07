<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class VendorAuthController extends Controller
{
     public function create()
    {
        return view('auth.vendor.application');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_first_name' => 'required|string|max:255',
            'applicant_last_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone_number' => 'required|string|max:20',
            'proposed_vendor_name' => 'required|string|max:255',
            'stall_number_preference' => 'nullable|string|max:100',
            'market_section_preference' => 'nullable|string|max:100',
            'business_description' => 'required|string',
            'primary_product_categories_description' => 'required|string',
            'business_permit_document_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dti_registration_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bir_registration_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'other_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'agreed_to_terms' => 'required|accepted',
        ]);

        // Convert agreed_to_terms to integer
        $validated['agreed_to_terms'] = $request->has('agreed_to_terms') ? 1 : 0;

        // File handling
        if ($request->hasFile('business_permit_document_url')) {
            $validated['business_permit_document_url'] = $request->file('business_permit_document_url')->store('vendor_documents');
        }

        if ($request->hasFile('dti_registration_url')) {
            $validated['dti_registration_url'] = $request->file('dti_registration_url')->store('vendor_documents');
        }

        if ($request->hasFile('bir_registration_url')) {
            $validated['bir_registration_url'] = $request->file('bir_registration_url')->store('vendor_documents');
        }

        if ($request->hasFile('other_documents')) {
            $paths = [];
            foreach ($request->file('other_documents') as $file) {
                $paths[] = $file->store('vendor_documents');
            }
            $validated['other_documents'] = json_encode($paths);
        }

        VendorApplication::create($validated);

        return redirect()->back()->with('success', 'Vendor application submitted successfully!');
    }


     public function showLoginForm()
    {
        return view('auth.vendor.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $credentials['role'] = 'vendor';

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Check if AJAX request
        if ($request->ajax()) {
            return response()->json(['message' => 'Login successful'], 200);
        }

        return redirect()->intended(route('vendor.dashboard'));
    }

    if ($request->ajax()) {
        return response()->json([
            'errors' => ['email' => ['Invalid credentials or not a vendor.']]
        ], 422);
    }

    return back()->withErrors([
        'email' => 'Invalid credentials or not a rider.',
    ]);
}

 public function logout(Request $request)
{
    $user = Auth::user();

    // Ensure the user is a vendor before attempting to update
    if ($user && $user->vendor) {
        $vendor = $user->vendor;

        // Only update if it's currently true
        if ($vendor->is_accepting_orders) {
            $vendor->is_accepting_orders = false;
            $vendor->save();
        }
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('vendor.login.form');
}

}
