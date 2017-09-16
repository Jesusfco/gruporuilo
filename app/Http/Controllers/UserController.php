<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\User;
use GrupoRuilo\Credential;

class UserController extends Controller
{
    public function getMyUser(){

        return response()->json('que pedo');

    }

    public function createUser(Request $request){
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->type = $request->type;
        $user->phone = $request->phone;
        $user->enterprise = $request->enterprise;

        $user->save();

        $credential = new credential();
        $credential->userId = $user->id;
        $credential->systemId = 1;

        $credential->save();

        return response()->json($user);
    }


    public function getUsers(){
        return response()->json(User::all());
    }
}
