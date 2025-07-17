<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class VendorProfileController extends Controller
{
    
/**
     * Display the vendor's shop profile page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is a vendor
        if ($user->role !== 'vendor') {
            return redirect()->route('vendor.dashboard')->with('error', 'Access denied.');
        }

        $vendor = $user->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // Calculate average rating
        $averageRating = $this->calculateAverageRating($vendor->id);
        
        // Get recent ratings with comments
        $recentRatings = Rating::where('rateable_type', 'App\Models\Vendor')
            ->where('rateable_id', $vendor->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get total ratings count
        $totalRatings = Rating::where('rateable_type', 'App\Models\Vendor')
            ->where('rateable_id', $vendor->id)
            ->count();

        return view('vendor.shop-profile.index', compact('vendor', 'averageRating', 'recentRatings', 'totalRatings'));
    }

    /**
     * Show the form for editing the vendor profile
     */
    public function edit()
    {
        $user = Auth::user();
        
        if ($user->role !== 'vendor') {
            return redirect()->route('vendor.dashboard')->with('error', 'Access denied.');
        }

        $vendor = $user->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        return view('vendor.shop-profile.edit', compact('vendor'));
    }

    /**
     * Update the vendor profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'vendor') {
            return redirect()->route('vendor.dashboard')->with('error', 'Access denied.');
        }

        $vendor = $user->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|string|max:255',
            'stall_number' => 'nullable|string|max:255',
            'market_section' => 'nullable|string|max:255',
            'business_hours' => 'nullable|string|max:255',
            'public_contact_number' => 'nullable|string|max:255',
            'public_email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:1000',
            'accepts_cod' => 'boolean',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'shop_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle shop logo upload
        if ($request->hasFile('shop_logo')) {
            // Delete old logo if exists
            if ($vendor->shop_logo_url) {
                Storage::delete($vendor->shop_logo_url);
            }
            
            $logoPath = $request->file('shop_logo')->store('vendor/logos', 'public');
            $vendor->shop_logo_url = $logoPath;
        }

        // Handle shop banner upload
        if ($request->hasFile('shop_banner')) {
            // Delete old banner if exists
            if ($vendor->shop_banner_url) {
                Storage::delete($vendor->shop_banner_url);
            }
            
            $bannerPath = $request->file('shop_banner')->store('vendor/banners', 'public');
            $vendor->shop_banner_url = $bannerPath;
        }

        // Update vendor details
        $vendor->update([
            'vendor_name' => $request->vendor_name,
            'stall_number' => $request->stall_number,
            'market_section' => $request->market_section,
            'business_hours' => $request->business_hours,
            'public_contact_number' => $request->public_contact_number,
            'public_email' => $request->public_email,
            'description' => $request->description,
            'accepts_cod' => $request->has('accepts_cod'),
        ]);

        // Recalculate average rating
        $averageRating = $this->calculateAverageRating($vendor->id);
        $vendor->update(['average_rating' => $averageRating]);

        return redirect()->route('vendor.profile.index')
            ->with('success', 'Shop profile updated successfully!');
    }

    /**
     * Calculate average rating for a vendor
     */
    private function calculateAverageRating($vendorId)
    {
        $averageRating = Rating::where('rateable_type', 'App\Models\Vendor')
            ->where('rateable_id', $vendorId)
            ->avg('rating_value');

        return $averageRating ? round($averageRating, 2) : 0;
    }

    /**
     * Display ratings and reviews page
     */
    public function ratings()
    {
        $user = Auth::user();
        
        if ($user->role !== 'vendor') {
            return redirect()->route('vendor.dashboard')->with('error', 'Access denied.');
        }

        $vendor = $user->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        // Get all ratings with pagination
        $ratings = Rating::where('rateable_type', 'App\Models\Vendor')
            ->where('rateable_id', $vendor->id)
            ->with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = Rating::where('rateable_type', 'App\Models\Vendor')
                ->where('rateable_id', $vendor->id)
                ->where('rating_value', $i)
                ->count();
        }

        $totalRatings = array_sum($ratingDistribution);
        $averageRating = $this->calculateAverageRating($vendor->id);

        return view('vendor.shop-profile.ratings', compact('ratings', 'ratingDistribution', 'totalRatings', 'averageRating'));
    }

}
