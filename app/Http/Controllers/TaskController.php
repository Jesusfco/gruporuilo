<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\Task;
use GrupoRuilo\User;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(){ $this->middleware('user9'); }

    public function create(Request $request) {
        $task = new Task();

        $auth = JWTAuth::parseToken()->authenticate();

        $task->title =  $request->title;
        $task->userId = $request->userId;
        $task->description = $request->description;
        $task->level = $request->level;

        $task->createBy = $auth->id;

        $task->save();

        return response()->json(['task' => $task]);

    }

    public function getTasks(Request $request){
        $tasks = Task::all();

        return response()->json(['tasks' => $tasks]);
    }
}
