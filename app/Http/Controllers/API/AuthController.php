<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Session;

//models
use App\Models\User;

class AuthController extends Controller
{
    //for making register
    public function register(Request $request){

        // Validator
        $data = Validator::make($request->all(), [
            'name' => 'required | string',
            'email' => 'required | email | unique:users',
            'password' => 'required | min:6 | max:10',
            'cPassword' => 'required | same:password'
        ]);
        // 'sex' => ['required',Rule::in('f','m')]
        if($data->fails()){
            // return response()->json(['error'=>$data->errors()->all()], 200);
            return [
                'error' => $data->errors()->all(),
                'status' => 422
            ];
        }

        $validatedData = $data->validated();

        $record= new User();
        $record->name = $validatedData['name'];
        $record->email = $validatedData['email'];
        $record->password = Hash::make($validatedData['password'])  ;
        $record->save();
        
        return [
            'message' => 'User save successfully!',
            'status' => 200
        ];

    }

    public function login(Request $request){
        // Validator
        $data = Validator::make($request->all(), [
            'email' => 'required | email ',
            'password' => 'required | min:6 | max:10',
        ]);

        if($data->fails()){
            return [
                'error' => $data->errors()->all(),
                'status' => 422
            ];
        }
        if (Auth::attempt($data->validated())) {
            $success = 200;
            $message = 'Login successfully';
        }else{
            $success = 401;
            $message = 'crediential does not match!';
        }
        
        return [
            'status' => $success,
            'message' => $message
        ];
    }

    public function logout(){
        try {
            session::flush();
            $status = 200;
            $message = 'logout successfully';
        } catch (\Throwable $th) {
            $status = 401;
            $message = $th->getMessage();
        }
        return [
            'status' => $status,
            'message' => $message
        ];
    }
}
