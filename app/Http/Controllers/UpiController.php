<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Upi;

class UpiController extends Controller
{
    public function upis()
    {
        $auth = Auth::user();
        if ($auth->is_admin == 1) {
            $upis = Upi::get();
            return response()->json(['upis' => $upis]);
        }
    }
    public function Upi(Request $request)
    {
        $input = $request->all();
        $auth = Auth::user();
        if ($auth->is_admin == 1) {
            $input['name'] = $request->name;
            $input['type'] = $request->type;
            $input['upi_id'] = $request->upi_id;
            $input['is_active'] = 0;
        }
        Upi::create($input);
        return response()->json(['success' => 'Upi Create Successfully !']);
    }
    public function changeUpiStatus(Request $request)
    {
        $data = Upi::where('id', $request->id)->first();
        if ($data != null) {
            if ($data->is_active == 1) {
                $data->update(['is_active' => 0]);
          } else {
                $data->update(['is_active' => 1]);
          }
            return response()->json(['show' => 'Status  changed Successfully.']);
        }
        return response()->json(['error' => 'Status change have an error!!']);
    }
    public function deleteUpi($id)
    {
        $upi = Upi::findOrFail($id);
        if ($upi) {
            $upi->delete();
            return response()->json(['danger' => 'Upi deleted successfully']);
        } else {
            return response()->json(['danger' => 'Upi not found']);
        }
    }
}
