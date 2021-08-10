<?php

namespace App\Http\Controllers\Api\Models;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\GarageRequest;

use App\Models\Garage;

class GarageController extends Controller
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
                if(!$user->garages()->find($request->garage)){
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
        return $user->garages()->paginate($qtd);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GarageRequest $request)
    {
        $data = $request->only(['user_id', 'address_id', 'image', 'price', 'description']);
        $user = \Authorization::user();
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $data['image'] = "$profileImage";
        }
        $model = $user->garages()->create($data);
        return [
            "data" => [
                "garage" => $model
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
            "data" => $user->garages()->find($request->garage)
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GarageRequest $request)
    {
        $data = $request->only(['user_id', 'address_id', 'image', 'price', 'description']);
        $garage = $this->show($request)["data"];
        $garage->update($data);
        $garage->save();
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $data['image'] = "$profileImage";
        }else{
            unset($data['image']);
        }
        return [
            "data" => $garage
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
        return $user->garages()->where('id', '=', $request->garage)->delete();
    }
}
