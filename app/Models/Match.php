<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    protected $fillable = [
        'sport_id',
        'name',
        'slug',
        'team_one',
        'team_two',
        'match_date',
        'is_bet_block',
        'is_active'
    ];

    public function sport()
    {
        return $this->belongsTo('App\Models\Sport', 'sport_id', 'id');
    }

}
