<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'LoginControllerAPI@login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');

Route::middleware('auth:api')->get('teste', function () {
    return response()->json(['msg'=>'Só um teste'], 200);
});

Route::middleware('auth:api')->group(function(){
	Route::get('/sendEmail/{to}/{name}', 'UserControllerAPI@sendMail');
	Route::get('/sendEmailSpecial/{to}', 'UserControllerAPI@sendMailSpecial');
	Route::get('/users', 'UserControllerAPI@index')->middleware('can:viewAny,App\User');
	Route::get('/users/all/count', 'UserControllerAPI@countAccounts')->middleware('can:viewAny,App\User');
	Route::get('/users/all/lastWeek/count', 'UserControllerAPI@countAccountsLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/users/all/lastMonth/count', 'UserControllerAPI@countAccountsLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/users/all/lastYear/count', 'UserControllerAPI@countAccountsLastYear')->middleware('can:viewAny,App\User');
	Route::get('/users/admins/count', 'UserControllerAPI@countAdministrators')->middleware('can:viewAny,App\User');
	Route::get('/users/admins/lastWeek/count', 'UserControllerAPI@countAdministratorsLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/users/admins/lastMonth/count', 'UserControllerAPI@countAdministratorsLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/users/admins/lastYear/count', 'UserControllerAPI@countAdministratorsLastYear')->middleware('can:viewAny,App\User');
	Route::get('/users/operators/count', 'UserControllerAPI@countOperators')->middleware('can:viewAny,App\User');
	Route::get('/users/operators/lastWeek/count', 'UserControllerAPI@countOperatorsLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/users/operators/lastMonth/count', 'UserControllerAPI@countOperatorsLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/users/operators/lastYear/count', 'UserControllerAPI@countOperatorsLastYear')->middleware('can:viewAny,App\User');
	Route::get('/users/users/count', 'UserControllerAPI@countUsers')->middleware('can:viewAny,App\User');
	Route::get('/users/users/lastWeek/count', 'UserControllerAPI@countUsersLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/users/users/lastMonth/count', 'UserControllerAPI@countUsersLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/users/users/lastYear/count', 'UserControllerAPI@countUsersLastYear')->middleware('can:viewAny,App\User');
	Route::get('/users/active/count', 'UserControllerAPI@countActives')->middleware('can:viewAny,App\User');
	Route::get('/users/active/lastWeek/count', 'UserControllerAPI@countActivesLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/users/active/lastMonth/count', 'UserControllerAPI@countActivesLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/users/active/lastYear/count', 'UserControllerAPI@countActivesLastYear')->middleware('can:viewAny,App\User');
	Route::get('users/emailavailable', 'UserControllerAPI@emailAvailable');
	Route::get('users/me', 'UserControllerAPI@me');
	Route::get('users/show/{email}', 'UserControllerAPI@showEmail');
	Route::get('/users/balance/{id}', 'UserControllerAPI@getBalance');
	Route::get('/users/type/{email}', 'UserControllerAPI@getUserType');
	Route::get('users/{id}', 'UserControllerAPI@show');
	Route::post('/users', 'UserControllerAPI@store')->middleware('can:create,App\User');
	Route::patch('users/{id}', 'UserControllerAPI@update');
	Route::put('users/{id}', 'UserControllerAPI@changeActive')->middleware('can:changeActive,App\User');
	Route::delete('users/{id}', 'UserControllerAPI@destroy')->middleware('can:delete,App\User');

	Route::get('/wallets', 'WalletControllerAPI@index')->middleware('can:viewAny,App\Wallet');
	Route::get('/wallets/all/lastWeek/count', 'WalletControllerAPI@countWalletsLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/wallets/all/lastMonth/count', 'WalletControllerAPI@countWalletsLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/wallets/all/lastYear/count', 'WalletControllerAPI@countWalletsLastYear')->middleware('can:viewAny,App\User');
	Route::get('/wallets/all/lastWeek/balance', 'WalletControllerAPI@getGlobalBalanceLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/wallets/all/lastMonth/balance', 'WalletControllerAPI@getGlobalBalanceLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/wallets/all/lastYear/balance', 'WalletControllerAPI@getGlobalBalanceLastYear')->middleware('can:viewAny,App\User');
	Route::get('/wallets/balance/{id}', 'WalletControllerAPI@getUserBalance');

	Route::get('/movements/all/count', 'MovementControllerAPI@countMovements')->middleware('can:viewAny,App\User');
	Route::get('/movements/all/lastWeek/count', 'MovementControllerAPI@countMovementsLastWeek')->middleware('can:viewAny,App\User');
	Route::get('/movements/all/lastMonth/count', 'MovementControllerAPI@countMovementsLastMonth')->middleware('can:viewAny,App\User');
	Route::get('/movements/all/lastYear/count', 'MovementControllerAPI@countMovementsLastYear')->middleware('can:viewAny,App\User');
	Route::get('/movements/balance/{id}', 'MovementControllerAPI@getBalance');
	Route::get('/movements/income/{id}/count', 'MovementControllerAPI@countIncomes');
	Route::get('/movements/expense/{id}/count', 'MovementControllerAPI@countExpenses');
	Route::get('/movements/income/{id}/count/lastWeek', 'MovementControllerAPI@countIncomesLastWeek');
	Route::get('/movements/expense/{id}/count/lastWeek', 'MovementControllerAPI@countExpensesLastWeek');
	Route::get('/movements/income/{id}/count/lastMonth', 'MovementControllerAPI@countIncomesLastMonth');
	Route::get('/movements/expense/{id}/count/lastMonth', 'MovementControllerAPI@countExpensesLastMonth');
	Route::get('/movements/income/{id}/count/lastYear', 'MovementControllerAPI@countIncomesLastYear');
	Route::get('/movements/expense/{id}/count/lastYear', 'MovementControllerAPI@countExpensesLastYear');
	Route::get('/movements/income/{id}/count/{category}', 'MovementControllerAPI@countIncomesCategory');
	Route::get('/movements/expense/{id}/count/{category}', 'MovementControllerAPI@countExpensesCategory');
	Route::get('/movements/income/{id}/count/{category}/lastWeek', 'MovementControllerAPI@countIncomesCategoryLastWeek');
	Route::get('/movements/expense/{id}/count/{category}/lastWeek', 'MovementControllerAPI@countExpensesCategoryLastWeek');
	Route::get('/movements/income/{id}/count/{category}/lastMonth', 'MovementControllerAPI@countIncomesCategoryLastMonth');
	Route::get('/movements/expense/{id}/count/{category}/lastMonth', 'MovementControllerAPI@countExpensesCategoryLastMonth');
	Route::get('/movements/income/{id}/count/{category}/lastYear', 'MovementControllerAPI@countIncomesCategoryLastYear');
	Route::get('/movements/expense/{id}/count/{category}/lastYear', 'MovementControllerAPI@countExpensesCategoryLastYear');
	Route::get('/movements/{id}', 'MovementControllerAPI@index');
	Route::post('/movements', 'MovementControllerAPI@store');
	Route::post('/movements/operator', 'MovementControllerAPI@createIncome')->middleware('can:registerMovement,App\Movement');
	Route::put('movements/{id}', 'MovementControllerAPI@update');

	Route::get('/categories', 'CategoryControllerAPI@index');
});
Route::get('/users/{id}/photo', 'UserControllerAPI@showPhoto');
Route::post('/users/create','UserControllerAPI@register');
Route::post('/users/{id}/photo', 'UserControllerAPI@updatePhoto');

Route::get('/wallets/all/count', 'WalletControllerAPI@countWallets');
Route::post('/wallets', 'WalletControllerAPI@store');

Route::get('/movements/{walletId}/user', 'MovementControllerAPI@getUser');

Route::get('/wallets/all/balance', 'WalletControllerAPI@getGlobalBalance');