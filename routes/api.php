<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserAuthController;

// /api/...
Route::namespace('Auth')->prefix('auth')->group(function (){
    Route::post('login/user', 'UserAuthController@login')->middleware('throttle:15,120');
    Route::post('logout/user', 'UserAuthController@logout')->middleware('auth:user');
    Route::get('me/user', 'UserAuthController@me')->middleware('auth:user');
    Route::get('refresh/user', 'UserAuthController@refresh');
});


Route::namespace('Models')->group(function (){
    Route::apiResource('user','UserController');
    Route::apiResource('garage','GarageController');
    Route::apiResource('address','AddressController');
    Route::apiResource('favorite','FavoriteController');

    Route::post('favorite/attach/{favorite}','FavoriteController@attach');
    Route::delete('favorite/detach/{favorite}','FavoriteController@detach');
});
