<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Sport;
use App\Models\Market;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    public function getData() {
        $all_data = Market::with(['match' => function($qu) {
            $qu->select('id', 'sport_id', 'name')->with(['sport' => function($qu2) {
                $qu2->select('id', 'name');
            }]);
        }])->get();
         
        $all_sports = Sport::pluck('slug');
        return response()->json(['all_data' => $all_data, 'all_sports' => $all_sports]);
    }

    public function getSportMatchesData($sport_slug) {
        $sport = Sport::whereSlug($sport_slug)->first();
        $all_matches = $sport->matches->pluck('slug');
        return response()->json(['all_matches' => $all_matches]);
    }

    public function addMarket(Request $request) {
        $input = $request->data;
        $validator = Validator::make($input, [
            'match_slug' => ['required'],
            'name' => ['required'],
            'is_bet_block' => ['required'],
            'is_active' => ['required'],
            'odds' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Please re-check the filled data']);
        }
        // Get Match Id
        $input['match_id'] = Match::whereSlug($input['match_slug'])->first()->id;

        $slug = Str::slug($input['name'], '-');

        while (Market::where('slug', $slug)->exists()) {
            $slug = Str::slug($input['name'], '-') . '-' . random_int(1, 99);
        }

        $input['slug'] = $slug;

        $mrk_id = random_int(000000,999999);

        while (Market::where('mrk_id', $mrk_id)->exists()) {
            $mrk_id = random_int(000000,999999);
        }

        $input['mrk_id'] = $mrk_id;

        Market::create($input);

        return response()->json(['success' => 'Data added successfully']);
    }

    public function updateMarket(Request $request)
    {
        $market = Market::find($request->id);
        if ($market != null) {
            $input = $request->data;
            $validator = Validator::make($input, [
                'name' => ['required'],
                //'result' => ['required'],
                'is_bet_block' => ['required'],
                'is_active' => ['required'],
                'odds' => ['required']
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => 'Please re-check the filled data']);
            }
            $market->update($input);
            return response()->json(['success' => "Market updated successfully"]);
        }
    }

    public function updateMarketStatus(Request $request) {
        $type = $request->type;
        $market = Market::find($request->id);
        if ($type == 'bet block') {
            $market->update([
                'is_bet_block' => !$market->is_bet_block
            ]);
        } else if ($type == 'active') {
            $market->update([
                'is_active' => !$market->is_active
            ]);
        }
        return response()->json(['success' => "Status updated successfully"]);
    }
    public function deleteMarket(Request $request)
    {
        $market = Market::findOrFail($request->id);
        if ($market) {
          $market->delete();
          return response()->json(['danger' => 'Market deleted successfully']);
        } else {
          return response()->json(['danger' => 'Market not found']);
        }
      }
}
