<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_reward',
        'level_1_reward',
        'level_2_reward',
        'level_3_reward',
        'order_fee',
        'withdrawal_fee',
        'jackpot_odds',
        'reward_threshold'
    ];
}
