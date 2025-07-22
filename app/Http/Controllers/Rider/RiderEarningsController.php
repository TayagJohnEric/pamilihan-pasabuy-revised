<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rider;
use App\Models\RiderPayout;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiderEarningsController extends Controller
{
     public function earnings(Request $request)
    {
        $rider = Auth::user();
        $currentMonth = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $currentMonthEarnings = Order::where('rider_user_id', $rider->id)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$currentMonth, $currentMonthEnd])
            ->sum('delivery_fee');

        $currentMonthDeliveries = Order::where('rider_user_id', $rider->id)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$currentMonth, $currentMonthEnd])
            ->count();

        $incentiveRules = SystemSetting::where('setting_key', 'like', 'rider_incentive_%')->get();
        $currentMonthIncentives = $this->calculateIncentives($rider->id, $currentMonthDeliveries, $incentiveRules);

        $totalEarnings = Order::where('rider_user_id', $rider->id)
            ->where('status', 'delivered')
            ->sum('delivery_fee');

        $recentPayouts = RiderPayout::where('rider_user_id', $rider->id)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $pendingPayout = RiderPayout::where('rider_user_id', $rider->id)
            ->whereIn('status', ['pending_calculation', 'pending_payment'])
            ->first();

        $dailyEarnings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $earnings = Order::where('rider_user_id', $rider->id)
                ->where('status', 'delivered')
                ->whereDate('created_at', $date)
                ->sum('delivery_fee');

            $dailyEarnings[] = [
                'date' => $date->format('M j'),
                'earnings' => $earnings
            ];
        }

        $riderStats = $rider->rider;

        return view('rider.earnings.earnings', compact(
            'currentMonthEarnings',
            'currentMonthDeliveries',
            'currentMonthIncentives',
            'totalEarnings',
            'recentPayouts',
            'pendingPayout',
            'dailyEarnings',
            'riderStats'
        ));
    }

    public function payouts(Request $request)
    {
        $rider = Auth::user();
        $status = $request->get('status', 'all');
        $period = $request->get('period', 'all');

        $query = RiderPayout::where('rider_user_id', $rider->id)->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($period !== 'all') {
            switch ($period) {
                case 'current_month':
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth()
                    ]);
                    break;
                case 'last_month':
                    $query->whereBetween('created_at', [
                        Carbon::now()->subMonth()->startOfMonth(),
                        Carbon::now()->subMonth()->endOfMonth()
                    ]);
                    break;
                case 'last_3_months':
                    $query->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;
            }
        }

        $payouts = $query->paginate(10);

        $totalPaid = RiderPayout::where('rider_user_id', $rider->id)
            ->where('status', 'paid')
            ->sum('total_payout_amount');

        $totalPending = RiderPayout::where('rider_user_id', $rider->id)
            ->whereIn('status', ['pending_calculation', 'pending_payment'])
            ->sum('total_payout_amount');

        return view('rider.earnings.payouts', compact(
            'payouts',
            'totalPaid',
            'totalPending',
            'status',
            'period'
        ));
    }

    protected function calculateIncentives($riderId, $deliveryCount, $incentiveRules)
    {
        $totalIncentives = 0;

        foreach ($incentiveRules as $rule) {
            $ruleData = json_decode($rule->setting_value, true);

            if (!$ruleData) continue;

            if (isset($ruleData['min_deliveries']) && $deliveryCount >= $ruleData['min_deliveries']) {
                $totalIncentives += $ruleData['bonus_amount'] ?? 0;
            }
        }

        return $totalIncentives;
    }
}
