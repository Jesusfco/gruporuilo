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

        if($request->type == false) {
            $tasks = Task::where([
                ['userId', '<>', $auth->id],
                ['title', 'LIKE', '%' . $request->toSearch . '%']])
                ->orderBy('status', 'asc')
                ->orderBy('id', $request->id)->paginate($request->paginate);
        }
        else if($request->type == true) {

         $user = User::where('name', $request->toSearch )->first();

            $tasks = Task::where([
//                ['userId', '<>', $auth->id],
                ['userId', '=', $user->id]])
                ->orderBy('status', 'asc')
                ->orderBy('id', $request->id)->paginate($request->paginate);
        }

        foreach($tasks as $task) {

//          Asignar nombres de creador y de editor referente a los usuarios

            $user = User::find($task->userId);
            $task->userName = $user->name;
            $creator = User::find($task->createBy);
            $task->createByName = $creator->name;

//            $breaks = array("<br />","<br>","<br/>");
//            $task->descriptionEdit = str_ireplace($breaks, "\r\n", $task->description);


//Asignar ultimo progreso, ultima fecha de entrada de ultimo progreso, si ya fue leido el ultimo progreso

            $progress = TaskProgress::where('taskId', $task->id)->orderBy('id','desc')->first();

            if($progress != NULL) {

                $task->last_progress = $progress->created_at;

                if($progress->read  == 0){

                    $task->toRead = 1;

                } else { $task->toRead = 0;}

                if($progress->progress == NULL){

                    $task->progress = $this->checkPercentaje(
                                        TaskProgress::where('taskId', $task->id)
                                            ->orderBy('id','desc')
                                            ->select('id','progress')
                                            ->get());

                } else {

                    $task->progress = $progress->progress;

                }
            }
            else {
                $task->progress  = 0;
                $task->toRead = 0;
            }

            if($task->status == 1) $task->progress = 100;



        }

        return response()->json(['tasks' => $tasks]);
    }

    public function create(Request $request) {
        $auth = JWTAuth::parseToken()->authenticate();

//        $texto = rawurlencode($request->description);
//        $texto = rawurldecode(str_replace("%0D%0A","<br>",$texto));

        $task = new Task();
        $task->title =  $request->title;
        $task->userId = $request->userId;
        $task->description = $request->description;
        $task->level = $request->level;
        $task->createBy = $auth->id;

        $task->save();

        $user = User::find($task->userId);
        $task->userName = $user->name;
        $creator = User::find($task->createBy);
        $task->createByName = $creator->name;



        return response()->json(['task' => $task]);

    }

    public function update(Request $request){

        $task = Task::find($request->id);
//
//        $texto = rawurlencode($request->descriptionEdit);
//        $texto = rawurldecode(str_replace("%0D%0A","<br>",$texto));

        $task->title =  $request->title;
        $task->userId = $request->userId;
        $task->description = $request->description;
        $task->level = $request->level;

        $task->save();

        $user = User::find($task->userId);
        $task->userName = $user->name;

//        $breaks = array("<br />","<br>","<br/>");
//        $task->descriptionEdit = str_ireplace($breaks, "\r\n", $task->description);

        return response()->json(['task' => $task]);

    }

    public function delete($id) {
        $task = Task::find($id);
        TaskProgress::where('taskId', $task->id)->delete();
        Task::destroy($id);

        return response()->json(['message' => "Task and Progresses Task deleted"]);

    }


    public function getProgresses($id){
        $progresses = TaskProgress::where('taskId', $id)->orderBy('id','desc')->get();
        foreach($progresses as $x){
            $x->createByName = (User::find($x->createBy))->name;
        }
        return response()->json(['progress' => $progresses]);
    }

    public function updateProgress(Request $request){
        $progress = TaskProgress::find($request->id);

        $auth = JWTAuth::parseToken()->authenticate();

        $progress->progress = $request->progress;
        $progress->observation = $request->observation;
        $progress->read = 1;
        $progress->modifyBy = $auth->id;
        $progress->readTime = date_create();

        $progress->save();

        $task = Task::find($progress->taskId);
        if($progress->progress == 100) {
            $task->status = 1;
            $task->save();
        } else {
            if($task->status == 1) {
                $task->status = 0;
                $task->save();
            }
        }

        $progress->createByName = (User::find($progress->createBy))->name;

        $progress->edit = false;
        return response()->json(['progress' => $progress]);
    }

    public function checkPercentaje($progresses){
        foreach($progresses as $progress){
            if($progress->progress !== NULL)  return $progress->progress;
        }

        return 0;
    }
}
