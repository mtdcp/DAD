<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Wallet as WalletResource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'password' => "",
            'remember_token' => $this->remember_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => ($this->type == 'a') ? 'Administrator' : (($this->type == 'o') ? 'Operator' : 'User'),
            'active' => ($this->active) ? 'Active' : 'Not Active',
            'photo' => ($this->photo) ? $this->photo() : null,
            'nif' => $this->nif,
            'balance' => ($this->wallet) ? (($this->wallet->balance > 0) ? 'Balance > 0' : 'Balance = 0') : 'Don\'t have wallet'
        ];
    }

    public function photo() {
        return asset('api/users/'. $this->id . '/photo');
    }
}
