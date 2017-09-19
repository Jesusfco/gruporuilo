<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\Task;
use GrupoRuilo\User;

class TaskController extends Controller
{
    public function __construct(){ $this->middleware('user9'); }

    public function store(Request $request) {
        $task = new Task();

        $auth = JWTAuth::parseToken()->authenticate();

        $task->title =  $request->title;
        $task->userId = $request->userId;
        $task->description = $request->description;
        $task
        $task->createBy = $auth->id;

    }
}
