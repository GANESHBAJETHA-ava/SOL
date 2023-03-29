<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\RewardWithdrawal;
use App\Models\Bank;

class RewardWithdrawalController extends Controller
{
    public function GetRewardWithdrawalData(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        if ($request->type == 'pending') {
            $all_reward_withdrawal_requests = RewardWithdrawal::with('user', 'bank')->where('status', 0)->where('is_rejected', 0)->latest('id')->paginate($itemsPerPage);            
        } else if ($request->type == 'success') {
            $all_reward_withdrawal_requests = RewardWithdrawal::with('user', 'bank')->where('status', 1)->where('is_rejected', 0)->latest('id')->paginate($itemsPerPage);
        } else if ($request->type == 'rejected') {
            $all_reward_withdrawal_requests = RewardWithdrawal::with('user', 'bank')->where('is_rejected', 1)->latest('id')->paginate($itemsPerPage);            
        }
        return response()->json(['all_reward_withdrawal_requests' => $all_reward_withdrawal_requests]);        
    }

    public function ChangeRewardWithdrawalStatus(Request $request)
    {
        $data = RewardWithdrawal::where('id', $request->id)->first();
        $user = User::findOrFail($data->user_id);

        if ($user) {
            if ($data->status == 0 ) {
                $data->update(['status' => 1]);
                $user_balance = $user->balance - $data->amount;
                $user->update([
                    'balance' => $user_balance
                ]);
            } elseif($data->status == 1) {
                $data->update(['status' => 0]);
                $user_balance = $user->balance + $data->amount;
                $user->update([
                    'balance' => $user_balance
                ]);
            }
        }
        return response()->json(['success'=>'Reward Withdrawal Request change Successful.']);
    }

    public function RewardWithdrawalRejected(Request $request)
    {
        $data = RewardWithdrawal::where('id', $request->id)->first();
        $user = User::findOrFail($data->user_id);

        if ($user) {
            if ($data->is_rejected == 0 ) {
                $data->update(['is_rejected' => 1]);
            }
        }
        return response()->json(['success'=>'Reward Withdrawal Request Rejected Successful.']);
    }
}
