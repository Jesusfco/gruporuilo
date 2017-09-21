<?php

namespace GrupoRuilo\Http\Controllers;

use Illuminate\Http\Request;
use GrupoRuilo\Task;
use GrupoRuilo\User;
use JWTAuth;
use GrupoRuilo\TaskProgress;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(){ $this->middleware('user9'); }

    public function getTasks(Request $request){
        $tasks = Task::all();

        foreach($tasks as $task) {
            $user = User::find($task->userId);
            $task->userName = $user->name;
            $creator = User::find($task->createBy);
            $task->createByName = $creator->name;
        }

        return response()->json(['tasks' => $tasks]);
    }

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

    public function update(Request $request){

        $task = Task::find($request->id);

        $task->title =  $request->title;
        $task->userId = $request->userId;
        $task->description = $request->description;
        $task->level = $request->level;

        $task->save();

        $user = User::find($task->userId);
        $task->userName = $user->name;

        return response()->json(['task' => $task]);

    }

    public function delete($id) {
        $task = Task::find($id);
        TaskProgress::where('taskId', $task->id)->delete();
//
//        $progresses = TaskProgress::where('taskId', $task->id)->get();
//        $progresses = TaskProgress::all();
//        foreach($progresses as $x){
//            TaskProgress::destroy($x->id);
//        }

        Task::destroy($id);

//        return response()->json(['message' => $request->all()]);

        return response()->json(['message' => "Task and Progresses Task deleted"]);

    }


}
