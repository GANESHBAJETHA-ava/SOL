<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\Withdrawal;
use App\Models\RewardWithdrawal;
use Illuminate\Support\Facades\Hash;

class WithdrawalController extends Controller
{
    public function Withdrawal(Request $request)
    {   
        $password = $request->password;
        $balanceType = $request->balanceType;
        $auth = Auth::user();
        $creds = [
            'phone' => $auth->phone,
            'password' => $password
        ];

        $user = User::where('phone', $auth->phone)->first();
        $settings = Setting::first();
        if (Auth::attempt($creds)) {
            // CHECK USER BALANCE
            if ($balanceType == "Reward Balance") {
                if ($auth->loss_reward_balance < $settings->reward_threshold) {
                    return response()->json(['error' => 'Please try again later !']);
                }
                if ($auth->loss_reward_balance < $request->amount) {
                    return response()->json(['error' => 'Insufficient reward balance !']);
                }
            } else {
                if ($auth->balance < $request->amount) {
                    return response()->json(['error' => 'Insufficient balance !']);
                }
            }

            if ($request->amount < 300) {
                return response()->json(['error' => 'Min withdrawal amount is 300']);
            }

            $input['amount'] = $request->amount;
            $input['fee'] = $request->fee;
            $input['type'] = $request->type;
            $input['bank_id'] = $request->bank_id;
            $input['user_id'] = $user->id;
            $input['is_rejected'] = 0;
            $input['status'] = 0;

            if ($balanceType == "Reward Balance") {
                RewardWithdrawal::create($input);
                $auth->update([
                    'loss_reward_balance' => $auth->loss_reward_balance - ($request->amount + $request->fee)
                ]);
            } else {
                Withdrawal::create($input);
                $auth->update([
                    'balance' => $auth->balance - ($request->amount + $request->fee)
                ]);
            }
            
            return response()->json(['success' => 'Withdrawal request sent successfully !']); 
        } else {
            return response()->json(['error' => 'Password is incorrect !']);
        }
    }

    public function getWithdrawalRequest($type)
    {
        $auth = Auth::user();
        if ($type == 'Reward Withdrawals') {
            $requests = RewardWithdrawal::where('user_id', $auth->id)->latest('id')->get();
        } else {
            $requests = Withdrawal::where('user_id', $auth->id)->latest('id')->get();
        }
        return response()->json(['requests' => $requests]);
    }
}
