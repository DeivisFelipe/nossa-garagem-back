<?php

namespace App\Helper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Authorization{


    public static function isAuthenticated(){
        if(self::guard()==null)return false;
        else return true;
    }

    public static function user(){
        $guard = self::guard();
        if($guard==null)return null;
        return Auth::guard($guard)->user();
    }

    public static function guard(){
        foreach(array_keys(config('auth.guards')) as $guard){
            if(Auth::guard($guard)->check()) return $guard;
        }
        return null;
    }
}


?>
