<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Address;

class AddressController extends Controller
{
    public function Addresses()
    {   
        $user = Auth::user();
        if ($user != null) {
            $addresses = Address::where('user_id', $user->id)->with('user')->get();
        }
        return response()->json(['addresses' => $addresses]);
    }

    public function Address(Request $request)
    {
        $input = $request->all();
        $auth = Auth::user();
        $request->validate([
            'full_name' => ['required'],
            'mobile_number' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'address_detail' => ['required'],
        ]);
        $input['user_id'] = $auth->id;
        Address::create($input);
        return response()->json(['success' => 'Address Added  Successfully !']);                  
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
}
