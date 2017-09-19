<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('visiflex/user', 'UserController@getMyUser');

Route::get('visiflex/users', 'UserController@getUsers');
Route::post('visiflex/user/createUser', 'UserController@createUser');
Route::put('visiflex/user/{id}', 'UserController@editUser');


Route::post('visiflex/login', 'LoginController@signin');
Route::get ('visiflex/login/check', 'LoginController@checkAuth');

Route::post('visiflex/user/find', 'UserController@findUser');
Route::post('visiflex/user/findId', 'UserController@findUserId');
Route::post('visiflex/user/sugest', 'UserController@sugestUsers');

Route::post('visiflex/task/create', 'TaskController@create');