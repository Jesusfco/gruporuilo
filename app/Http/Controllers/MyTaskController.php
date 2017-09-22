<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\Task;
use GrupoRuilo\User;
use JWTAuth;
use GrupoRuilo\TaskProgress;

class MyTaskController extends Controller
{
    public function __construct(){ $this->middleware('user1'); }

    public function getTasks(Request $request){
        $auth = JWTAuth::parseToken()->authenticate();
        $tasks = Task::where('userId','=', $auth->id)->orderBy('id', 'desc')->get();

        foreach($tasks as $task) {
            $user = User::find($task->userId);
            $task->userName = $user->name;
            $creator = User::find($task->createBy);
            $task->createByName = $creator->name;

            $progress = TaskProgress::where('taskId', $task->id)->orderBy('id','desc')->first();
            if($progress != NULL) $task->last_progress = $progress->created_at;

        }

        return response()->json(['tasks' => $tasks]);
    }


    public function createProgress(Request $request){
        $progress =  new TaskProgress();
        $auth = JWTAuth::parseToken()->authenticate();

        $progress->message = $request->message;
        $progress->createBy = $auth->id;
        $progress->taskId = $request->taskId;
        $progress->save();

        return response()->json(['progress' => $progress]);
    }
}
