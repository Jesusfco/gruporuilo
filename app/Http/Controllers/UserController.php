<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\User;
use GrupoRuilo\Credential;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('user9');

    }
    public function getUsers(){
        return response()->json(User::all());
    }

    public function createUser(Request $request){

        $auth = JWTAuth::parseToken()->authenticate();

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->type = $request->type;
        $user->phone = $request->phone;
        $user->enterprise = $request->enterprise;
        $this->createBy = $auth->id;

        $user->save();

        $credential = new credential();
        $credential->userId = $user->id;
        $credential->systemId = 1;

        $credential->save();

        return response()->json($user);
    }

    public function editUser(Request $request, $id){

        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->type = $request->type;
        $user->phone = $request->phone;
        $user->enterprise = $request->enterprise;

        if($request->password != NULL && $request->password !== ''){
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json(true);

    }

    public function findUser(Request $request){
        $user =  User::find($request->id);
        if($user->active == 0){
            return response()->json(['message' => 'User Inactive']);
        }
        return response()->json(['id' => $user->id]);
    }

    public function findUserId(Request $request){
        $user =  User::first()->where(['name', '=', $request->name],['active',1]);

        return response()->json(['id' => $user->id]);
    }

    public function sugestUsers(Request $request){
        $users = User::where(['name', 'LIKE', $request->name],
                            ['active','=', 1],
                            ['type', '<', '10']);

        return response()->json([users => $users],201);
    }

}
