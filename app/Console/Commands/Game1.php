<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\Period;
use App\Models\User;
use App\Models\Order;
use App\Models\Recharge;
use Carbon\Carbon;
use App\Models\Setting;
use Log;

class Game1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $game = Game::where('slug', 'win')->where('is_active', 1)->first();
        if ($game == null) {
            $game = Game::create([
                'title' => 'win',
                'slug' => 'win',
                'is_active' => 1,
                'time' => 150
            ]);
        }
        // GET ODDS
        $odds_multiplication = (object) [
            'green_red' => 2,
            'green_red_half' => 1.5,
            'voilet' => 4.5,
            'number' => 9
        ];
        
        while (true) {
            $period = Period::where('game_id', $game->id)->where('is_cleared', 0)->latest('id')->first();
            $jackpot_odds = Setting::first()->jackpot_odds;
            if ($period == null) {
                Period::create([
                    'game_id' => $game->id,
                    'period' => time()
                ]);
            } else {
                $now = Carbon::now();
                $period_time = Carbon::parse($period->created_at)->addSeconds($game->time);
                if ($now->gt($period_time) && $period->is_manual == 0) {
                    sleep(2);
                    $orders = Order::where('period_id', $period->id)->where('is_cleared', 0)->get();
                    // RESULT
                    if (count($orders) > 0) {
                        $book = [0,0,0,0,0,0,0,0,0,0];
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
                                    } else {
                                        $book[$key] = $book[$key] + $delivery;
                                    }
                                }
                            }
                        }

                        $max = max($book);
                        $results = array_filter($book, function ($number) use ($max) {
                            return $number === $max;
                        });
                        $results = array_keys($results);
                        shuffle($results);
                        $result = $results[0];
                    } else {
                        $result = rand(0, 9);
                    }

                    $price_money = rand(88400, 89000);
                    $price_money = substr_replace((string) $price_money, (string) $result, -1);
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
                                if ((string) $value->selection == (string) $result) {
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
                    // NEW PERIOD
                    $old_periods_count = Period::where('game_id', $game->id)->whereDate('created_at', Carbon::today())->where('is_cleared', 1)->count();
                    $new_period_time = str_replace('-', '', Carbon::now()->toDateString()) . ($old_periods_count + 1);
                    Period::create([
                        'game_id' => $game->id,
                        'period' => $new_period_time
                    ]);
                }
            }
            sleep(1);
        }
    }
}
