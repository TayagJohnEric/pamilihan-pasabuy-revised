<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminVendorController extends Controller
{
    
  public function index(Request $request)
    {
        $query = Vendor::with(['user'])->orderBy('created_at', 'desc');

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vendor_name', 'LIKE', "%{$search}%")
                    ->orWhere('stall_number', 'LIKE', "%{$search}%")
                    ->orWhere('market_section', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $vendors = $query->paginate(15)->withQueryString();

        return view('admin.platform-operation.vendors.index', compact('vendors'));
    }

    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['is_active' => !$vendor->is_active]);

        $status = $vendor->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Vendor {$vendor->vendor_name} has been {$status}");
    }

}
