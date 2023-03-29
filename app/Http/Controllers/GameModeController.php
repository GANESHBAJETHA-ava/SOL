<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Period;
use App\Models\Order;
use App\Models\User;
use App\Models\Recharge;
use App\Models\Setting;
use Carbon\Carbon;
use App\Events\GameResult;

class GameModeController extends Controller
{
    public function getAllGames()
    {
        $all_games = Game::where('is_active', 1)->get();
        return response()->json(['all_games' => $all_games]);
    }

    public function getGameCurrentPeriod($game_slug)
    {
        $game = Game::whereSlug($game_slug)->first();
        $currentPeriod = Period::where('game_id', $game->id)->where('is_cleared', 0)->latest('id')->first();
        return response()->json(['currentPeriod' => $currentPeriod]);
    }

    public function updatePeriodMode(Request $request)
    {
        $periodModeManual = $request->periodModeManual;
        $currentPeriod = Period::where('id', $request->period_id)->where('is_cleared', 0)->latest('id')->first();
        $currentPeriod->update([
            'is_manual' => $periodModeManual
        ]);
        return;
    }

    public function submitGameResult(Request $request)
    {
        $result = $request->result;
        $period = Period::where('id', $request->period_id)->where('is_cleared', 0)->latest('id')->first();
        $game = Game::where('id', $period->game_id)->first();

        if ($period->is_manual == false) {
            return response()->json(['error' => "Please switch mode to manual first"]);
        }

        // CHECK ROUND END
        $now = Carbon::now();
        $period_time = Carbon::parse($period->created_at)->addSeconds($game->time);
        if ($now->gt($period_time) == false) {
            return response()->json(['error' => "Please wait for round to end"]);
        }

        if ($game->slug == 'win') {
            // GET ODDS
            $odds_multiplication = (object) [
                'green_red' => 2,
                'green_red_half' => 1.5,
                'voilet' => 4.5,
                'number' => 9
            ];
            // RESULT
            if ($result == 'jackpot') {
                $price_money = 'jackpot';
                $period->update([
                    'result' => $result,
                    'price_money' => $price_money,
                    'is_cleared' => 1
                ]);
            } else {
                $price_money = rand(88400, 89000);
                $price_money = substr_replace((string) $price_money, (string) $result, -1);
                $period->update([
                    'result' => $result,
                    'price_money' => $price_money,
                    'is_cleared' => 1
                ]);
            }
            // USERS PL
            $orders = Order::where('period_id', $period->id)->where('is_cleared', 0)->get();
            $all_users = User::whereIn('id', $orders->pluck('user_id')->toArray())->get();
            $jackpot_odds = Setting::first()->jackpot_odds;
            foreach ($all_users as $user) {
                $user_orders = $orders->where('user_id', $user->id);
                foreach ($user_orders as $value) {
                    $delivery = $value->delivery;
                    $pl = 0;
                    if ($value->selection == 'king' || $value->selection == 'queen' || $value->selection == 'joker') {
                        if ($value->selection == 'king') {
                            if ($result == '1' || $result == '3' || $result == '7' || $result == '9') {
                                $pl = ($delivery * $odds_multiplication->green_red);
                            } else if ($result == '5') {
                                $pl = ($delivery * $odds_multiplication->green_red_half);
                            } else {
                                $pl = -($delivery);
                            }
                        } else if ($value->selection == 'queen') {
                            if ($result == '2' || $result == '4' || $result == '6' || $result == '8') {
                                $pl = ($delivery * $odds_multiplication->green_red);
                            } else if ($result == '0') {
                                $pl = ($delivery * $odds_multiplication->green_red_half);
                            } else {
                                $pl = -($delivery);
                            }
                        } else if ($value->selection == 'joker') {
                            if ($result == '0' || $result == '5') {
                                $pl = ($delivery * $odds_multiplication->voilet);
                            } else {
                                $pl = -($delivery);
                            }
                        }
                    } else {
                        if ($value->selection == $result && $result == 'jackpot') {
                            $pl = ($delivery * $jackpot_odds);
                        } else if ($value->selection == $result) {
                            $pl = ($delivery * $odds_multiplication->number);
                        } else {
                            $pl = -($delivery);
                        }
                    }
                    if ($pl > 0) {
                        $user->update([
                            'balance' => ($user->balance + $pl)
                        ]);
                    } else if ($pl < 0) {
                        $user_recharges_amount = Recharge::where('user_id', $user->id)->where('is_cleared', 1)->where('is_rejected', 0)->sum('amount');
                        $reward_amount = ($pl * -1);
                        if ($user->loss_reward_balance < $user_recharges_amount) {
                            if (floatval($user->loss_reward_balance + $reward_amount) > floatval($user_recharges_amount)) {
                                $reward_amount = $user_recharges_amount - $user->loss_reward_balance;
                            }
                            $user->update([
                                'loss_reward_balance' => ($user->loss_reward_balance + $reward_amount)
                            ]);
                        }
                    }
                    $value->update([
                        'pl' => ($pl),
                        'is_cleared' => 1
                    ]);
                }
            }
        } else if ($game->slug == 'coin-flip') {
            $orders = Order::where('period_id', $period->id)->where('is_cleared', 0)->get();
            if ($result == 'head') {
                $result = 0;
            } else if ($result == 'tail') {
                $result = 1;
            }

            $price_money = rand(88400, 89000);
            $price_money = substr_replace((string) $price_money, (string) $result, -1);
            if ($result == 0) {
                $result = 'head';
            } else {
                $result = 'tail';
            }

            // BROADCAST THE RESULT
            broadcast(new GameResult($period->game_id, $period->id, $result));
            sleep(5);
            // UPDATE PERIOD
            $period->update([
                'result' => $result,
                'price_money' => $price_money,
                'is_cleared' => 1
            ]);
            // USERS PL
            $all_users = User::whereIn('id', $orders->pluck('user_id')->toArray())->get();
            foreach ($all_users as $user) {
                $user_orders = $orders->where('user_id', $user->id);
                foreach ($user_orders as $value) {
                    $delivery = $value->delivery;
                    $pl = 0;
                    if ($value->selection == $result) {
                        $pl = ($delivery * 2);
                    } else {
                        $pl = -($delivery);
                    }
                    if ($pl > 0) {
                        $user->update([
                            'balance' => ($user->balance + $pl)
                        ]);
                    } else if ($pl < 0) {
                        $user_recharges_amount = Recharge::where('user_id', $user->id)->where('is_cleared', 1)->where('is_rejected', 0)->sum('amount');
                        $reward_amount = ($pl * -1);
                        if ($user->loss_reward_balance < $user_recharges_amount) {
                            if (floatval($user->loss_reward_balance + $reward_amount) > floatval($user_recharges_amount)) {
                                $reward_amount = $user_recharges_amount - $user->loss_reward_balance;
                            }
                            $user->update([
                                'loss_reward_balance' => ($user->loss_reward_balance + $reward_amount)
                            ]);
                        }
                    }
                    $value->update([
                        'pl' => ($pl),
                        'is_cleared' => 1
                    ]);
                }
            }
        }

        // NEW PERIOD
        $old_periods_count = Period::where('game_id', $game->id)->whereDate('created_at', Carbon::today())->where('is_cleared', 1)->count();
        $new_period_time = str_replace('-', '', Carbon::now()->toDateString()) . ($old_periods_count + 1);
        Period::create([
            'game_id' => $game->id,
            'period' => $new_period_time
        ]);
        return response()->json(['success' => "Result submited successfully"]);
    }

    public function getOldPeriods($game_slug)
    {
        $game = Game::whereSlug($game_slug)->first();
        $periods = Period::where('game_id', $game->id)->where('is_cleared', 1)->latest('id')->get();
        return response()->json(['periods' => $periods]);
    }

    public function getCurrentPeriodBook(Request $request)
    {
        $game = Game::where('id', $request->game_id)->first();
        $orders = Order::where('period_id', $request->period_id)->where('is_cleared', 0)->get();
        $book = [];
        // GAME "WIN"
        if ($game->slug == 'win') {
            // GET ODDS
            $odds_multiplication = (object) [
                'green_red' => 2,
                'green_red_half' => 1.5,
                'voilet' => 4.5,
                'number' => 9,
            ];
            $jackpot_odds = Setting::first()->jackpot_odds;
            $book = [0,0,0,0,0,0,0,0,0,0,0];
            foreach ($orders as $order) {
                $delivery = $order->delivery;
                foreach ($book as $key => $book_value) {
                    if ((string) $order->selection == (string) $key) {
                        $book[$key] = $book[$key] - (($delivery * $odds_multiplication->number) - $delivery);
                    } else {
                        if (($order->selection == 'king' && ($key == 1 || $key == 3 || $key == 7 || $key == 9)) || ($order->selection == 'queen' && ($key == 2 || $key == 4 || $key == 6 || $key == 8))) {
                            $book[$key] = $book[$key] - $delivery;
                        } else if (($order->selection == 'king' && $key == 5) || ($order->selection == 'queen' && $key == 0)) {
                            $book[$key] = $book[$key] - (($delivery * $odds_multiplication->green_red_half) - $delivery);
                        } else if ($order->selection == 'joker' && ($key == 5 || $key == 0)) {
                            $book[$key] = $book[$key] - (($delivery * $odds_multiplication->voilet) - $delivery);
                        } else if ($order->selection == 'jackpot' && $key == 10) {
                            $book[$key] = $book[$key] - (($delivery * $jackpot_odds) - $delivery);
                        } else {
                            $book[$key] = $book[$key] + $delivery;
                        }
                    }
                }
            }
        } else if ($game->slug == 'coin-flip') {
            $book = [0,0];
            foreach ($orders as $order) {
                $delivery = $order->delivery;
                if ((string) $order->selection == (string) 'head') {
                    $book[0] = $book[0] - ($delivery * 2);
                    $book[1] = $book[1] + $delivery;
                } else if ((string) $order->selection == (string) 'tail') {
                    $book[1] = $book[1] - ($delivery * 2);
                    $book[0] = $book[0] + $delivery;
                }
            }
        }
        return response()->json(['book' => $book]);
    }
}
