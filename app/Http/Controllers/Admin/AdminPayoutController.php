<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiderPayout;
use App\Models\VendorPayout;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminPayoutController extends Controller
{
    /**
     * Display the rider payouts list
     * Shows all rider payout records with filtering and pagination
     */
    public function riderPayouts(Request $request)
    {
        $query = RiderPayout::with(['rider.user'])
            ->latest('created_at');

        // Apply status filter if provided
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply date range filter if provided
        if ($request->filled('date_from')) {
            $query->where('payout_period_start_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('payout_period_end_date', '<=', $request->date_to);
        }

        $payouts = $query->paginate(15);

        // Get status counts for filter tabs
        $statusCounts = [
            'all' => RiderPayout::count(),
            'pending_payment' => RiderPayout::where('status', 'pending_payment')->count(),
            'paid' => RiderPayout::where('status', 'paid')->count(),
            'failed' => RiderPayout::where('status', 'failed')->count(),
        ];

        return view('admin.financial.rider-payout.index', compact('payouts', 'statusCounts'));
    }

    /**
     * Display the vendor payouts list
     * Shows all vendor payout records with filtering and pagination
     */
    public function vendorPayouts(Request $request)
    {
        $query = VendorPayout::with(['vendor.user'])
            ->latest('created_at');

        // Apply status filter if provided
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Apply date range filter if provided
        if ($request->filled('date_from')) {
            $query->where('payout_period_start_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('payout_period_end_date', '<=', $request->date_to);
        }

        $payouts = $query->paginate(15);

        // Get status counts for filter tabs
        $statusCounts = [
            'all' => VendorPayout::count(),
            'pending_payment' => VendorPayout::where('status', 'pending_payment')->count(),
            'paid' => VendorPayout::where('status', 'paid')->count(),
            'failed' => VendorPayout::where('status', 'failed')->count(),
        ];

        return view('admin.financial.vendor-payout.index', compact('payouts', 'statusCounts'));
    }

    /**
     * Show detailed view of a specific rider payout
     * Displays all payout information and allows for editing
     */
    public function showRiderPayout($id)
    {
        $payout = RiderPayout::with(['rider.user'])
            ->findOrFail($id);

        return view('admin.financial.rider-payout.show', compact('payout'));
    }

    /**
     * Show detailed view of a specific vendor payout
     * Displays all payout information and allows for editing
     */
    public function showVendorPayout($id)
    {
        $payout = VendorPayout::with(['vendor.user'])
            ->findOrFail($id);

        return view('admin.financial.vendor-payout.show', compact('payout'));
    }

    /**
     * Update rider payout with manual adjustments
     * Handles adjustment amounts, notes, and recalculates total payout
     */
    public function updateRiderPayout(Request $request, $id)
    {
        $request->validate([
            'adjustments_amount' => 'nullable|numeric|between:-999999.99,999999.99',
            'adjustments_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $payout = RiderPayout::findOrFail($id);

            // Update adjustment fields
            $adjustmentsAmount = $request->adjustments_amount ?? 0;
            $payout->update([
                'adjustments_amount' => $adjustmentsAmount,
                'adjustments_notes' => $request->adjustments_notes,
                'total_payout_amount' => $payout->total_delivery_fees_earned + 
                                       $payout->total_incentives_earned + 
                                       $adjustmentsAmount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rider payout updated successfully',
                'total_payout_amount' => number_format($payout->total_payout_amount, 2),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating rider payout: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payout. Please try again.',
            ], 500);
        }
    }

    /**
     * Update vendor payout with manual adjustments
     * Handles adjustment amounts, notes, and recalculates total payout
     */
    public function updateVendorPayout(Request $request, $id)
    {
        $request->validate([
            'adjustments_amount' => 'nullable|numeric|between:-999999.99,999999.99',
            'adjustments_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $payout = VendorPayout::findOrFail($id);

            // Update adjustment fields
            $adjustmentsAmount = $request->adjustments_amount ?? 0;
            $netSalesAmount = $payout->total_sales_amount - $payout->platform_commission_amount;
            
            $payout->update([
                'adjustments_amount' => $adjustmentsAmount,
                'adjustments_notes' => $request->adjustments_notes,
                'total_payout_amount' => $netSalesAmount + $adjustmentsAmount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor payout updated successfully',
                'total_payout_amount' => number_format($payout->total_payout_amount, 2),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating vendor payout: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payout. Please try again.',
            ], 500);
        }
    }

    /**
     * Mark rider payout as paid and add transaction reference
     * Updates status, paid_at timestamp, and sends notification
     */
    public function markRiderPayoutAsPaid(Request $request, $id)
    {
        $request->validate([
            'transaction_reference' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $payout = RiderPayout::with(['rider.user'])->findOrFail($id);

            // Update payout status to paid
            $payout->update([
                'status' => 'paid',
                'transaction_reference' => $request->transaction_reference,
                'paid_at' => now(),
            ]);

            // Send notification to rider
            $this->createPayoutNotification(
                $payout->rider_user_id,
                'rider_payout_paid',
                'Payout Completed',
                [
                    'payout_id' => $payout->id,
                    'amount' => $payout->total_payout_amount,
                    'period' => $payout->payout_period_start_date->format('M d') . ' - ' . $payout->payout_period_end_date->format('M d, Y'),
                    'transaction_reference' => $payout->transaction_reference,
                    'message' => 'Your payout has been processed successfully.',
                ],
                RiderPayout::class,
                $payout->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rider payout marked as paid successfully',
                'status' => 'paid',
                'paid_at' => $payout->paid_at->format('M d, Y g:i A'),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error marking rider payout as paid: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payout. Please try again.',
            ], 500);
        }
    }

    /**
     * Mark vendor payout as paid and add transaction reference
     * Updates status, paid_at timestamp, and sends notification
     */
    public function markVendorPayoutAsPaid(Request $request, $id)
    {
        $request->validate([
            'transaction_reference' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $payout = VendorPayout::with(['vendor.user'])->findOrFail($id);

            // Update payout status to paid
            $payout->update([
                'status' => 'paid',
                'transaction_reference' => $request->transaction_reference,
                'paid_at' => now(),
            ]);

            // Send notification to vendor
            $this->createPayoutNotification(
                $payout->vendor_user_id,
                'vendor_payout_paid',
                'Payout Completed',
                [
                    'payout_id' => $payout->id,
                    'amount' => $payout->total_payout_amount,
                    'period' => $payout->payout_period_start_date->format('M d') . ' - ' . $payout->payout_period_end_date->format('M d, Y'),
                    'transaction_reference' => $payout->transaction_reference,
                    'message' => 'Your payout has been processed successfully.',
                ],
                VendorPayout::class,
                $payout->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor payout marked as paid successfully',
                'status' => 'paid',
                'paid_at' => $payout->paid_at->format('M d, Y g:i A'),
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error marking vendor payout as paid: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payout. Please try again.',
            ], 500);
        }
    }

    /**
     * Mark rider payout as failed
     * Updates status and optionally adds notes
     */
    public function markRiderPayoutAsFailed(Request $request, $id)
    {
        $request->validate([
            'failure_reason' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $payout = RiderPayout::with(['rider.user'])->findOrFail($id);

            // Update payout status to failed
            $payout->update([
                'status' => 'failed',
                'adjustments_notes' => $request->failure_reason ? 
                    ($payout->adjustments_notes ? $payout->adjustments_notes . "\n\nFailure Reason: " . $request->failure_reason : 'Failure Reason: ' . $request->failure_reason) : 
                    $payout->adjustments_notes,
            ]);

            // Send notification to rider about failed payout
            $this->createPayoutNotification(
                $payout->rider_user_id,
                'rider_payout_failed',
                'Payout Processing Failed',
                [
                    'payout_id' => $payout->id,
                    'amount' => $payout->total_payout_amount,
                    'period' => $payout->payout_period_start_date->format('M d') . ' - ' . $payout->payout_period_end_date->format('M d, Y'),
                    'message' => 'There was an issue processing your payout. Please contact support.',
                ],
                RiderPayout::class,
                $payout->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rider payout marked as failed',
                'status' => 'failed',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error marking rider payout as failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payout status. Please try again.',
            ], 500);
        }
    }

    /**
     * Mark vendor payout as failed
     * Updates status and optionally adds notes
     */
    public function markVendorPayoutAsFailed(Request $request, $id)
    {
        $request->validate([
            'failure_reason' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $payout = VendorPayout::with(['vendor.user'])->findOrFail($id);

            // Update payout status to failed
            $payout->update([
                'status' => 'failed',
                'adjustments_notes' => $request->failure_reason ? 
                    ($payout->adjustments_notes ? $payout->adjustments_notes . "\n\nFailure Reason: " . $request->failure_reason : 'Failure Reason: ' . $request->failure_reason) : 
                    $payout->adjustments_notes,
            ]);

            // Send notification to vendor about failed payout
            $this->createPayoutNotification(
                $payout->vendor_user_id,
                'vendor_payout_failed',
                'Payout Processing Failed',
                [
                    'payout_id' => $payout->id,
                    'amount' => $payout->total_payout_amount,
                    'period' => $payout->payout_period_start_date->format('M d') . ' - ' . $payout->payout_period_end_date->format('M d, Y'),
                    'message' => 'There was an issue processing your payout. Please contact support.',
                ],
                VendorPayout::class,
                $payout->id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vendor payout marked as failed',
                'status' => 'failed',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error marking vendor payout as failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payout status. Please try again.',
            ], 500);
        }
    }

    /**
     * Create a payout-related notification
     * Follows the same pattern as your existing notification system
     */
    private function createPayoutNotification($userId, $type, $title, $message, $entityType = null, $entityId = null)
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
