<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }
    
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    public function registration(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password',
            'phone'=>'required',
        ]);
        if($validation->fails()){
            return $this->sendError('Validation Error.', $validation->errors());
        }
        $allData = $request->all();
        $allData['password'] = bcrypt($allData['password']);
        $user = User::create($allData);
        $user->assignRole('user');
        $success['token']=$user->createToken('api-application')->accessToken;
        return $this->sendResponse($success, 'User register successfully.');
    }
    public function login(Request $request)
    {
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])){
            $user =Auth::user();
            $success['token']=$user->createToken('api-application')->accessToken;
            return $this->sendResponse($success, 'User login successfully.');
        }else{
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
        }
    }

    public function logoutApi()
{ 
    // dd(Auth::check());
    if (Auth::check()) {
       $success = Auth::user()->AauthAcessToken()->delete();
       dd($success);
       $res = [
        'message' => 'Logout succesfully',
        'data' => $success,
        // 'user' => $user
    ];
       return response()->json($res);
    }
}
    public function details()
    {
        $success = auth()->user();
        return $this->sendResponse($success, 'User login successfully.');
    }
}
