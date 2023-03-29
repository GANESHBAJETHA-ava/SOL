<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DummyWin extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_number',
        'to_number',
        'from_amount',
        'to_amount',
        'text',
        'is_active'
    ];
}
