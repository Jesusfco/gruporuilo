<?php

namespace GrupoRuilo\Http\Middleware;

use Closure;

class UserType10Middleware
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
        }else if(! Auth::user()->type == 10) {
            return response()->json(['message' => 'User dont have credentials'], 402);
        }
        return $next($request);

    }
}
