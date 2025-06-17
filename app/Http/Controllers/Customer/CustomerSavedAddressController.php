<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SavedAddress;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerSavedAddressController extends Controller
{
    public function index()
    {
        $addresses = SavedAddress::where('user_id', Auth::id())->with('district')->get();
        $districts = District::where('is_active', true)->orderBy('name')->get();
        return view('customer.my-account.saved-addresses.index', compact('addresses', 'districts'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'address_line1' => 'required|string',
            'address_label' => 'required|string|max:255',
            'delivery_notes' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            SavedAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        SavedAddress::create([
            'user_id' => Auth::id(),
            'district_id' => $request->district_id,
            'address_line1' => $request->address_line1,
            'address_label' => $request->address_label,
            'delivery_notes' => $request->delivery_notes,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->route('customer.saved_addresses.index')->with('success', 'Address saved successfully.');
    }


    public function update(Request $request, SavedAddress $saved_address)
    {
        if ($saved_address->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this address.');
        }

        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'address_line1' => 'required|string',
            'address_label' => 'required|string|max:255',
            'delivery_notes' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            SavedAddress::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        $saved_address->update([
            'district_id' => $request->district_id,
            'address_line1' => $request->address_line1,
            'address_label' => $request->address_label,
            'delivery_notes' => $request->delivery_notes,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->route('customer.saved_addresses.index')->with('success', 'Address updated successfully.');
    }

    public function destroy(SavedAddress $saved_address)
    {
        if ($saved_address->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this address.');
        }

        $saved_address->delete();

        return redirect()->route('customer.saved_addresses.index')->with('success', 'Address deleted successfully.');
    }
}
