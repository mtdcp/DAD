<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\Wallet as WalletResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;

use App\Wallet;
use App\User;
use Hash;
use Carbon\Carbon;

class WalletControllerAPI extends Controller
{
    public function index(Request $request)
    {
        return WalletResource::collection(Wallet::all());
    }

    public function store(Request $request)
    {
        $wallet = $request->validate([
                'id' => 'required|max:20|unique:wallets,id',
                'email' => 'required|email|unique:wallets,email',
                'balance' => 'required|numeric'
        ]);

        $walletCreated = Wallet::create($wallet);
        return response()->json(new WalletResource($walletCreated), 201);
    }

    public function countWallets() {
        $counter = WalletResource::collection(Wallet::all())->count();
        return response()->json($counter, 200);
    }

    public function countWalletsLastWeek() {
        $counter = WalletResource::collection(Wallet::all())->where('created_at', '>=', Carbon::now()->subDays(7))->where('created_at', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countWalletsLastMonth() {
        $counter = WalletResource::collection(Wallet::all())->where('created_at', '>=', Carbon::now()->subDays(30))->where('created_at', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countWalletsLastYear() {
        $counter = WalletResource::collection(Wallet::all())->where('created_at', '>=', Carbon::now()->subDays(365))->where('created_at', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function getGlobalBalance() {
        $balance = WalletResource::collection(Wallet::all())->sum('balance');
        return response()->json(round($balance, 2), 200);
    }

    public function getGlobalBalanceLastWeek() {
        $balance = WalletResource::collection(Wallet::all())->where('created_at', '>=', Carbon::now()->subDays(7))->where('created_at', '<=', Carbon::now())->sum('balance');
        return response()->json(round($balance, 2), 200);
    }

    public function getGlobalBalanceLastMonth() {
        $balance = WalletResource::collection(Wallet::all())->where('created_at', '>=', Carbon::now()->subDays(30))->where('created_at', '<=', Carbon::now())->sum('balance');
        return response()->json(round($balance, 2), 200);
    }

    public function getGlobalBalanceLastYear() {
        $balance = WalletResource::collection(Wallet::all())->where('created_at', '>=', Carbon::now()->subDays(365))->where('created_at', '<=', Carbon::now())->sum('balance');
        return response()->json(round($balance, 2), 200);
    }

    public function getUserBalance($id) {
        $wallet = WalletResource::collection(Wallet::where('id', '=', $id )->get());
        return response()->json($wallet, 200);
    }
}
