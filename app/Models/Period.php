<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\GameBroadcast;
use Carbon\Carbon;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'period',
        'price_money',
        'result',
        'is_cleared',
        'is_manual'
    ];

    protected $appends = ["number", "period_time"];

    public function getNumberAttribute()
    {
        return strtoupper($this->result);
    }

    public function getPeriodTimeAttribute()
    {
        return Carbon::parse($this->created_at)->timestamp;
    }

    public function getResultAttribute($value)
    {
        return strtoupper($value);
    }

    public function getPriceMoneyAttribute($value)
    {
        return strtoupper($value);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($period) {
            broadcast(new GameBroadcast($period->game_id, $period));
        });
    }
}
