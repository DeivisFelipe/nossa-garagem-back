<?php

namespace App\Http\Controllers\Api\Models;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\IndexRequest;

use App\Models\User;
use Faker\Factory as Faker;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:100,5')
            ->only(['store']);
        $this->middleware(function ($request,$next){
            $user = \Authorization::user();
            if(!$user->isAdmin()){
                if($request->user!=$user->id){
                    abort(403);
                }
            }
            return $next($request);
        })->only(['update','show']);
    }

    public function index(IndexRequest $request)
    {
        $page = $request->input('page', 1);
        $qtd = $request->input('qtd', 20);
        return User::paginate($qtd);
    }

    public function store(UserRequest $request)
    {
        $data = $request->only(['email', 'password', 'name', 'phone']);
        $model = User::create($data);
        return [
            "data" => [
                "user" => $model
            ]
        ];
    }

    public function show(Request $request)
    {
        return [
            "data" => User::findOrFail($request->user)
        ];
    }

    public function update(UserRequest $request)
    {
        $data = $request->only([
            'email',
            'password',
            'name',
            'phone'
        ]);
        $user = $this->show($request)["data"];
        $user->update($data);
        $user->save();
        return [
            "data" => $user
        ];
    }

    public function destroy(Request $request)
    {
        return User::destroy($request->user);
    }
}
