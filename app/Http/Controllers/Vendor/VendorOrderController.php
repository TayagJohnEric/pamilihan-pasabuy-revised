<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorOrderController extends Controller
{
     /**
     * Display a list of new orders for the authenticated vendor.
     * 
     * This method fetches all order items that belong to the vendor's products
     * and joins with related tables to provide complete order context.
     * 
     * @return \Illuminate\View\View
     */
    public function viewNewOrders()
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;
        
        // Check if the user has a vendor profile
        if (!$vendor) {
            abort(403, 'Access denied. Vendor profile required.');
        }

        // Fetch order items with complete context using optimized query
        // Join order_items with orders, products, users, and saved_addresses
        $newOrders = DB::table('order_items as oi')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('users as u', 'o.customer_user_id', '=', 'u.id')
            ->join('saved_addresses as sa', 'o.delivery_address_id', '=', 'sa.id')
            ->join('districts as d', 'sa.district_id', '=', 'd.id')
            ->select([
                // Order item details
                'oi.id as order_item_id',
                'oi.status as item_status',
                'oi.product_name_snapshot',
                'oi.quantity_requested',
                'oi.unit_price_snapshot',
                'oi.customer_budget_requested',
                'oi.customerNotes_snapshot',
                'oi.is_substituted',
                'oi.created_at as item_created_at',
                
                // Order details
                'o.id as order_id',
                'o.order_date',
                'o.status as order_status',
                'o.payment_method',
                'o.payment_status',
                'o.special_instructions',
                'o.final_total_amount',
                'o.delivery_fee',
                
                // Product details
                'p.product_name',
                'p.unit',
                'p.is_budget_based',
                'p.image_url as product_image',
                
                // Customer details
                'u.first_name as customer_first_name',
                'u.last_name as customer_last_name',
                'u.phone_number as customer_phone',
                
                // Delivery address details
                'sa.address_line1',
                'sa.address_label',
                'sa.delivery_notes',
                'd.name as district_name'
            ])
            ->where('p.vendor_id', $vendor->id) // Filter by vendor's products
            ->where('oi.status', 'pending') // Only show pending items
            ->where('o.deleted_at', null) // Exclude soft-deleted orders
            ->where('p.deleted_at', null) // Exclude soft-deleted products
            ->orderBy('o.order_date', 'desc') // Most recent orders first
            ->get();

        // Group order items by order ID for better organization
        $groupedOrders = $newOrders->groupBy('order_id');

        // Calculate summary statistics
        $totalNewOrders = $groupedOrders->count();
        $totalItems = $newOrders->count();
        $totalBudgetRequests = $newOrders->where('customer_budget_requested', '>', 0)->count();

        return view('vendor.orders.new-orders', compact(
            'groupedOrders',
            'totalNewOrders',
            'totalItems',
            'totalBudgetRequests'
        ));
    }

    /**
     * Show detailed view of a specific order for the vendor.
     * 
     * This method displays all items from a specific order that belong
     * to the authenticated vendor, with complete order context.
     * 
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function showOrderDetails($orderId)
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            abort(403, 'Access denied. Vendor profile required.');
        }

        // Fetch the specific order with all vendor's items
        $orderDetails = DB::table('order_items as oi')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('users as u', 'o.customer_user_id', '=', 'u.id')
            ->join('saved_addresses as sa', 'o.delivery_address_id', '=', 'sa.id')
            ->join('districts as d', 'sa.district_id', '=', 'd.id')
            ->select([
                // Order item details
                'oi.*',
                
                // Order details
                'o.id as order_id',
                'o.order_date',
                'o.status as order_status',
                'o.payment_method',
                'o.payment_status',
                'o.special_instructions',
                'o.final_total_amount',
                'o.delivery_fee',
                
                // Product details
                'p.product_name',
                'p.unit',
                'p.is_budget_based',
                'p.image_url as product_image',
                'p.price as current_product_price',
                
                // Customer details
                'u.first_name as customer_first_name',
                'u.last_name as customer_last_name',
                'u.email as customer_email',
                'u.phone_number as customer_phone',
                
                // Delivery address details
                'sa.address_line1',
                'sa.address_label',
                'sa.delivery_notes',
                'd.name as district_name'
            ])
            ->where('o.id', $orderId) // Specific order
            ->where('p.vendor_id', $vendor->id) // Vendor's products only
            ->where('o.deleted_at', null)
            ->where('p.deleted_at', null)
            ->get();

        // Check if order exists and belongs to this vendor
        if ($orderDetails->isEmpty()) {
            abort(404, 'Order not found or you do not have access to this order.');
        }

        // Get the first item to extract order-level information
        $orderInfo = $orderDetails->first();

        return view('vendor.orders.show', compact('orderDetails', 'orderInfo'));
    }

    /**
     * AJAX endpoint to get real-time count of new orders
     * 
     * This method returns a JSON response with the count of pending
     * order items for the authenticated vendor. Useful for dashboard
     * notifications and real-time updates.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewOrdersCount()
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['error' => 'Vendor profile required'], 403);
        }

        // Count pending order items for this vendor
        $newOrdersCount = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->where('p.vendor_id', $vendor->id)
            ->where('oi.status', 'pending')
            ->where('o.deleted_at', null)
            ->where('p.deleted_at', null)
            ->count();

        return response()->json([
            'count' => $newOrdersCount,
            'message' => $newOrdersCount > 0 ? 
                "You have {$newOrdersCount} new order items to review" : 
                'No new orders at this time'
        ]);
    }

    /**
     * Update order item status (e.g., 'pending', 'preparing', 'ready_for_pickup'.)
     * 
     * This method can be used to update the status of an order item
     * when the vendor acknowledges they have seen it or changes its status.
     * 
     * @param Request $request
     * @param int $orderItemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function acknowledgeOrderItem(Request $request, $orderItemId)
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['error' => 'Vendor profile required'], 403);
        }

        // Find the order item and verify it belongs to this vendor
        $orderItem = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->where('oi.id', $orderItemId)
            ->where('p.vendor_id', $vendor->id)
            ->first();

        if (!$orderItem) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        // Get the desired status from request, default to 'preparing'
        $newStatus = $request->input('status', 'preparing');
        
        // Validate the status
        $validStatuses = ['pending', 'preparing', 'ready_for_pickup', 'completed'];
        if (!in_array($newStatus, $validStatuses)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        // Update the order item status
        DB::table('order_items')
            ->where('id', $orderItemId)
            ->update([
                'status' => $newStatus,
                'updated_at' => now()
            ]);

        // Create appropriate success message based on status
        $messages = [
            'preparing' => 'Order item marked as preparing successfully',
            'ready_for_pickup' => 'Order item marked as ready for pickup successfully',
            'completed' => 'Order item marked as completed successfully'
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$newStatus] ?? 'Order item status updated successfully'
        ]);
    }

    /**
     * Add or update fulfillment notes for an order item
     * 
     * This method allows vendors to add notes about how they plan to
     * fulfill a specific order item, including any special preparations
     * or substitutions.
     * 
     * @param Request $request
     * @param int $orderItemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOrderItemNotes(Request $request, $orderItemId)
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['error' => 'Vendor profile required'], 403);
        }

        // Validate the notes input
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        // Find the order item and verify it belongs to this vendor
        $orderItem = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->where('oi.id', $orderItemId)
            ->where('p.vendor_id', $vendor->id)
            ->first();

        if (!$orderItem) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        // Update the order item with fulfillment notes
        DB::table('order_items')
            ->where('id', $orderItemId)
            ->update([
                'vendor_fulfillment_notes' => $request->input('notes'),
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Fulfillment notes added successfully'
        ]);
    }

    /**
     * Update order item pricing (for budget-based items)
     * 
     * This method allows vendors to set the actual price for budget-based
     * items after reviewing the customer's budget request.
     * 
     * @param Request $request
     * @param int $orderItemId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderItemPricing(Request $request, $orderItemId)
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['error' => 'Vendor profile required'], 403);
        }

        // Validate the pricing input
        $request->validate([
            'actual_item_price' => 'required|numeric|min:0',
            'vendor_assigned_quantity_description' => 'nullable|string|max:255'
        ]);

        // Find the order item and verify it belongs to this vendor
        $orderItem = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->where('oi.id', $orderItemId)
            ->where('p.vendor_id', $vendor->id)
            ->first(['oi.*', 'p.is_budget_based']);

        if (!$orderItem) {
            return response()->json(['error' => 'Order item not found'], 404);
        }

        // Check if the item is budget-based
        if (!$orderItem->is_budget_based) {
            return response()->json(['error' => 'This item is not budget-based'], 400);
        }

        // Update the order item with actual pricing
        $updateData = [
            'actual_item_price' => $request->input('actual_item_price'),
            'updated_at' => now()
        ];

        if ($request->filled('vendor_assigned_quantity_description')) {
            $updateData['vendor_assigned_quantity_description'] = $request->input('vendor_assigned_quantity_description');
        }

        DB::table('order_items')
            ->where('id', $orderItemId)
            ->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Item pricing updated successfully'
        ]);
    }

    /**
     * Get order items summary for vendor dashboard
     * 
     * This method returns a summary of order items grouped by status
     * for dashboard widgets and quick overview.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderItemsSummary()
    {
        // Get the authenticated vendor
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['error' => 'Vendor profile required'], 403);
        }

        // Get summary data grouped by status
        $summary = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->where('p.vendor_id', $vendor->id)
            ->where('o.deleted_at', null)
            ->where('p.deleted_at', null)
            ->select(
                'oi.status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN oi.actual_item_price IS NOT NULL THEN oi.actual_item_price * oi.quantity_requested ELSE oi.unit_price_snapshot * oi.quantity_requested END) as total_value')
            )
            ->groupBy('oi.status')
            ->get()
            ->keyBy('status');

        // Calculate totals
        $totalItems = $summary->sum('count');
        $totalValue = $summary->sum('total_value');
        
        // Get recent orders count (last 24 hours)
        $recentOrdersCount = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->where('p.vendor_id', $vendor->id)
            ->where('o.order_date', '>=', now()->subDay())
            ->where('o.deleted_at', null)
            ->where('p.deleted_at', null)
            ->count();

        return response()->json([
            'summary' => [
                'pending' => $summary->get('pending', (object)['count' => 0, 'total_value' => 0]),
                'preparing' => $summary->get('preparing', (object)['count' => 0, 'total_value' => 0]),
                'ready_for_pickup' => $summary->get('ready_for_pickup', (object)['count' => 0, 'total_value' => 0]),
                'completed' => $summary->get('completed', (object)['count' => 0, 'total_value' => 0]),
            ],
            'totals' => [
                'total_items' => $totalItems,
                'total_value' => $totalValue,
                'recent_orders' => $recentOrdersCount
            ]
        ]);
    }
}
