<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'actual_name',
        'IFSC',
        'bank_name',
        'bank_account',
        'state',
        'city',
        'address',
        'mobile_number',
        'email',
        'account_type',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected $appends = ["account_with_name"];

    public function getAccountWithNameAttribute()
    {
        $value = $this->bank_name . " ******" . substr($this->bank_account, -3);
        return $value;
    }
}
