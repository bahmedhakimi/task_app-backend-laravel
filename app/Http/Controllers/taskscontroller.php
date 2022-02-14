<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tasks;
use App\Models\User;
use App\Models\state_task;
use Tasks as GlobalTasks;

class taskscontroller extends Controller
{
    //creat_task
    //POST
    //url http://127.0.0.1:8000/api/creat_task
    public function creat_task(Request $request)
    {
        //validation
        $request->validate([
            "title" => 'required|',
            "note" => 'required|',
            "date" => 'required|',
            "startTime" => 'required|',
            "isCompleted" => 'required|',
            "remind" => 'required|',
            "repeat" => 'required|',
            "color" => 'required|',



        ]);
        //create data
        $task = new tasks;
        $task->user_id = auth()->user()->id;
        $task->title = $request->title;
        $task->note = $request->note;
        $task->date = $request->date;
        $task->startTime = $request->startTime;
        $task->isCompleted = $request->isCompleted;
        $task->remind = $request->remind;
        $task->repeat = $request->repeat;
        $task->color = $request->color;

        $user_id = auth()->user()->id;
        if (state_task::where('user_id', $user_id)->exists()) {

            $state_task = User::find($user_id)->state_task;


            $state_task->inProgrss =  $state_task->inProgrss + 1;
            $state_task->save();
            $task->save();

            return response()->json([
                "status" => 1,
                "message" => "task created sucessfully"
            ]);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "User Not Found"
            ]);
        }



        //sen responce

    }

    //show_tasks
    //GET
    //url http://127.0.0.1:8000/api/show_tasks
    public function show_tasks()
    {
        $id = auth()->user()->id;
        $task = User::find($id)->task;
        return response()->json([
            'status' => 1, 'message' => 'tasks', 'body' => $task
        ], 200);
    }

    //show_single_tasks
    //GET
    //url http://127.0.0.1:8000/api/show_single_tasks/ID
    public function show_single_tasks($id)
    {
        $task = tasks::where('id', $id);
        if ($task->exists()) {
            return response()->json([
                'status' => 1, 'message' => 'task exist', 'body' => $task->first()
            ], 200);
        } else {
            return response()->json(['status' => 0, 'message' => 'task does not exist'], 404);
        }
    }


    //update_task
    //PUT
    //url http://127.0.0.1:8000/api/update_task/ID
    public function update_task(Request $request, $id)
    {
        if (tasks::where('id', $id)->exists()) {
            $task = tasks::find($id);
            $task->title = empty($request->title) ? $task->title : $request->title;
            $task->note = empty($request->note) ? $task->note : $request->note;
            $task->date = empty($request->date) ? $task->date : $request->date;
            $task->startTime = empty($request->startTime) ? $task->startTime : $request->startTime;
            $task->isCompleted = empty($request->isCompleted) ? $task->isCompleted : $request->isCompleted;
            $task->remind = empty($request->remind) ? $task->remind : $request->remind;
            $task->repeat = empty($request->repeat) ? $task->repeat : $request->repeat;
            $task->color = empty($request->color) ? $task->color : $request->color;
            $task->save();
            return response()->json([
                'status' => 1, 'message' => 'update complited'
            ]);
        } else {
            return response()->json(['status' => 0, 'message' => 'task not found ']);
        }
    }

    //delet_task
    //DELETE
    //url http://127.0.0.1:8000/api/delete_task
    public function delete_task($id, $iscomplited)
    {
        if (tasks::where('id', $id)->exists()) {

            $task = tasks::find($id);
            $user_id = $task->user_id;
            if (state_task::where('user_id', $user_id)->exists()) {

                $state_task = User::find($user_id)->state_task;


                if ($iscomplited == '1') {
                    $state_task->done =  $state_task->done - 1;
                    $state_task->inProgrss =  $state_task->inProgrss ;
                } else {
                    $state_task->done =  $state_task->done;
                    $state_task->inProgrss =  $state_task->inProgrss - 1;
                }
                $state_task->save();
                $task->delete();

                return response()->json([
                    'status' => 1, 'message' => 'task delete'
                ]);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'task not found ']);
        };
    }

    //complited_task
    //PUT
    //url http://127.0.0.1:8000/api/update_task/ID
    public function complited_task($id)
    {
        if (tasks::where('id', $id)->exists()) {
            $task = tasks::find($id);
            $user_id = $task->user_id;
            //
            if (state_task::where('user_id', $user_id)->exists()) {
                $task->isCompleted = '1';
                $state_task = User::find($user_id)->state_task;

                $state_task->done = $state_task->done + 1;
                $state_task->inProgrss =  $state_task->inProgrss - 1;
                $task->save();
                $state_task->save();

                return response()->json([
                    'status' => 1, 'message' => 'task complited'
                ]);
            } else {
                return response()->json(['status' => 0, 'message' => 'failed ']);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'failed ']);
        }
    }
}
