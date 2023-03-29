<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Recharge;
use App\Models\Withdrawal;
use App\Models\Bank;
use App\Models\Setting;


class DepositeWithdrawalController extends Controller
{
    public function GetDepositData(Request $request) {
        $itemsPerPage = $request->itemsPerPage;
        if ($request->type == 'pending') {
            $all_deposit_requests = Recharge::with('user')->where('is_cleared', 0)->where('is_rejected', 0)->latest('id')->paginate($itemsPerPage);
        } else if ($request->type == 'success') {
            $all_deposit_requests = Recharge::with('user')->where('is_cleared', 1)->where('is_rejected', 0)->latest('id')->paginate($itemsPerPage);            
        } else if ($request->type == 'rejected') {
            $all_deposit_requests = Recharge::with('user')->where('is_rejected', 1)->latest('id')->paginate($itemsPerPage);
        }
        return response()->json(['all_deposit_requests' => $all_deposit_requests]);
    }

    public function ChangeDepositStatus(Request $request)
    {
        $data = Recharge::where('id', $request->id)->first();
        $user = User::findOrFail($data->user_id);
        $editAmount = $request->editAmount;
        if ($user) {
            if ($data->is_cleared == 0) {
                $first_reward_amount = 0;
                $parent = User::where('id', $user->referred_id)->first();
                $user_recharges = Recharge::where('user_id', $user->id)->where('is_cleared', 1)->get();
                if ($parent != null && count($user_recharges) == 0) {
                    $settings = Setting::first();
                    $first_reward_per = $settings->first_reward;
                    $first_reward_amount = $editAmount * $first_reward_per / 100;
                    $parent->update([
                        'commission' => $parent->commission + $first_reward_amount,
                        'balance' => $parent->balance + $first_reward_amount
                    ]);
                }
                
                $user_balance = $user->balance + $editAmount;
                $user->update([
                    'balance' => $user_balance,
                    'level_1_first_reward' => ($user->level_1_water_reward + $first_reward_amount)
                ]);

                $data->update(['is_cleared' => 1, 'amount' => $editAmount]);

            } elseif ($data->is_cleared == 1) {
                $data->update(['is_cleared' => 0]);
                $user_balance = $user->balance - $editAmount;
                $user->update([
                    'balance' => $user_balance
                ]);
            }
        }
        return response()->json(['success'=>'Deposit Status is change Successful.']);
    }

    public function DepositRejected(Request $request)
    {
        $data = Recharge::where('id', $request->id)->first();
        $user = User::findOrFail($data->user_id);

        if ($user) {
            if ($data->is_rejected == 0 ) {
                $data->update(['is_rejected' => 1]);
            }
        }

        return response()->json(['success' =>' Deposit Rejected is change Successful.']);
    }

    public function GetWithdrawalData(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        if ($request->type == 'pending') {
            $all_withdrawal_requests = Withdrawal::with('user', 'bank')->where('status', 0)->where('is_rejected', 0)->latest('id')->paginate($itemsPerPage);            
        } else if ($request->type == 'success') {
            $all_withdrawal_requests = Withdrawal::with('user', 'bank')->where('status', 1)->where('is_rejected', 0)->latest('id')->paginate($itemsPerPage);
        } else if ($request->type == 'rejected') {
            $all_withdrawal_requests = Withdrawal::with('user', 'bank')->where('is_rejected', 1)->latest('id')->paginate($itemsPerPage);
        }
        return response()->json(['all_withdrawal_requests' => $all_withdrawal_requests]);
    }

    public function ChangeWithdrawalStatus(Request $request)
    {
        $data = Withdrawal::where('id', $request->id)->first();
        $user = User::findOrFail($data->user_id);

        if ($user) {
            if ($data->status == 0 ) {
                $data->update(['status' => 1]);
                // $user_balance = $user->balance - $data->amount;
                // $user->update([
                //     'balance' => $user_balance
                // ]);
            } elseif($data->status == 1) {
                $data->update(['status' => 0]);
                // $user_balance = $user->balance + $data->amount;
                // $user->update([
                //     'balance' => $user_balance
                // ]);
            }
        }
        return response()->json(['success'=>'Withdrawal Request change Successful.']);
    }

    public function WithdrawalRejected(Request $request)
    {
        $data = Withdrawal::where('id', $request->id)->first();
        $user = User::findOrFail($data->user_id);

        if ($user) {
            if ($data->is_rejected == 0 ) {
                $data->update(['is_rejected' => 1]);
                $user->update([
                    'balance' => ($user->balance + $data->amount)
                ]);
            }
        }
        return response()->json(['success'=>'Withdrawal Request Rejected Successful.']);
    }
}
