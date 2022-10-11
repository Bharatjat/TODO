<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// model
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Events\TodoCreatedOrUpdated;

class TaskController extends Controller
{
    public function create(Request $request){
        $loginUser = Auth::User();
        $data = $request->all();

        // checking if task is empty
        if (empty($data['task'])) {
            return [
                'status' => 400,
                'message' => 'Given task is empty!'
            ];
        }

        $task = new Task();
        $task->description = $data['task'];
        $task->user_id = $loginUser->id;
        $task->save();

        broadcast(new TodoCreatedOrUpdated());

        return [
            'status' => 200,
            'message' => 'Tasked added successfully',
            'data' => $this->allTask($loginUser->id, $data['filter']== 'all' ? null : $data['filter']),
        ];

    }

    public function allTask($id, $active = '1'){
        if ($active != null) {
            return DB::table('users')->join('Tasks', 'Tasks.user_id', '=', 'users.id')->where(['users.id' => $id, 'Tasks.active' => (string)$active])->get(['Tasks.*', 'users.name']);
        }
        return DB::table('users')->where('users.id', '=', $id)->where('Tasks.active', '!=', '0')->join('Tasks', 'Tasks.user_id', '=', 'users.id')->get(['Tasks.*', 'users.name']);

    }

    public function show(Request $request){
        $data = $request->all();
        if ($data['filter'] == 'all') {
            return [
                'status' => 200,
                'message' => 'all result fetching successfully!',
                'data' => $this->allTask(Auth::id(), null)
            ];
        }
        return [
            'status' => 200,
            'message' => 'active result fetching successfully!',
            'data' => $this->allTask(Auth::id(), $data['filter'])
        ];
    }

    public function complate(Request $request){
        $data = $request->all();

        try {
            Task::where('id',$data['id'])->update(['active' => (string)$data['val']]);
            $status = 200;
            $message = 'update successfully';
        } catch (\Throwable $th) {
            $status = 401;
            $message = $th->getMessage();
        }

        broadcast(new TodoCreatedOrUpdated());

        return [
            'status' => $status,
            'message' => $message
        ];

    }
}
