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
        $auth = JWTAuth::parseToken()->authenticate();
        $tasks = Task::where('userId','<>', $auth->id)->orderBy('id', 'desc')->get();

        foreach($tasks as $task) {
            $user = User::find($task->userId);
            $task->userName = $user->name;
            $creator = User::find($task->createBy);
            $task->createByName = $creator->name;

            $progress = TaskProgress::where('taskId', $task->id)->orderBy('id','desc')->first();
            if($progress != NULL) $task->last_progress = $progress->created_at;

//            if(TaskProgress::where('',))
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
        Task::destroy($id);

        return response()->json(['message' => "Task and Progresses Task deleted"]);

    }


    public function getProgresses($id){
        return response()->json(['progress' => TaskProgress::where('taskId', $id)->orderBy('id','desc')->get()]);
    }

    public function updateProgress(Request $request){
        $progress = TaskProgress::find($request->id);

        $auth = JWTAuth::parseToken()->authenticate();

        $progress->progress = $request->progress;
        $progress->observation = $request->observation;
        $progress->read = 1;
        $progress->modifyBy = $auth->id;
//        $progress->readTime = TaskProgress::timestamp();

        $progress->save();

        $progress->edit = false;
        return response()->json(['progress' => $progress]);
    }
}
