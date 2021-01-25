<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Wallet as WalletResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\User;
use App\Wallet;
use Hash;

use Intervention\Image\Facades\Image;

class UserControllerAPI extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('id', '!=', 'null');
        if($request->filled('filterType')) {
            $filterType = $request->filterType;
            if($filterType == 'Administrator') {
                $filterType = 'a';
            }
            if($filterType == 'Operator') {
                $filterType = 'o';
            }
            if($filterType == 'User') {
                $filterType = 'u';
            }
            $users =  $users->where('type', '=', $filterType);
        } 
        if($request->filled('filterStatus')) {
            $filterStatus = $request->filterStatus;
            if($filterStatus == 'Active') {
                $filterStatus = '1';
            }
            if($filterStatus == 'Not Active') {
                $filterStatus = '0';
            }
            $users =  $users->where('active', '=', $filterStatus);
        }
        if($request->filled('filterName')) {
            $filterName = $request->filterName;
            $users =  $users->where('name', 'like', '%'.$filterName.'%');
        }
        if($request->filled('filterEmail')) {
            $filterEmail = $request->filterEmail;
            $users =  $users->where('email', 'like', $filterEmail);
        }
        if($request->has('sort')) {
            $sort = explode('|',$request->sort);
            $users =  $users->orderBy($sort[0],$sort[1]);
        }
        if ($request->has('page')) {
            return UserResource::collection($users->paginate(5));
        } else {
            return UserResource::collection($users->get());
        }
        
    }

    public function showPhoto($id)
    {
        $user = User::findOrFail($id);

        $storagePath = storage_path('app/public/fotos/'. $user->photo);

        return response()->file($storagePath);
    }

    public function getBalance(Request $request) {

        $data = Wallet::where('id', $request->id)->get('balance');
        
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $user = $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'min:3|required',
                'type' => 'required|in:u,o,a',
                'nif' => 'nullable|digits:9|unique:users,nif'
            ]);

        $user['password'] = Hash::make($request->password);

        if($request->hasfile('image')){

            $path = Storage::putFile('public/fotos', $request->file('image'));

            $user['photo'] = basename($path);
        }

        $userCreated = User::create($user);
        return response()->json(new UserResource($userCreated), 201);
    }

    public function register(Request $request)
    {
        $user = $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:3|required',
            'nif' => 'required|digits:9|unique:users,nif',
        ]);

        $user['password'] = Hash::make($request->password);
        $user['type'] = 'u';

        if($request->hasfile('image')){

            $path = Storage::putFile('public/fotos', $request->file('image'));

            $user['photo'] = basename($path);
        }

        $userCreated = User::create($user);

        return response()->json(new UserResource($userCreated), 201);
    }

    public function updatePhoto(Request $request, $id) {

        $user = User::findOrFail($id);

        if($request->hasfile('image')){

            if($user->photo != null && Storage::exists('public/fotos/'. $user->photo)){
                Storage::delete('public/fotos/'.$user->photo);
            }

            $path = Storage::putFile('public/fotos', $request->file('image'));

            $user->photo = basename($path);
            
            $user->save();
            return response()->json($user->photo, 201);
        }
    }

    public function update(Request $request, $id) {

        $user = User::findOrFail($id);

        $this->authorize('update', $user);

        $request->validate([
            'name' => 'min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ0-9 ]+$/',
            'oldPassword' => 'nullable',
            'nif' => ['nullable','digits:9',Rule::unique('users', 'nif')->ignore($request->id)],
            'password' => 'nullable|min:3|different:oldPassword',
            'passwordConfirmation' => 'same:password'
        ]);

        if($request->filled('oldPassword')) {
            if(!Hash::check($request->oldPassword, $user->password)){
                return response()->json("Invalid old password", 400);
            } else{
                if($request->filled('password')) {
                    $user['password'] = Hash::make($request->password);
                }
            }
        }

        $user->name = $request->name;
        
        if($request->filled('nif')) {
            $user->nif = $request->nif;
        }

        $user->save();
        return response()->json(new UserResource($user), 200);

    }

    public function me(Request $request)
    {
        if (!$request->user()) 
        {
            $message = 'Your information was not found.';
            return response()->json(compact('message'), 409);
        }

        return new UserResource($request->user());
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        //$user->destroy();
        User::destroy($id);

        if($user->photo != null && Storage::exists('public/fotos/'. $user->photo)){
            Storage::delete('public/fotos/'.$user->photo);
        }
        return response()->json(null, 204);
    }

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function showEmail($email) {
        $user = DB::table('users')->where('email', '=', $email)->get();
        return response()->json($user, 200);
    }
    
    public function countAccounts() {
        $counter = UserResource::collection(User::all())->count();
        return response()->json($counter, 200);
    }

    public function countAccountsLastWeek() {
        $counter = UserResource::collection(User::all())->where('created_at', '>=', Carbon::now()->subDays(7))->where('created_at', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countAccountsLastMonth() {
        $counter = UserResource::collection(User::all())->where('created_at', '>=', Carbon::now()->subDays(30))->where('created_at', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    }

    public function countAccountsLastYear() {
        $counter = UserResource::collection(User::all())->where('created_at', '>=', Carbon::now()->subDays(365))->where('created_at', '<=', Carbon::now())->count();
        return response()->json($counter, 200);
    } 

    public function countAdministrators() {
        $counter = DB::table('users')->where('type', '=', 'a')->count();
        return response()->json($counter, 200);
    }

    public function countAdministratorsLastWeek() {
        $counter = DB::table('users')->where('type', '=', 'a')->where('created_at', '>=', Carbon::now()->subDays(7))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countAdministratorsLastMonth() {
        $counter = DB::table('users')->where('type', '=', 'a')->where('created_at', '>=', Carbon::now()->subDays(30))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countAdministratorsLastYear() {
        $counter = DB::table('users')->where('type', '=', 'a')->where('created_at', '>=', Carbon::now()->subDays(365))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countOperators() {
        $counter = DB::table('users')->where('type', '=', 'o')->count();
        return response()->json($counter, 200);
    }

    public function countOperatorsLastWeek() {
        $counter = DB::table('users')->where('type', '=', 'o')->where('created_at', '>=', Carbon::now()->subDays(7))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countOperatorsLastMonth() {
        $counter = DB::table('users')->where('type', '=', 'o')->where('created_at', '>=', Carbon::now()->subDays(30))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countOperatorsLastYear() {
        $counter = DB::table('users')->where('type', '=', 'o')->where('created_at', '>=', Carbon::now()->subDays(365))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countUsers() {
        $counter = DB::table('users')->where('type', '=', 'u')->count();
        return response()->json($counter, 200);
    }

    public function countUsersLastWeek() {
        $counter = DB::table('users')->where('type', '=', 'u')->where('created_at', '>=', Carbon::now()->subDays(7))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countUsersLastMonth() {
        $counter = DB::table('users')->where('type', '=', 'u')->where('created_at', '>=', Carbon::now()->subDays(30))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countUsersLastYear() {
        $counter = DB::table('users')->where('type', '=', 'u')->where('created_at', '>=', Carbon::now()->subDays(365))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countActives() {
        $counter = DB::table('users')->where('active', '=', 1)->count();
        return response()->json($counter, 200);
    }

    public function countActivesLastWeek() {
        $counter = DB::table('users')->where('active', '=', 1)->where('created_at', '>=', Carbon::now()->subDays(7))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countActivesLastMonth() {
        $counter = DB::table('users')->where('active', '=', 1)->where('created_at', '>=', Carbon::now()->subDays(30))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function countActivesLastYear() {
        $counter = DB::table('users')->where('active', '=', 1)->where('created_at', '>=', Carbon::now()->subDays(365))->where('created_at', '<=', Carbon::now())->get()->count();
        return response()->json($counter, 200);
    }

    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

    public function sendMail($to, $name) {
        $data = array('name' => $name, 'email' => $to);
	
        Mail::send('email', $data, function($message) use ($data)
        {
            $message->to($data['email'])
            ->subject('New movement added to your virtual wallet');
        });
    }

    public function sendMailSpecial($to) {
        $data = array('email' => $to);
	
        Mail::send('emailSpecial', $data, function($message) use ($data)
        {
            $message->to($data['email'])
            ->subject('New movement added to an user virtual wallet');
        });
    }

    public function getUserType($email) {
        $type = DB::table('users')->where('email', '=', $email)->get('type');
        return response()->json($type, 200);
    }

    public function changeActive(Request $request, $id) {
        $user = User::findOrFail($id);

        if($user->active == 1) {
            $user->active = 0;
        } else {
            $user->active = 1;
        }

        $user->save();
    }
}
