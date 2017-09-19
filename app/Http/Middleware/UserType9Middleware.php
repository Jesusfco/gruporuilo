<?php

namespace GrupoRuilo\Http\Middleware;

use Closure;
use GrupoRuilo\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserType9Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['message' => 'User not found'], 404);
        }else if(! Auth::user()->active == 1) {
            return response()->json(['message' => 'User disable'], 403);
        }else if(Auth::user()->type === 9 ||  Auth::user()->type === 10) {
            return $next($request);
        }
        else {
            return response()->json(['message' => 'User dont have credentials'], 402);
        }
    }
}
