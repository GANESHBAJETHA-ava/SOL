<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;
    protected $fillable = [
		'match_id',
		'name',
		'slug',
		'mrk_id',
        'result',
		'is_bet_block',
		'is_active',
		'odds'
	];

    public function match()
	{
		return $this->belongsTo('App\Models\Match','match_id', 'sport_id');
	}

}
