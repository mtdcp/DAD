<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'wallet_id',
        'type',
        'transfer',
        'transfer_movement_id',
        'transfer_wallet_id',
        'type_payment',
        'category_id',
        'iban',
        'mb_entity_code',
        'mb_payment_reference',
        'description',
        'source_description',
        'date',
        'start_balance',
        'end_balance',
        'value'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'transfer_wallet_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
