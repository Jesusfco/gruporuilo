<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\User;
use GrupoRuilo\Credential;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('user9');

    }
    public function getUsers( Request $request){
        $users = User::where('name','LIKE', '%'. $request->toSearch .'%')
            ->orderBy('name', 'asc')
            ->paginate($request->paginate);

        return response()->json($users);
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
        $user =  User::where([
                    ['name', 'LIKE', $request->name . '%'],
                    ['active',1]
                ])->first();

        return response()->json(['id' => $user->id]);
    }

    public function sugestUsers(Request $request){
        $auth = JWTAuth::parseToken()->authenticate();

        if($auth->type == 10) {
            $users = User::where([
                ['name', 'LIKE', '%' . $request->name . '%'],
                ['active', 1],
                ['type', '<',10]
            ])->select('name','id')->get();
        } else {
            $users = User::where([
                ['name', 'LIKE', '%'. $request->name . '%'],
                ['active', 1],
                ['type', '<',9]
            ])->select('name','id')->get();
        }


        return response()->json([ 'users' => $users]);
    }

    public function mailExist(Request $request) {
        $user = User::where('email','=', $request->email )->first();
        if($user !== NULL){
            return response()->json(1);
        }

        return response()->json(0);
    }

    public function nameExist(Request $request) {
        $user = User::where('name', $request->name )->first();

        if($user !== NULL){
            return response()->json(['message' => 'asignado'], 402);
        }

        return response()->json(['uniqueName' => 'libre']);
    }

}
