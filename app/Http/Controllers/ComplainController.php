<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Complain;

class ComplainController extends Controller
{
    public function Complains()
    {   
        $user = Auth::user();
        if ($user != null) {
            $complains = Complain::where('user_id', $user->id)->with('user')->get();
        }
        return response()->json(['complains' => $complains]);
    }

    public function Complain(Request $request)
    {
        $input = $request->all();
        $auth = Auth::user();
        $request->validate([
            'type' => ['required'],
            'whatsapp' => ['required'],
        ]);
        $input['user_id'] = $auth->id;
        $input['des'] = $request->des;
        $input['reply'] = null;


        Complain::create($input);
        return response()->json(['success' => 'Complain Added  Successfully !']);                  
    }
    public function editAddress ($id) {   
        $edit_address = Address::findOrFail($id);
        return response()->json(['edit_address' => $edit_address,]);
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Address::where('id', '=', $id)->first();
        $request->validate([
            'full_name' => ['required'],
            'mobile_number' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'address_detail' => ['required'],
        ]);
        
        $address->update($request->all());
        return response()->json(['show' => 'Address Update  Successfully !']);
    }
    public function deleteAddress($id)
    {
        $address = Address::findOrFail($id);
        if ($address) {
          $address->delete();
          return response()->json(['danger' => 'Address deleted successfully']);
        } else {
          return response()->json(['danger' => 'Address not found']);
        }
      }
    public function CustomerComplains()
    {
        $complains = Complain::with('user')->get(); 
        return response()->json(['complains' => $complains]);
    }
    public function ComplainReply(Request $request)
    {
        $id = $request->id;
        $reply = $request->reply;
        $complain_reply = Complain::where('id', $id)->where('reply', null)->first();
        if ($complain_reply != null) {
            $complain_reply->update([
                'reply' => $reply,
            ]);
            return response()->json(['success' => 'Complain reply successfully']);
        } else {
            return response()->json(['error' => 'Complain already replied']);
        }
    }
}

