<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\User;


class AdminRatingController extends Controller
{
    public function index(Request $request)
    {
        // Start with basic relationships
        $query = Rating::with(['user', 'rateable']);

        // Filtering by rateable_type (Vendor or Rider)
        if ($request->filled('rateable_type')) {
            $query->where('rateable_type', $request->rateable_type);
        }

        // Filtering by rating value
        if ($request->filled('rating_value')) {
            $query->where('rating_value', $request->rating_value);
        }

        $ratings = $query->latest()->paginate(10);

        // Manually load the user relationship for riders after fetching
        $ratings->getCollection()->each(function ($rating) {
            if ($rating->rateable_type === 'App\\Models\\Rider' && $rating->rateable) {
                $rating->rateable->load('user');
            }
        });

        return view('admin.system-monitoring.ratings.index', compact('ratings'));
    }
}
