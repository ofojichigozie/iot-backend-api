<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class UserController extends Controller
{
    // public function addSingleUser(){
    //     $user = User::create(['contact' => '08135439547', 'email' => 'ofojichigozie@gmail.com', 'password' => Hash::make('user@12345')]);
    //     return response()->json($user);
    // }

    public function authenticateUser(Request $request){
        //Get the user authentication details
        $email = $request->input('email');
        $password = $request->input('password');

        //Validate user inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            $data = [
                'status' => 'EMPTY_INPUT',
                'message' => 'Some fields are empty',
                'data' => []
            ];
            return response()->json($data);
        }

        if(Auth::attempt(['email' => $email, 'password' => $password])){
            
            //Get the details of the user
            $user = User::where('email', $email)->first();

            //Prepare data to return
            $data = [
                'status' => 'AUTH_SUCCEED',
                'message' => 'The user is authorized',
                'user' => $user
            ];

            //Return the response
            return response()->json($data);

        }else{
            //Prepare data to return
            $data = [
                'status' => 'AUTH_FAILED',
                'message' => 'The user is unauthorized',
                'user' => []
            ];

            //Return the response
            return response()->json($data);
        }
    }
}
