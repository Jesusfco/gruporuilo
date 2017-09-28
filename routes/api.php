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

//CONTROLADOR PARA ACCESAR AL SISTEMA
Route::post('visiflex/login', 'LoginController@signin');
Route::get ('visiflex/login/check', 'LoginController@checkAuth');

//RUTAS DE AYUDA PARA LA MANIPULACION DE DATOS DE USUARIO
Route::post('visiflex/user/find', 'UserController@findUser');
Route::post('visiflex/user/findId', 'UserController@findUserId');
Route::post('visiflex/user/sugest', 'UserController@sugestUsers');

Route::post('visiflex/user/mailExist', 'UserController@mailExist');
Route::post('visiflex/user/nameExist', 'UserController@nameExist');

//CONTROLADOR PARA TAREAS

Route::post('visiflex/task/create', 'TaskController@create');
Route::get('visiflex/task/getTasks', 'TaskController@getTasks');
Route::put('visiflex/task/update', 'TaskController@update');
Route::delete('visiflex/task/delete/{id}', 'TaskController@delete');

Route::get('visiflex/task/progress/{id}' , 'TaskController@getProgresses');
Route::put('visiflex/task/updateProgress', 'TaskController@updateProgress');

//MIS TAREAS RUTAS

Route::get('visiflex/myTasks', 'MyTaskController@getTasks');
Route::get('visiflex/myTask/progress/{id}' , 'MyTaskController@getProgresses');
Route::post('visiflex/myTask/progress/create', 'MyTaskController@createProgress');