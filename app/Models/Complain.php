<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'whatsapp',
        'des',
        'reply'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
