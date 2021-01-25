<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Movement as MovementResource;
use App\Movement;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use App\User;
use App\Wallet;

class MovementControllerAPI extends Controller{

    public function index(Request $request)
    {
        $movements = Movement::where('movements.wallet_id', '=', $request->id); 

        if($request->filled('filterIdMovements')) {
            $filterIdMovements = $request->filterIdMovements;
            $movements =  $movements->where('movements.id', '=', $filterIdMovements);
        }
        if($request->filled('filterTypeMovements')) {
            $filterTypeMovements = $request->filterTypeMovements;
            if($filterTypeMovements == 'Expense') {
                $filterTypeMovements = 'e';
            }
            if($filterTypeMovements == 'Income') {
                $filterTypeMovements = 'i';
            }
            $movements =  $movements->where('movements.type', '=', $filterTypeMovements);
        }
        if($request->filled('filterCategoryMovements')) {
            $filterCategoryMovements = $request->filterCategoryMovements;
            $joinCat = $movements->join('categories', 'movements.category_id', '=', 'categories.id');
            $movements =  $joinCat->where('categories.name', '=', $filterCategoryMovements)->select('movements.id', 'movements.type', 'movements.type_payment', 'movements.category_id', 'movements.date', 'movements.value', 'movements.start_balance', 'movements.end_balance');
        }
        if($request->filled('filterTypePaymentMovements')) {
            $filterTypePaymentMovements = $request->filterTypePaymentMovements;
            if($filterTypePaymentMovements == 'Multibanco') {
                $filterTypePaymentMovements = 'mb';
            }
            if($filterTypePaymentMovements == 'MB payment') {
                $filterTypePaymentMovements = 'mb';
            }
            if($filterTypePaymentMovements == 'Cash') {
                $filterTypePaymentMovements = 'c';
            }
            if($filterTypePaymentMovements == 'Bank Transfer') {
                $filterTypePaymentMovements = 'bt';
            }
            if($filterTypePaymentMovements == 'Bank') {
                $filterTypePaymentMovements = 'bt';
            }
            $movements =  $movements->where('movements.type_payment', '=', $filterTypePaymentMovements);
        }
        if($request->filled('filterEmailMovements')) {
            $filterEmailMovements = $request->filterEmailMovements;
            $movements = $movements->where('wallets.email', '=', $filterEmailMovements)->where('movements.transfer', '=', 1)->join('wallets', function ($join) {
                $join->on('wallets.id', '=', 'movements.transfer_wallet_id')->orOn('wallets.id', '=', 'movements.wallet_id');
            })->select('movements.id', 'movements.type', 'movements.type_payment', 'movements.category_id', 'movements.date', 'movements.value', 'movements.start_balance', 'movements.end_balance');
        }
        if($request->filled('filterStartDateMovements') && $request->filled('filterEndDateMovements') ) {
            $filterStartDateMovements = $request->filterStartDateMovements;
            $filterEndDateMovements = $request->filterEndDateMovements;
            $movements = $movements->whereBetween('date', [$filterStartDateMovements, $filterEndDateMovements]);
        }
        if($request->has('sort')) {
            $sort = explode('|',$request->sort);
            $movements =  $movements->orderBy($sort[0],$sort[1]);
        }
        if ($request->has('page')) {
            return MovementResource::collection($movements->paginate(5));
        } else {
            return MovementResource::collection($movements->get());
        }
    }

    public function store(Request $request)
    {
        $this->authorize('createMovement', User::findOrFail($request->user()->id));
        $movement = $request->validate([
                'transfer' => 'required|in:0,1',
                'value' => 'required|numeric|between:0.01,5000.00',
                'category_id' => 'required|exists:categories,name',
                'description' => 'required',
                'type_payment' => 'sometimes|required_if:transfer,0|in:bt,mb',
                'iban' => 'sometimes|required_if:type_payment,bt|regex:/^[A-Z]{2}[0-9]{23}/',
                'mb_entity_code' => 'sometimes|required_if:type_payment,mb|numeric|digits:5',
                'mb_payment_reference' => 'sometimes|required_if:type_payment,mb|numeric|digits:9',
                'transfer_wallet_id' => 'sometimes|required_if:transfer,1|exists:wallets,email|email',
                'source_description' => 'sometimes|required_if:transfer,1'
            ]);

        $movement['wallet_id'] = $request->user()->id;
        $movement['type'] = 'e';
        $category = DB::table('categories')->where('name', '=', $request->category_id)->get('id');
        $movement['category_id'] = $category[0]->id;

        $start_balance = Wallet::where("id", '=', $request->user()->id)->get('balance');
        $movement['start_balance'] = $start_balance[0]->balance;
        $movement['end_balance'] = $start_balance[0]->balance - $request->value;
        $movement['date'] = Carbon::now();

        if($request->transfer == "1"){
            $wallet_id = Wallet::where("email", '=', $request->transfer_wallet_id)->get('id');
            $movement['transfer_wallet_id'] = $wallet_id[0]->id;
        }

        $wallet = Wallet::findOrFail($request->user()->id);
        $wallet->balance = $start_balance[0]->balance - $request->value;
        $movementCreated = Movement::create($movement);
        $wallet->save();
        if($request->transfer == "1"){
            $movementPair = $movement;
            $movementPair['type'] = 'i';
            $movementPair['description'] = null;
            $movementPair['category_id'] = null;
            $movementPair['wallet_id'] = $wallet_id[0]->id;
            $movementPair['transfer_wallet_id'] = $wallet->id;
            $movementPair['transfer_movement_id'] = $movementCreated->id;
            $start_balancePair = Wallet::where("id", '=', $wallet_id[0]->id)->get('balance');
            $movementPair['start_balance'] = $start_balancePair[0]->balance;
            $movementPair['end_balance'] = $start_balancePair[0]->balance + $request->value;
            
            $walletPair = Wallet::findOrFail($wallet_id[0]->id);
            $walletPair->balance = $start_balancePair[0]->balance + $request->value;
            $movementPairCreated = Movement::create($movementPair);
            $walletPair->save();
            $movementCreated['transfer_movement_id'] = $movementPairCreated->id;
            $movementCreated->save();
        }

        return response()->json(new MovementResource($movementCreated), 201);
    }

    public function createIncome(Request $request){
        $movement = $request->validate([
            'wallet_id' => 'required|exists:wallets,email|email',
            'value' => 'required|numeric|between:0.01,5000.00',
            'type_payment' => 'required|in:c,bt',
            'iban' => 'sometimes|required_if:type_payment,bt|regex:/^[A-Z]{2}[0-9]{23}/',
            'source_description' => 'required'
        ]);
        $wallet_id = Wallet::where("email", '=', $request->wallet_id)->get('id');
        $movement['wallet_id'] = $wallet_id[0]->id;
        $movement['type'] = 'i';
        $movement['transfer'] = 0;
        $start_balance = Wallet::where("id", '=', $wallet_id[0]->id)->get('balance');
        $movement['start_balance'] = $start_balance[0]->balance;
        $movement['end_balance'] = $start_balance[0]->balance + $request->value;
        $movement['date'] = Carbon::now();

        $wallet = Wallet::findOrFail($wallet_id[0]->id);
        $wallet->balance = $start_balance[0]->balance + $request->value;
        $movementCreated = Movement::create($movement);
        $wallet->save();
        
        return response()->json(new MovementResource($movementCreated), 201);
    }

    public function getBalance(Request $request) {
        $this->authorize('viewStats', User::findOrFail($request->id));
        $balance = DB::table('movements')->where('wallet_id', '=', $request->id)->orderBy('date', 'desc')->get('end_balance')->first();
        return response()->json($balance, 200);
    }

    public function update(Request $request, $id) {

        $movement = Movement::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,name'
        ]);

        $category = DB::table('categories')->where('name', '=', $request->category_id)->get('id');
        $movement->category_id = $category[0]->id;
        $movement->description = $request->description;
         
        $movement->save();
        return response()->json(new MovementResource($movement), 200);

    }

    public function countMovements() {
        $counter = DB::table('movements')->count();
        return response()->json($counter, 200);
    }

    public function countMovementsLastWeek() {
        $counter = DB::table('movements')->where('date', '>=', Carbon::now()->subDays(7))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countMovementsLastMonth() {
        $counter = DB::table('movements')->where('date', '>=', Carbon::now()->subDays(30))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countMovementsLastYear() {
        $counter = DB::table('movements')->where('date', '>=', Carbon::now()->subDays(365))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countExpenses($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->count();
        return response()->json($counter, 200);
    }

    public function countIncomes($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->count();
        return response()->json($counter, 200);
    }

    public function countExpensesLastWeek($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->where('date', '>=', Carbon::now()->subDays(7))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countIncomesLastWeek($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->where('date', '>=', Carbon::now()->subDays(7))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countExpensesLastMonth($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->where('date', '>=', Carbon::now()->subDays(30))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countIncomesLastMonth($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->where('date', '>=', Carbon::now()->subDays(30))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countExpensesLastYear($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->where('date', '>=', Carbon::now()->subDays(365))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countIncomesLastYear($id) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->where('date', '>=', Carbon::now()->subDays(365))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countExpensesCategory($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->count();
        return response()->json($counter, 200);
    }

    public function countIncomesCategory($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->count();
        return response()->json($counter, 200);
    }

    public function countExpensesCategoryLastWeek($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->where('date', '>=', Carbon::now()->subDays(7))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countIncomesCategoryLastWeek($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->where('date', '>=', Carbon::now()->subDays(7))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countExpensesCategoryLastMonth($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->where('date', '>=', Carbon::now()->subDays(30))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countIncomesCategoryLastMonth($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->where('date', '>=', Carbon::now()->subDays(30))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countExpensesCategoryLastYear($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'e')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->where('date', '>=', Carbon::now()->subDays(365))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countIncomesCategoryLastYear($id, $category) {
        $this->authorize('viewStats', User::findOrFail($id));
        $counter = DB::table('movements')->where('type', '=', 'i')->where('wallet_id', '=', $id)->where('category_id', '=', $category)->where('date', '>=', Carbon::now()->subDays(365))->where('date', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }
}
