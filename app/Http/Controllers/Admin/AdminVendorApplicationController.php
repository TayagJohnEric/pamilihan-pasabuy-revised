<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VendorApplication;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminVendorApplicationController extends Controller
{
    public function index()
    {
        $applications = VendorApplication::latest()->get();
        return view('admin.user-management.vendor-application.index', compact('applications'));
    }

    public function show($id)
    {
        $application = VendorApplication::findOrFail($id);
        return view('admin.user-management.vendor-application.show', compact('application'));
    }

    public function destroy($id)
    {
        $application = VendorApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.vendor_applications.index')->with('success', 'Application deleted.');
    }

    public function createVendorFromApplication($id)
    {
        $application = VendorApplication::findOrFail($id);
        return view('admin.user-management.vendor-application.create', compact('application'));
    }

    public function storeVendorFromApplication(Request $request, $id)
    {
        $application = VendorApplication::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, $application) {
            $user = User::create([
                'first_name' => $application->applicant_first_name,
                'last_name' => $application->applicant_last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role' => 'vendor',
            ]);

            Vendor::create([
                'user_id' => $user->id,
                'vendor_name' => $application->proposed_vendor_name,
                'stall_number' => $application->stall_number_preference,
                'market_section' => $application->market_section_preference,
                'description' => $application->business_description,
                'verification_status' => 'verified',
                'permit_documents_path' => $application->business_permit_document_url,
                'public_contact_number' => $application->applicant_phone_number,
                'public_email' => $application->applicant_email,
            ]);

            $application->update([
                'status' => 'approved',
                'reviewed_by_user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('admin.vendor_applications.index')->with('success', 'Vendor account created.');
    }
}
