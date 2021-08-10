<?php

namespace App\Http\Controllers\Api\Models;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\AddressRequest;

use App\Models\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user')
            ->only(['index','store','update','show','destroy']);
        $this->middleware('throttle:100,5')
            ->only(['store']);
        $this->middleware(function ($request,$next){
            $user = \Authorization::user();
            if(!$user->isAdmin()){
                if(!$user->adresses()->find($request->address)){
                    abort(403);
                }
            }
            return $next($request);
        })->only(['update', 'destroy']);
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
        return $user->adresses()->paginate($qtd);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
    {
        $data = $request->only(['cep', 'street', 'city', 'number', 'user_id']);
        $user = \Authorization::user();
        $model = $user->adresses()->create($data);
        return [
            "data" => [
                "address" => $model
            ]
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = \Authorization::user();
        return [
            "data" => $user->adresses()->find($request->address)
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request)
    {
        $data = $request->only(['cep', 'street', 'city', 'number', 'user_id']);
        $address = $this->show($request)["data"];
        $address->update($data);
        $address->save();
        return [
            "data" => $address
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = \Authorization::user();
        return $user->adresses()->where('id', '=', $request->address)->delete();
    }
}
