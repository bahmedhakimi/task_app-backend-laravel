<?php

namespace App\Http\Controllers;

use App\Models\state_task;
use Illuminate\Http\Request;
use App\Models\User;


class statetaskcontroller extends Controller
{

    //creat_task_state
    //POST
    //url http://127.0.0.1:8000/api/creat_task_state

    public function creat_state_task(Request $request)
    {


        //create data
        $state_task = new state_task;
        $id = auth()->user()->id;
        $state_task->user_id = auth()->user()->id;
        $state = User::find($id)->state_task;
        $state_task->inProgrss =  empty($state->inProgrss) ? 0 : $state_task->inProgrss ;
        $state_task->done = empty($state->done) ? 0 : $state_task->done;


        $state_task->save();

        //sen responce
        return response()->json([
            "status" => 1,
            "message" => "task state created sucessfully"
        ]);
    }

    //creat_task_state
    //GET
    //url http://127.0.0.1:8000/api/state_task
    public function state_task()
    {
        $id = auth()->user()->id;
        $state_task = User::find($id)->state_task;
        return response()->json([
            'status' => 1, 'message' => 'tasks', 'body' => $state_task
        ], 200);
    }


    //creat_task_state
    //PUT
    //url http://127.0.0.1:8000/api/update_state
    /*   public function update_state($id)
    {
        if (state_task::where('user_id', $id)->exists()) {
            $state_task = state_task::find($id);
            $state_task->inProgrss =  $state_task->inProgrss - 1;
            $state_task->done =  $state_task->done  + 1;

            $state_task->save();
            return response()->json([
                'status' => 1, 'message' => 'update task state'
            ]);
        } else {
            return response()->json(['status' => 0, 'message' => 'task state not found']);
        }
    } */

    /*    public function update_task_deleted($id)
    {
        if (state_task::where('id', $id)->exists()) {

            $state_task = state_task::find($id);
            $state_task->done = $state_task->done - 1;
            $state_task->inProgrss = $state_task->inProgrss - 1;
            $state_task->save();
            return response()->json([
                'status' => 1, 'message' => 'task state delete'
            ]);
        } else {
            return response()->json(['status' => 0, 'message' => 'task_state not found ']);
        }
    } */
}
