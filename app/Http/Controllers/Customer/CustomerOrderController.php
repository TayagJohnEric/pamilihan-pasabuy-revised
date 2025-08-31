<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index(Request $request)
    {
        // Get the authenticated customer
        $customer = Auth::user();
        
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(403, 'Unauthorized access.');
        }

        // Build the query for customer's orders
        $query = Order::where('customer_user_id', $customer->id)
            ->with([
                'orderItems.product',
                'orderItems.substitutedProduct',
                'deliveryAddress',
                'rider',
                'payment'
            ])
            ->orderBy('order_date', 'desc');

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply date range filter if provided
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        // Paginate the results
        $orders = $query->paginate(10)->appends($request->query());

        // Get order status counts for filter badges
        $statusCounts = Order::where('customer_user_id', $customer->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('customer.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated customer
        if ($order->customer_user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this order.');
        }

        // Load relationships
        $order->load([
            'orderItems.product.vendor',
            'orderItems.substitutedProduct',
            'deliveryAddress',
            'rider',
            'payment',
            'statusHistory.updatedBy'
        ]);

        return view('customer.orders.show', compact('order'));
    }
}
