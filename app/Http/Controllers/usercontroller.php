<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class usercontroller extends Controller
{
    public function get_user()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        return response()->json([
            'status' => 1, 'message' => 'tasks', 'body' => $user->name
        ], 200);
    }
}
