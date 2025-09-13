<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with([
            'order.rider',
            'order.orderItems.product.vendor'
        ]);

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->whereHas('order', function ($orderQuery) use ($searchTerm) {
                // Search by rider name
                $orderQuery->whereHas('rider', function ($riderQuery) use ($searchTerm) {
                    $riderQuery->where(function ($nameQuery) use ($searchTerm) {
                        $nameQuery->where('first_name', 'LIKE', "%{$searchTerm}%")
                                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$searchTerm}%"]);
                    });
                })
                // Search by vendor name
                ->orWhereHas('orderItems.product.vendor', function ($vendorQuery) use ($searchTerm) {
                    $vendorQuery->where('vendor_name', 'LIKE', "%{$searchTerm}%");
                });
            });
        }

        // Apply filters if provided
        if ($request->filled('payment_method_used')) {
            $query->where('payment_method_used', $request->payment_method_used);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(10);

        return view('admin.financial.payment.index', [
            'payments' => $payments,
            'filters' => $request->only(['payment_method_used', 'status', 'search']),
        ]);
    }
}
