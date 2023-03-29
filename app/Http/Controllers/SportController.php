<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sport;
use Illuminate\Support\Str;

/**
 * Summary of SportController
 */
class SportController extends Controller
{

    public function getData() {
        $all_data = Sport::all();
        return response()->json(['all_data' => $all_data]);
    }

    public function addSport(Request $request) {
        $input = $request->data;
        $validator = Validator::make($input, [
            'name' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Please re-check the filled data']);
        }

        $slug = Str::slug($input['name'], '-');


        while (Sport::where('slug', '=', $slug)->exists()) {
            $slug = Str::slug($input['name'], '-') . '-' . random_int(1, 99);
        }

        $input['slug'] = $slug;

        Sport::create($input);

        return response()->json(['success' => 'Data added successfully']);
    }

    public function updateSport(Request $request)
    {
        $sport = Sport::find($request->id);
        if ($sport != null) {
            $input = $request->data;
            $validator = Validator::make($input, [
                'name' => ['required']
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => 'Please re-check the filled data']);
            }

            $slug = Str::slug($input['name'], '-');


        while (Sport::where('slug', '=', $slug)->exists()) {
            $slug = Str::slug($input['name'], '-') . '-' . random_int(1, 99);
        }

        $input['slug'] = $slug;
            $sport->update($input);
            return response()->json(['success' => "Sport updated successfully"]);
        }
    }

    public function updateSportsStatus(Request $request) {
        $type = $request->type;
        $match = Sport::find($request->id);
         if ($type == 'active') {
            $match->update([
                'is_active' => !$match->is_active
            ]);
        }
        return response()->json(['success' => "Status updated successfully"]);
    }

    public function deleteSport(Request $request)
    {
        $sport = Sport::findOrFail($request->id);
        if ($sport) {
          $sport->delete();
          return response()->json(['danger' => 'Sport deleted successfully']);
        } else {
          return response()->json(['danger' => 'Sport not found']);
        }
      }
}
