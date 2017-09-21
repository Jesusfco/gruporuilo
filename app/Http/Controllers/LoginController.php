<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
//use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;

use Tymon\JWTAuth\Middleware\RefreshToken;

class LoginController extends Controller
{

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'error' => 'Credenciales Invalidas'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token!'
            ], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ],200);
    }

    public function checkAuth(){

        $this->middleware('user1');

        $user = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'user' => $user,
        ]);

    }


}
