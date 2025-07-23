<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminDistrictController extends Controller
{
    /**
     * Display a listing of districts
     */
    public function index()
    {
        $districts = District::orderBy('name')
            ->paginate(10);
        
        return view('admin.platform-operation.delivery-zones.index', compact('districts'));
    }

    /**
     * Show the form for creating a new district
     */
    public function create()
    {
        return view('admin.platform-operation.delivery-zones.create');
    }

    /**
     * Store a newly created district
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:districts,name',
            'distance_km' => 'required|numeric|min:0|max:999999.99',
            'delivery_fee' => 'required|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        District::create([
            'name' => $request->name,
            'distance_km' => $request->distance_km,
            'delivery_fee' => $request->delivery_fee,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District created successfully!');
    }

    /**
     * Display the specified district
     */
    public function show(District $district)
    {
        $district->load(['savedAddresses' => function($query) {
            $query->latest()->take(10);
        }]);
        
        return view('admin.platform-operation.delivery-zones.show', compact('district'));
    }

    /**
     * Show the form for editing the specified district
     */
    public function edit(District $district)
    {
        return view('admin.platform-operation.delivery-zones.edit', compact('district'));
    }

    /**
     * Update the specified district
     */
    public function update(Request $request, District $district)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:districts,name,' . $district->id,
            'distance_km' => 'required|numeric|min:0|max:999999.99',
            'delivery_fee' => 'required|numeric|min:0|max:999999.99',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $district->update([
            'name' => $request->name,
            'distance_km' => $request->distance_km,
            'delivery_fee' => $request->delivery_fee,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District updated successfully!');
    }

    /**
     * Remove the specified district
     */
    public function destroy(District $district)
    {
        // Check if district has saved addresses
        if ($district->savedAddresses()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete district that has saved addresses. Please reassign addresses first.');
        }

        $district->delete();

        return redirect()->route('admin.districts.index')
            ->with('success', 'District deleted successfully!');
    }

    /**
     * Toggle district active status
     */
    public function toggleStatus(District $district)
    {
        $district->update([
            'is_active' => !$district->is_active
        ]);

        $status = $district->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "District {$status} successfully!");
    }

    /**
     * Bulk update delivery fees
     */
    public function bulkUpdateFees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fee_adjustment' => 'required|numeric',
            'adjustment_type' => 'required|in:fixed,percentage',
            'selected_districts' => 'required|array|min:1',
            'selected_districts.*' => 'exists:districts,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $districts = District::whereIn('id', $request->selected_districts)->get();
        
        foreach ($districts as $district) {
            if ($request->adjustment_type === 'fixed') {
                $newFee = $district->delivery_fee + $request->fee_adjustment;
            } else {
                $newFee = $district->delivery_fee * (1 + ($request->fee_adjustment / 100));
            }
            
            $district->update(['delivery_fee' => max(0, $newFee)]);
        }

        return redirect()->back()
            ->with('success', 'Delivery fees updated successfully for selected districts!');
    }
}
