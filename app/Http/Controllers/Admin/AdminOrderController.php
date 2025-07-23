<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    
   public function index(Request $request)
    {
        $query = Order::with(['customer', 'rider', 'deliveryAddress', 'orderItems.product', 'payment'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);
        if ($request->filled('payment_method')) $query->where('payment_method', $request->payment_method);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($customerQuery) use ($search) {
                    $customerQuery->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.platform-operation.orders.index', [
            'orders' => $orders,
            'statuses' => ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled'],
            'paymentStatuses' => ['pending', 'paid', 'failed'],
            'paymentMethods' => ['online_payment', 'cod']
        ]);
    }

    public function show($id)
    {
        $order = Order::with([
            'customer', 'rider', 'deliveryAddress.district',
            'orderItems.product.vendor', 'orderItems.substitutedProduct',
            'payment', 'statusHistory.updatedBy'
        ])->findOrFail($id);

        $availableRiders = User::where('role', 'rider')->where('is_active', true)->get();

        return view('admin.platform-operation.orders.show', compact('order', 'availableRiders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,out_for_delivery,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        DB::transaction(function() use ($order, $request) {
            $order->update(['status' => $request->status]);

            $order->statusHistory()->create([
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by_user_id' => auth()->id(),
            ]);
        });

        return back()->with('success', "Order status updated from {$oldStatus} to {$request->status}");
    }

    public function assignRider(Request $request, $id)
    {
        $request->validate(['rider_user_id' => 'required|exists:users,id']);

        $order = Order::findOrFail($id);
        $rider = User::where('id', $request->rider_user_id)
            ->where('role', 'rider')->where('is_active', true)->firstOrFail();

        $order->update(['rider_user_id' => $rider->id]);

        $order->statusHistory()->create([
            'status' => $order->status,
            'notes' => "Rider assigned: {$rider->first_name} {$rider->last_name}",
            'updated_by_user_id' => auth()->id(),
        ]);

        return back()->with('success', "Rider {$rider->first_name} {$rider->last_name} assigned to order");
    }

}
