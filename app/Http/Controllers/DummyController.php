<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\DummyWin;

class DummyController extends Controller
{
    public function getDummyWinsData()
    {
        $data = DummyWin::latest('id')->get();
        return response()->json($data);
    }

    public function addDummyWins(Request $request)
    {
        $input = $request->data;
        $validator = Validator::make($input, [
            'from_number' => ['required'],
            'to_number' => ['required'],
            'from_amount' => ['required'],
            'to_amount' => ['required'],
            'text' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Please re-check the filled data']);
        }
        DummyWin::create($input);
        return response()->json(['success' => "Dummy win added successfully"]);
    }

    public function updateDummyWins(Request $request)
    {
        $dummy_win = DummyWin::find($request->id);
        if ($dummy_win != null) {
            $input = $request->data;
            $validator = Validator::make($input, [
                'from_number' => ['required'],
                'to_number' => ['required'],
                'from_amount' => ['required'],
                'to_amount' => ['required'],
                'text' => ['required']
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => 'Please re-check the filled data']);
            }
            $dummy_win->update($input);
            return response()->json(['success' => "Dummy win updated successfully"]);
        }
    }

    public function updateDummyWinsStatus(Request $request)
    {
        $dummy_wins = DummyWin::find($request->id);
        $dummy_wins->update([
            'is_active' => !$dummy_wins->is_active
        ]);
        return response()->json(['success' => "Status updated successfully"]);
    }

    public function deleteDummyWinsStatus(Request $request)
    {
        $dummy_wins = DummyWin::find($request->id);
        $dummy_wins->delete();
        return response()->json(['success' => "Dummy Win deleted successfully"]);
    }
}
