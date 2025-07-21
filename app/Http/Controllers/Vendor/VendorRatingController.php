<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorRatingController extends Controller
{
    public function index(Request $request)
{
    $vendor = Auth::user()->vendor;

    if (!$vendor) {
        return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
    }

    $ratingsQuery = Rating::where('rateable_type', Vendor::class)
        ->where('rateable_id', $vendor->id)
        ->with(['user', 'order']);

    if ($request->filled('rating_filter') && $request->rating_filter !== 'all') {
        $ratingsQuery->where('rating_value', $request->rating_filter);
    }

    // Clone for stats (unfiltered version)
    $baseQueryForStats = (clone $ratingsQuery)->getQuery();

    $ratings = $ratingsQuery->orderBy('created_at', 'desc')->paginate(10);

    $ratingStats = $baseQueryForStats
        ->selectRaw('
            COUNT(*) as total_ratings,
            AVG(rating_value) as average_rating,
            SUM(CASE WHEN rating_value = 5 THEN 1 ELSE 0 END) as five_star,
            SUM(CASE WHEN rating_value = 4 THEN 1 ELSE 0 END) as four_star,
            SUM(CASE WHEN rating_value = 3 THEN 1 ELSE 0 END) as three_star,
            SUM(CASE WHEN rating_value = 2 THEN 1 ELSE 0 END) as two_star,
            SUM(CASE WHEN rating_value = 1 THEN 1 ELSE 0 END) as one_star
        ')
        ->first();

    return view('vendor.ratings.index', compact('ratings', 'ratingStats'));
}
}
