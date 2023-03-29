<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\User;

class GeneralController extends Controller
{
    public function getMyLevels()
    {
    	$auth = Auth::user();
    	$one_levels = User::where('referred_id', $auth->id)->get(["level_1_first_reward AS first_reward", "level_1_water_reward AS water_reward", "id", "phone", "contribution"]);
    	$two_levels = User::whereIn('referred_id', $one_levels->pluck('id')->toArray())->get(["level_2_first_reward AS first_reward", "level_2_water_reward AS water_reward", "id", "phone", "contribution"]);
    	$three_levels = User::whereIn('referred_id', $two_levels->pluck('id')->toArray())->get(["level_3_first_reward AS first_reward", "level_3_water_reward AS water_reward", "id", "phone", "contribution"]);
    	$total_people = $one_levels->count() + $two_levels->count() + $three_levels->count();
    	$contribution = $one_levels->sum('contribution') + $two_levels->sum('contribution') + $three_levels->sum('contribution');
    	return response()->json(['one_levels' => $one_levels, 'two_levels' => $two_levels, 'three_levels' => $three_levels, 'total_people' => $total_people, 'contribution' => $contribution]);
    }
}
