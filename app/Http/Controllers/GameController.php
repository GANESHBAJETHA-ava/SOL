<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Period;
use App\Models\Order;
use App\Models\User;
use App\Models\Setting;
use App\Models\DummyWin;
use Carbon\Carbon;
use Auth;

class GameController extends Controller
{
    public function getGame($game_slug)
    {
        $game = Game::whereSlug($game_slug)->first();
        $period = Period::where('game_id', $game->id)->where('is_cleared', 0)->latest('id')->first();
        $users_win_records = DummyWin::where('is_active', 1)->get();
        $users_win_records_data = collect();
        foreach ($users_win_records as $value) {
            for ($i=0; $i < 15; $i++) {
                $number = rand($value->from_number, $value->to_number) . 'xxxxx';
                $amount = rand($value->from_amount, $value->to_amount);
                $data = (object) [
                    'number' => $number,
                    'amount' => substr_replace((string) $amount,"0",-1),
                    'text' => str_replace('#', $amount, $value->text)
                ];
                $users_win_records_data->push($data);
            }
        }
        return response()->json(['game' => $game, 'period' => $period, 'users_win_records_data' => $users_win_records_data]);
    }

    public function getGameRecords(Request $request, $game_slug)
    {
        $itemsPerPage = $request->itemsPerPage;        
        $auth = Auth::user();
        $game = Game::whereSlug($game_slug)->first();
        $periods = Period::where('game_id', $game->id)->where('is_cleared', 1)->latest('id')->get(["is_cleared", "period", "price_money", "result"]);
        $my_orders = Order::where('user_id', $auth->id)->where('game_id', $game->id)->with('period')->latest('id')->paginate($itemsPerPage);
        return response()->json(['periods' => $periods, 'my_orders' => $my_orders]);
    }

    public function getMyGameOrders($game_slug)
    {
        $auth = Auth::user();
        $game = Game::whereSlug($game_slug)->first();
        $my_orders = Order::where('user_id', $auth->id)->where('game_id', $game->id)->with('period')->latest('id')->get();
        return response()->json(['my_orders' => $my_orders]);
    }

    public function submitNewOrder(Request $request)
    {
        $auth = Auth::user();
        $game_id = $request->game_id;
        $period_id = $request->period_id;
        $order = $request->order;
        $game = Game::find($game_id);
        $period = Period::find($period_id);
        $settings = Setting::first();
        if ($game != null && $period != null) {
            $now = Carbon::now();
            if ($game->slug == 'coin-flip') {
                $period_time = Carbon::parse($period->created_at)->addSeconds($game->time - 5);
            } else {
                $period_time = Carbon::parse($period->created_at)->addSeconds($game->time - 30);                
            }
            if ($period_time->gt($now)) {
                // BALANCE CHECK
                $total_order_money = $order['contract_money'] * $order['contract_count'];
                if ($auth->balance < $total_order_money) {
                    return response()->json(['error' => 'Insufficeint balance']);
                }
                // CREATE ORDER
                $order_fee_per = $settings->order_fee;
                $fee = ($total_order_money * $order_fee_per / 100);
                $delivery = $total_order_money - $fee;
                Order::create([
                    'user_id' => $auth->id,
                    'game_id' => $game_id,
                    'period_id' => $period_id,
                    'selection' => $order['selection'],
                    'contract_money' => $order['contract_money'],
                    'contract_count' => $order['contract_count'],
                    'delivery' => $delivery,
                    'fee' => $fee
                ]);
                // PARENTS
                $level_one_amount = 0;
                $level_two_amount = 0;
                $level_three_amount = 0;
                $parent = User::where('id', $auth->referred_id)->first();
                if ($parent != null) {
                    // COMMISSION WORK
                    $level_one_percent = $settings->level_1_reward;
                    $level_two_percent = $settings->level_2_reward;
                    $level_three_percent = $settings->level_3_reward;
                    // LEVEL ONE
                    $level_one_amount = $fee * $level_one_percent / 100;
                    $parent->update([
                        'commission' => $parent->commission + $level_one_amount,
                        'balance' => $parent->balance + $level_one_amount
                    ]);
                    // LEVEL TWO
                    $level_two_parent = User::where('id', $parent->referred_id)->first();
                    if ($level_two_parent != null) {
                        $level_two_amount = $fee * $level_two_percent / 100;
                        $level_two_parent->update([
                            'commission' => $level_two_parent->commission + $level_two_amount,
                            'balance' => $level_two_parent->balance + $level_two_amount
                        ]);
                        // LEVEL THREE
                        $level_three_parent = User::where('id', $level_two_parent->referred_id)->first();
                        if ($level_three_parent != null) {
                            $level_three_amount = $fee * $level_three_percent / 100;
                            $level_three_parent->update([
                                'commission' => $level_three_parent->commission + $level_three_amount,
                                'balance' => $level_three_parent->balance + $level_three_amount
                            ]);
                        }
                    }
                }

                $auth->update([
                    'balance' => ($auth->balance - $total_order_money),
                    'contribution' => ($auth->contribution + $total_order_money),
                    'level_1_water_reward' => $auth->level_1_water_reward + $level_one_amount,
                    'level_2_water_reward' => $auth->level_2_water_reward + $level_two_amount,
                    'level_3_water_reward' => $auth->level_3_water_reward + $level_three_amount
                ]);

                return response()->json(['success' => 'Order has been submited successfully']);
            } else {
                return response()->json(['error' => 'Period timout']);
            }
        } else {
            return response()->json(['error' => 'Game or period invalid']);
        }
    }
}
