<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1])) {
            return response()->json(Auth::id());
        }
    }

    public function checkAuth(){
        if(Auth::user()){
            return response()->json(Auth::id());
        }

        else{
            return response()->json(Auth::id());
        }
    }
}