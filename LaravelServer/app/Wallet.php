<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Movement;

class Wallet extends Model
{
    protected $fillable = [
        'id',
        'email',
        'balance',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
