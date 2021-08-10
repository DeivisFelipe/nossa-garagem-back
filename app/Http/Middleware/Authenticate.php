<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Closure;


class Authenticate extends Middleware
{

    public function handle($request, Closure $next, ...$guards){
        return parent::handle($request,$next,...$guards);
    }

    protected function redirectTo($request)
    {
        return null;
    }

    protected function unauthenticated($request, array $guards)
    {
        $aguard = config('auth.guards');
        $aguard = array_diff_key($aguard,array_flip($guards));
        foreach ($aguard as $key => $value) {
            if(Auth::guard($key)->check()){
                abort(403);
            }
        }
        abort(401);
    }
}
