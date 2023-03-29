<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Sport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MatchController extends Controller
{
    public function getData() {
        $all_data = Match::with('sport')->get();

         //$all_data = Match::with('match', function($qu) {
          //   $qu->with('sport');
        // })->get();
        $all_sports = Sport::pluck('slug');
        return response()->json(['all_data' => $all_data, 'all_sports' => $all_sports]);
    }
    
    public function addMatch(Request $request) {
        $input = $request->data;
        $validator = Validator::make($input, [
            'sport_slug' => ['required'],
            'name' => ['required'],
            'team_one' => ['required'],
            'team_two' => ['required'],
            'match_date' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Please re-check the filled data']);
        }

        // Get Sport Id
        $input['sport_id'] = Sport::whereSlug($input['sport_slug'])->first()->id;

        $slug = Str::slug($input['name'], '-');

        while (Match::where('slug', '=', $slug)->exists()) {
            $slug = Str::slug($input['name'], '-') . '-' . random_int(1, 99);
        }

        $input['match_date'] = Carbon::parse($input['match_date']);

        $input['slug'] = $slug;

        Match::create($input);

        return response()->json(['success' => 'Data added successfully']);
    }

    public function updateMatch(Request $request)
    {
        $match = Match::find($request->id);
        if ($match != null) {
            $input = $request->data;
            $validator = Validator::make($input, [
                'sport_slug' => ['required'],
                'name' => ['required'],
                'team_one' => ['required'],
                'team_two' => ['required'],
                'match_date' => ['required']
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => 'Please re-check the filled data']);
            }

            $input['sport_id'] = Sport::whereSlug($input['sport_slug'])->first()->id;

            $slug = Str::slug($input['name'], '-');

            while (Match::where('slug', '=', $slug)->exists()) {
                $slug = Str::slug($input['name'], '-') . '-' . random_int(1, 99);
            }

            $input['slug'] = $slug;
            $input['match_date'] = Carbon::parse($input['match_date']);

            $match->update($input);
            return response()->json(['success' => "Match updated successfully"]);
        }
    }

    public function updateMatchStatus(Request $request) {
        $type = $request->type;
        $match = Match::find($request->id);
        if ($type == 'bet block') {
            $match->update([
                'is_bet_block' => !$match->is_bet_block
            ]);
        } else if ($type == 'active') {
            $match->update([
                'is_active' => !$match->is_active
            ]);
        }
        return response()->json(['success' => "Status updated successfully"]);
    }

    public function deleteMatch(Request $request)
    {
        $match = Match::findOrFail($request->id);
        if ($match) {
            $match->delete();
            return response()->json(['success' => 'Match deleted successfully']);
        } else {
            return response()->json(['error' => 'Match not found']);
        }
    }
}
