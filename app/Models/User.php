<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Events\UserUpdate;
use Auth;
use Log;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'balance',
        'loss_reward_balance',
        'commission',
        'interest',
        'r_code',
        'password',
        'password_text',
        'is_admin',
        'contribution',
        'level_1_first_reward',
        'level_2_first_reward',
        'level_3_first_reward',
        'level_1_water_reward',
        'level_2_water_reward',
        'level_3_water_reward',
        'referred_id',
        'is_blocked'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::updated(function ($user) {
            broadcast(new UserUpdate($user->id, $user->balance, $user->loss_reward_balance));
        });
    }
    public function banks()
    {
        return $this->hasMany(Bank::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
