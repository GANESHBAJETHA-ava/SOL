<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Bank;

class BankController extends Controller
{
    public function Banks()
    {   
        $user = Auth::user();
        $banks = Bank::where('user_id', $user->id)->with('user')->get();
        return response()->json(['banks' => $banks]);
    }

    public function Bank(Request $request)
    {
        $input = $request->all();
        $auth = Auth::user();
        $request->validate([
            'actual_name' => ['required'],
            'IFSC' => ['required'], 
            'bank_name' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:banks'],
            'mobile_number' => ['required'],
        ]);
        $input['is_active'] = 1;
        $input['user_id'] = $auth->id;
        Bank::create($input);
        return response()->json(['success' => 'Bank Added  Successfully !']);                  
    }
    
    public function editBank ($id) {   
        $edit_bank = Bank::findOrFail($id);
        return response()->json(['edit_bank' => $edit_bank,]);
    }

    public function updateBank(Request $request, $id)
    {
        $bank = Bank::where('id', '=', $id)->first();
        $request->validate([
            'actual_name' => ['required'],
            'IFSC' => ['required'], 
            'bank_name' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
            'mobile_number' => ['required'],
        ]);
        
        $bank->update($request->all());
        return response()->json(['show' => 'Bank Update  Successfully !']);
    }
    public function deleteBank($id)
    {
        $bank = Bank::findOrFail($id);
        if ($bank) {
          $bank->delete();
          return response()->json(['danger' => 'Bank Account deleted successfully']);
        } else {
          return response()->json(['danger' => 'Bank Account not found']);
        }
      }
}
