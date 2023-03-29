<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recharge;
use App\Models\Withdrawal;
use App\Models\Order;
use App\Models\User;
use App\Models\RewardWithdrawal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $depositCount = Recharge::where('is_cleared', 0)->where('is_rejected', 0)->count();
        $withdrawalCount = Withdrawal::where('status', 0)->where('is_rejected', 0)->count();
        $data = (object) [
            'total_members' => 0,
            'active_members' => 0,
            'inactive_members' => 0,
            'total_orders' => 0,
            'total_deposits' => 0,
            'total_withdrawals' => 0,
        ];

        $today = Carbon::today();

        $data->total_members = User::where('is_admin', 0)->count();
        $data->active_members = 0;
        $data->inactive_members = 0;
        $data->total_orders = Order::where('is_cleared', 1)->count();
        $data->total_deposits = Recharge::where('is_cleared', 1)->sum('amount');
        $data->total_withdrawals = Withdrawal::where('status', 1)->sum('amount');
        $data->today_pl = (Recharge::where('is_cleared', 1)->whereDate('created_at', $today)->sum('amount') - (Withdrawal::where('status', 1)->whereDate('created_at', $today)->sum('amount') + RewardWithdrawal::where('status', 1)->whereDate('created_at', $today)->sum('amount')));
        $data->total_pl = $data->total_deposits - ($data->total_withdrawals + RewardWithdrawal::where('status', 1)->whereDate('created_at', $today)->sum('amount'));

        return response()->json(['depositCount' => $depositCount, 'withdrawalCount' => $withdrawalCount, 'data' => $data]);
    }
}
