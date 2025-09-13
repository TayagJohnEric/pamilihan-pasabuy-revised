<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
{
        $query = Payment::query();


        // Apply filters if provided
        if ($request->filled('payment_method_used')) {
        $query->where('payment_method_used', $request->payment_method_used);
        }


        if ($request->filled('status')) {
        $query->where('status', $request->status);
        }


        $payments = $query->latest()->paginate(10);


        return view('admin.platform.payment.index', [
        'payments' => $payments,
        'filters' => $request->only(['payment_method_used', 'status']),
        ]);
  }
}
