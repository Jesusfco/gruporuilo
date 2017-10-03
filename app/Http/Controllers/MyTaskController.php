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
        $tasks = Task::where([
            ['userId', $auth->id],
            ['title','LIKE', '%'. $request->toSearch .'%']])
            ->orderBy('status', 'asc')
            ->orderBy('id', $request->id)->paginate($request->paginate);

        foreach($tasks as $task) {

//          Asignar nombres de creador y de editor referente a los usuarios
            $user = User::find($task->userId);
            $task->userName = $user->name;
            $creator = User::find($task->createBy);
            $task->createByName = $creator->name;

//Asignar ultimo progreso, ultima fecha de entrada de ultimo progreso, si ya fue leido el ultimo progreso

            $progress = TaskProgress::where('taskId', $task->id)->orderBy('id','desc')->first();
            if($progress != NULL) {
                $task->last_progress = $progress->created_at;
                if($progress->read  == 0){
                    $task->toRead = 1;
                } else { $task->toRead = 0;}

                if($progress->progress == NULL){
                    $task->progress = $this->checkPercentaje( TaskProgress::where('taskId', $task->id)->orderBy('id','desc')->select('id','progress')->get());
                } else {
                    $task->progress = $progress->progress;
                }
            }
            else {
                $task->progress  = 0;
                $task->toRead = 0;
            }

        }

        return response()->json(['tasks' => $tasks]);
    }

    public function getProgresses($id){
        return response()->json(['progress' => TaskProgress::where('taskId', $id)->orderBy('id','desc')->get()]);
    }

    public function createProgress(Request $request){
        $progress =  new TaskProgress();
        $auth = JWTAuth::parseToken()->authenticate();

        $progress->message = $request->message;
        $progress->createBy = $auth->id;
        $progress->taskId = $request->taskId;
        $progress->save();

        $progress->createByName = (User::find($progress->createBy))->name;

        return response()->json(['progress' => $progress]);
    }

    public function checkPercentaje($progresses){
        foreach($progresses as $progress){
            if($progress->progress != NULL)  return $progress->progress;
        }

        return 0;
    }
}
