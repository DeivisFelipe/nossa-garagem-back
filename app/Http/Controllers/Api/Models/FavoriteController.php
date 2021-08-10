<?php

namespace App\Http\Controllers\Api\Models;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\IndexRequest;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user')
            ->only(['index','detach','attach']);
        $this->middleware(function ($request,$next){
            $user = \Authorization::user();
            if(!$user->isAdmin()){
                if(!$user->favorites()->find($request->favorite)){
                    abort(403);
                }
            }
            return $next($request);
        })->only(['detach']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $page = $request->input('page', 1);
        $qtd = $request->input('qtd', 20);
        $user = \Authorization::user();
        return $user->favorites()->paginate($qtd);
    }

    public function detach(Request $request)
    {
        $user = \Authorization::user();
        $oldData = $user->favorites()->find($request->favorite);
        $user->favorites()->detach($request->favorite);
        return $oldData;
    }

    public function attach(Request $request)
    {
        $user = \Authorization::user();
        $user->favorites()->attach($request->favorite);
        return $user->favorites()->find($request->favorite);
    }

}
