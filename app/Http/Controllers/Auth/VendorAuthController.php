<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class VendorAuthController extends Controller
{
     public function create()
    {
        return view('auth.vendor.application');
    }

    public function store(Request $request)
    {
        // Debug: Log all request data
        \Log::info('Vendor application submission received', [
            'has_files' => $request->hasFile(['business_permit_document_url', 'dti_registration_url', 'bir_registration_url', 'other_documents']),
            'files' => [
                'business_permit_document_url' => $request->hasFile('business_permit_document_url'),
                'dti_registration_url' => $request->hasFile('dti_registration_url'),
                'bir_registration_url' => $request->hasFile('bir_registration_url'),
                'other_documents' => $request->hasFile('other_documents'),
            ]
        ]);

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

        try {
            // Convert agreed_to_terms to integer
            $validated['agreed_to_terms'] = $request->has('agreed_to_terms') ? 1 : 0;

            // Handle file uploads with better naming and storage
            if ($request->hasFile('business_permit_document_url') && $request->file('business_permit_document_url')->isValid()) {
                $file = $request->file('business_permit_document_url');
                $filename = time() . '_permit_' . $file->getClientOriginalName();
                $path = $file->storeAs('vendor_documents', $filename, 'public');
                $validated['business_permit_document_url'] = $path;
                \Log::info('Business permit file uploaded', ['path' => $path]);
            }

            if ($request->hasFile('dti_registration_url') && $request->file('dti_registration_url')->isValid()) {
                $file = $request->file('dti_registration_url');
                $filename = time() . '_dti_' . $file->getClientOriginalName();
                $path = $file->storeAs('vendor_documents', $filename, 'public');
                $validated['dti_registration_url'] = $path;
                \Log::info('DTI registration file uploaded', ['path' => $path]);
            }

            if ($request->hasFile('bir_registration_url') && $request->file('bir_registration_url')->isValid()) {
                $file = $request->file('bir_registration_url');
                $filename = time() . '_bir_' . $file->getClientOriginalName();
                $path = $file->storeAs('vendor_documents', $filename, 'public');
                $validated['bir_registration_url'] = $path;
                \Log::info('BIR registration file uploaded', ['path' => $path]);
            }

            if ($request->hasFile('other_documents')) {
                $paths = [];
                foreach ($request->file('other_documents') as $index => $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_other_' . $index . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('vendor_documents', $filename, 'public');
                        $paths[] = $path;
                        \Log::info('Other document file uploaded', ['path' => $path]);
                    }
                }
                $validated['other_documents'] = json_encode($paths);
            }

            // Create the record
            $application = VendorApplication::create($validated);
            \Log::info('Vendor application created', ['id' => $application->id]);

            return redirect()->back()->with('success', 'Vendor application submitted successfully! We will review your application and contact you soon.');

        } catch (\Exception $e) {
            \Log::error('Vendor application submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to submit application: ' . $e->getMessage()])
                ->withInput();
        }
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


// TEMPORARY: This is a test version of the logout function currently being used for development/testing purposes.
//
// This version does NOT include the logic that automatically sets `is_accepting_orders` to false for vendors upon logout.
// It’s intended to simplify testing and isolate other features without triggering vendor status updates.
//
// Remember to remove or replace this with the original (production-ready) logout function when testing is complete.

public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login.form');
    }


/*
// NOTE: This logout function is commented out for now because we are currently testing an alternative logout implementation.
//
// Purpose of this original version:
// In the actual (production) scenario, when a vendor logs out, their `is_accepting_orders` status should be automatically set to `false`
// to indicate that the vendor is offline and no longer accepting new orders.
//
// Uncomment this function when reverting to the intended production behavior.


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
*/

}
