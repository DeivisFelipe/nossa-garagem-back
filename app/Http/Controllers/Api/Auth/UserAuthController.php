<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class UserAuthController extends Controller
{
    public function __construct()
    {
    }

    public function login(LoginRequest $request)
    {
        $credencial = $request->only('email', 'password');
        try {
            if (!$token = $this->guard()->attempt($credencial)) {
                return response([
                    'message' => __('auth.failed'),
                    "errors" => [
                        "password" => [
                            __('auth.failed')
                        ]
                    ]
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' =>  __('error.token_create')], 500);
        }
        return $this->respondWithToken($token, $this->guard()->user())
            ->withCookie(cookie(
                'token',
                $token,
                config('jwt.refresh_ttl'),
                '/'
            ));
    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    protected function guard()
    {
        return Auth::guard('user');
    }

    public function refresh(Request $request)
    {
        if (!$request->hasCookie('token'))
            return response()
                ->json([
                    'message' => 'token not found',
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        $user = $this->guard()->user();
        $token = $this->guard()->refresh();
        return $this->respondWithToken($token, $user)
            ->withCookie(cookie(
                'token',
                $token,
                config('jwt.refresh_ttl'),
                '/'
            ));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        return response()->json(['message' => __('auth.logout')]);
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
}
