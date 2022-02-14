<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\statetaskcontroller;
use App\Http\Controllers\taskscontroller;
use App\Http\Controllers\usercontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::group(["middleware" => ["auth:api"]], function () {

    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);
    //task route
    Route::get('tasks', [taskscontroller::class, 'show_tasks']);
    Route::post('add_task', [taskscontroller::class, 'creat_task']);
    Route::put('update_task/{id}', [taskscontroller::class, 'update_task']);
    Route::get('single_task/{id}', [taskscontroller::class, 'show_single_tasks']);
    Route::delete('delet_tasks/{id}/{iscomplited}', [taskscontroller::class, 'delete_task']);

    //complite task and update state of task Done & in progress
    Route::put('complited_tasks/{id}', [taskscontroller::class, 'complited_task']);
    //state task route
    Route::get('creat_state_task', [statetaskcontroller::class, 'creat_state_task']);
    Route::get('state_task', [statetaskcontroller::class, 'state_task']);

    //user route
    Route::get('username', [usercontroller::class, 'get_user']);

});

Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});
