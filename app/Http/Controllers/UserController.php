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


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
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
        // dd($request->phone);
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
        // dd($alldata);
        $allData['password'] = bcrypt($allData['password']);
        $user = User::create($allData);
        $user->assignRole('seller');
        // dd($user);

        $success = [];

        $success['token']=$user->createToken('api-application')->accessToken;
        // $success['name']=$user->name;
        // return response()->json(['token' => $success],200);
        return $this->sendResponse($success, 'User register successfully.');
    }
    public function login(Request $request)
    {
        if(Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])){
            $user =Auth::user();
            // $success = [];
            $success['token']=$user->createToken('api-application')->accessToken;
            // $success['name']=$user->name;
            return $this->sendResponse($success, 'User login successfully.');
            // return response()->json(['token' => $success],200);
        }else{
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
        }
    }
    public function details()
    {
        $success = auth()->user();
        return $this->sendResponse($success, 'User login successfully.');
        // return response()->json(['user'=> auth()->user()],200);
    }
}
