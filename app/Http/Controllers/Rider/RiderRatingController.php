<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiderRatingController extends Controller
{
     public function ratings(Request $request)
    {
        $rider = Auth::user();

        $ratings = Rating::where('rateable_id', $rider->id)
            ->where('rateable_type', User::class)
            ->with(['order.customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $allRatings = Rating::where('rateable_id', $rider->id)
            ->where('rateable_type', User::class);

        $averageRating = $allRatings->avg('rating_value');  
        $totalRatings = $allRatings->count();

        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = Rating::where('rateable_id', $rider->id)
                ->where('rateable_type', User::class)
                ->where('rating_value', $i)
                ->count();
            $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;

            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        $recentPositive = Rating::where('rateable_id', $rider->id)
            ->where('rateable_type', User::class)
            ->where('rating_value', '>=', 4)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->with(['order.customer'])
            ->latest()
            ->take(3)
            ->get();

        $recentNegative = Rating::where('rateable_id', $rider->id)
            ->where('rateable_type', User::class)
            ->where('rating_value', '<=', 2)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->with(['order.customer'])
            ->latest()
            ->take(3)
            ->get();

        return view('rider.ratings.index', compact(
            'ratings',
            'averageRating',
            'totalRatings',
            'ratingDistribution',
            'recentPositive',
            'recentNegative'
        ));
    }
}
