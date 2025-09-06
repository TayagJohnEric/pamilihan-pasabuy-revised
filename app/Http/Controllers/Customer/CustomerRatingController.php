<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Rider;
use App\Models\Vendor;
use App\Models\OrderStatusHistory;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerRatingController extends Controller
{
   /**
     * Show the rating form for delivered orders
     * Displays rating interface for rider and all vendors involved in the order
     */
    public function showRatingForm(Order $order)
    {
        // Verify order belongs to authenticated customer
        if ($order->customer_user_id !== Auth::id()) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order not found or not accessible.');
        }

        // Check if order is delivered and can be rated
        if ($order->status !== 'delivered') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'This order cannot be rated yet.');
        }

        // Load necessary relationships
        $order->load([
            // Rider is already a User model via rider_user_id
            'rider',
            // Vendors have a user() relation
            'orderItems.product.vendor.user',
            'ratings' // Check existing ratings
        ]);

        // Get unique vendors from order items
        $vendors = $order->orderItems->pluck('product.vendor')->unique('id')->filter();
        
        // Check if customer has already rated this order
        $existingRatings = $order->ratings->where('user_id', Auth::id());
        $hasRated = $existingRatings->isNotEmpty();
        
        // Organize existing ratings by rateable type and id
        $existingRiderRating = $existingRatings->where('rateable_type', 'App\\Models\\User')->first();
        $existingVendorRatings = $existingRatings->where('rateable_type', 'App\\Models\\Vendor')->keyBy('rateable_id');

        return view('customer.orders.rating', compact(
            'order', 
            'vendors', 
            'hasRated', 
            'existingRiderRating', 
            'existingVendorRatings'
        ));
    }

    /**
     * Store ratings for rider and vendors
     * Processes rating submission and updates average ratings
     */
    public function submitRating(Request $request, Order $order)
    {
        // Verify order belongs to authenticated customer
        if ($order->customer_user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order not found.'], 403);
            }
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order not found or not accessible.');
        }

        // Verify order is delivered
        if ($order->status !== 'delivered') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Order cannot be rated yet.'], 400);
            }
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'This order cannot be rated yet.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'rider_rating' => 'required|integer|min:1|max:5',
            'rider_comment' => 'nullable|string|max:1000',
            'vendor_ratings' => 'required|array',
            'vendor_ratings.*' => 'required|integer|min:1|max:5',
            'vendor_comments' => 'nullable|array',
            'vendor_comments.*' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Check if customer has already rated this order
            $existingRatings = Rating::where('order_id', $order->id)
                ->where('user_id', Auth::id())
                ->exists();

            if ($existingRatings) {
                DB::rollBack();
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'You have already rated this order.'], 400);
                }
                return redirect()->route('customer.orders.show', $order)
                    ->with('error', 'You have already rated this order.');
            }

            // Rate the rider (rating the user, not the rider record)
            if ($order->rider_user_id) {
                Rating::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'rateable_id' => $order->rider_user_id,
                    'rateable_type' => 'App\\Models\\User', // Rating the User who is a rider
                    'rating_value' => $request->rider_rating,
                    'comment' => $request->rider_comment,
                ]);

                // Update rider's average rating
                $this->updateRiderAverageRating($order->rider_user_id);
            }

            // Rate each vendor
            foreach ($request->vendor_ratings as $vendorId => $rating) {
                $comment = $request->vendor_comments[$vendorId] ?? null;

                Rating::create([
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'rateable_id' => $vendorId,
                    'rateable_type' => 'App\\Models\\Vendor',
                    'rating_value' => $rating,
                    'comment' => $comment,
                ]);

                // Update vendor's average rating
                $this->updateVendorAverageRating($vendorId);
            }

            // Create notification for rider if exists
            if ($order->rider_user_id) {
                $this->createNotification(
                    $order->rider_user_id,
                    'rating_received',
                    'You Received a New Rating',
                    [
                        'order_id' => $order->id,
                        'rating' => $request->rider_rating,
                        'customer_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                        'message' => 'A customer has rated your delivery service.'
                    ],
                    Order::class,
                    $order->id
                );
            }

            // Create notifications for vendors
            $order->load('orderItems.product.vendor.user');
            $vendorUsers = $order->orderItems->pluck('product.vendor.user')->unique('id')->filter();
            
            foreach ($vendorUsers as $vendorUser) {
                if (isset($request->vendor_ratings[$vendorUser->vendor->id])) {
                    $this->createNotification(
                        $vendorUser->id,
                        'rating_received',
                        'You Received a New Rating',
                        [
                            'order_id' => $order->id,
                            'rating' => $request->vendor_ratings[$vendorUser->vendor->id],
                            'customer_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                            'message' => 'A customer has rated your products and service.'
                        ],
                        Order::class,
                        $order->id
                    );
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for your feedback!',
                    'redirect_url' => route('customer.orders.show', $order)
                ]);
            }

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Thank you for your feedback! Your ratings have been submitted.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting ratings: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to submit ratings. Please try again.'], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to submit ratings. Please try again.')
                ->withInput();
        }
    }


    // ==================== HELPER METHODS ====================

    /**
     * Update rider's average rating based on all their ratings
     * Calculates average from all ratings received as a rider
     */
    private function updateRiderAverageRating($riderUserId)
    {
        // Get all ratings for this rider (user)
        $averageRating = Rating::where('rateable_type', 'App\\Models\\User')
            ->where('rateable_id', $riderUserId)
            ->avg('rating_value');

        // Update rider's average rating
        if ($averageRating) {
            Rider::where('user_id', $riderUserId)
                ->update(['average_rating' => round($averageRating, 2)]);
        }
    }

    /**
     * Update vendor's average rating based on all their ratings
     * Calculates average from all ratings received as a vendor
     */
    private function updateVendorAverageRating($vendorId)
    {
        // Get all ratings for this vendor
        $averageRating = Rating::where('rateable_type', 'App\\Models\\Vendor')
            ->where('rateable_id', $vendorId)
            ->avg('rating_value');

        // Update vendor's average rating
        if ($averageRating) {
            Vendor::where('id', $vendorId)
                ->update(['average_rating' => round($averageRating, 2)]);
        }
    }

    /**
     * Create notification for users
     * Follows the existing notification pattern from RiderOrderController
     */
    private function createNotification($userId, $type, $title, $message, $entityType = null, $entityId = null)
    {
        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'related_entity_type' => $entityType,
            'related_entity_id' => $entityId,
        ]);
    }
}
