<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Recharge;
use App\Models\Upi;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class RechargeController extends Controller
{
    public function Recharges()
    {   
        $user = Auth::user();
        $recharges = Recharge::where('user_id', $user->id)->latest('id')->get();
        return response()->json(['recharges' => $recharges]);
    }

    public function Recharge(Request $request)
    {
        $account = Upi::where('is_active', 1)->latest('id')->first();
        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $input = $request->all();
        $auth = Auth::user();
        $input['is_cleared'] = 0;
        $input['amount'] = $request->amount;
        $input['type'] = $request->type;       
        $input['to_upi'] = $account->upi_id;
        $input['user_id'] = $auth->id;
        $input['txn_id'] = $txnid;  
        $url = 'upi://pay?pa='.$account->upi_id.'&pn='.$account->name.$txnid.'&tn=PaymentRequest'.'&am='.$input['amount'];
        QrCode::size(300)->generate($url, public_path('images/payment/'. $txnid .'.svg'));

        Recharge::create($input);
        return response()->json(['data' => $input, 'account' => $account ]);
    }

    public function Utr(Request $request)
    {
        // return response()->json($request);
        $recharge = Recharge::where('is_rejected', 0)->where('is_cleared', 0)->where('txn_id', $request->txn_id)->first();
        if ($recharge) {
            $recharge->update(['utr' => $request->utr]);
        }
        return response()->json(['success'=>'Utr submit Successfully.']);
    }
}
