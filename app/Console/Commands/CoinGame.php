<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\Period;
use App\Models\User;
use App\Models\Order;
use App\Models\Recharge;
use Carbon\Carbon;
use Log;
use App\Events\GameResult;

class CoinGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin_game';

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
        $game = Game::where('slug', 'coin-flip')->where('is_active', 1)->first();
        if ($game == null) {
            $game = Game::create([
                'title' => 'Coin Flip',
                'slug' => 'coin-flip',
                'is_active' => 1,
                'time' => 30
            ]);
        }
        
        while (true) {
            $period = Period::where('game_id', $game->id)->where('is_cleared', 0)->latest('id')->first();
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
                        $head = 0;
                        $tail = 0;
                        foreach ($orders as $order) {
                            $delivery = $order->delivery;
                            if ((string) $order->selection == (string) 'head') {
                                $head = $head + ($delivery * 2);
                                $tail = $tail - $delivery;
                            } else if ((string) $order->selection == (string) 'tail') {
                                $tail = $tail + ($delivery * 2);
                                $head = $head - $delivery;
                            }
                        }
                        if ($head != $tail) {
                            if ($head < $tail) {
                                $result = 0;
                            } else if ($head > $tail) {
                                $result = 1;
                            }
                        } else {
                            $result = rand(0, 1);
                        }
                    } else {
                        $result = rand(0, 1);
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
