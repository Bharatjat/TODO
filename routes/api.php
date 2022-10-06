<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// making new register
Route::post('/register', [AuthController::class, 'register']);

// handel login 
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){

    // for logout
    Route::get('/logout', [AuthController::class, 'logout']);

    // for add new todo
    Route::post('/addTodo', [TaskController::class, 'create']);

    // get todo list
    Route::post('/ShowTask', [TaskController::class, 'show']);

    // complete task
    Route::patch('/complate', [TaskController::class, 'complate']);
});