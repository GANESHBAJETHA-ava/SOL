<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;


class Sport extends Model
{
    use HasFactory;
  

	protected $fillable = [
		'name',
		'slug',
		'is_active'
	];


	public function matches() {
		return $this->hasMany('App\Models\Match');
	}

}
