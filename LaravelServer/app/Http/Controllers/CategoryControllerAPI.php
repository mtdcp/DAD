<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;

use App\Http\Resources\Category as CategoryResource;
use Illuminate\Support\Facades\DB;

use App\Category;
use Hash;

class CategoryControllerAPI extends Controller
{
    public function index(Request $request)
    {
        $cats = CategoryResource::collection(Category::get());
        return response($cats, 200);
    }
}
