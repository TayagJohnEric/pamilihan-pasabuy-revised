<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RiderApplication;
use App\Models\Rider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;



class AdminRiderApplicationController extends Controller
{
    // Index: Show all rider applications
    public function index()
{
    $applications = RiderApplication::where('status', 'pending')->latest()->get();
    return view('admin.user-management.rider-application.index', compact('applications'));
}
    // Show: Show details of a specific application
    public function show($id)
    {
        $application = RiderApplication::findOrFail($id);
        return view('admin.user-management.rider-application.show', compact('application'));
    }

    // Delete a specific application
    public function destroy($id)
    {
        $application = RiderApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.rider_applications.index')->with('success', 'Application deleted.');
    }

    // Rider create form (prefilled from application)
    public function createRiderFromApplication($id)
    {
        $application = RiderApplication::findOrFail($id);
        return view('admin.user-management.rider-application.create', compact('application'));
    }

    // Store the new rider + user account
    public function storeRiderFromApplication(Request $request, $id)
    {
        $application = RiderApplication::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, $application) {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone_number' => $request->phone_number,
                'password'   => Hash::make($request->password),
                'role'       => 'rider',
            ]);

            Rider::create([
                'user_id' => $user->id,
                'license_number' => $application->driver_license_number,
                'vehicle_type'   => $application->vehicle_type,
                'verification_status' => 'verified',
            ]);

            // Optionally update the application status
            $application->update([
                'status' => 'approved',
                'reviewed_by_user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('admin.rider_applications.index')->with('success', 'Rider account created.');
    }
}
