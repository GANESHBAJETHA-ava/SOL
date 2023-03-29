<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'period_id',
        'selection',
        'contract_money',
        'contract_count',
        'delivery',
        'fee',
        'pl',
        'is_cleared'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function getCreatedAttribute()
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    protected $appends = ["created"];
}
