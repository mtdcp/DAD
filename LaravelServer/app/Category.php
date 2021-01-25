<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'id',
        'type',
        'name'
    ];

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
