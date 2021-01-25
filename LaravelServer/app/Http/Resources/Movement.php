<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Movement extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'type' => ($this->type == 'e') ? 'Expense' : (($this->type == 'i') ? 'Income' : 'Movement'),
            'transfer' => $this->transfer,
            'transfer_movement_id' => $this->transfer_movement_id,
            'transfer_wallet_id' => $this->transfer_wallet_id,
            'type_payment' => $this->transfer ? 'N/A' : (($this->type_payment  == 'c') ? 'Cash' : (($this->type_payment == 'bt') ? 'Bank Transfer' : 'Multibanco/MB payment')),
            'category_id' => ($this->category) ? $this->category->name : 'N/A',
            'iban' => $this->iban,
            'mb_entity_code' => $this->mb_entity_code,
            'mb_payment_reference' => $this->mb_payment_reference,
            'description' => $this->description,
            'source_description' => $this->source_description,
            'date' => $this->date,
            'start_balance' => $this->start_balance.'€',
            'end_balance' => $this->end_balance.'€',
            'value' => (($this->type) == 'i') ? $this->value.' €' : '('.$this->value.' €)',
            'email' => ($this->transfer) ? $this->wallet->email : ''
        ];
    }

}
