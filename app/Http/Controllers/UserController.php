<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

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

    public function registration(StoreRegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);

        try {
            $user = User::create($validated);
            $user->assignRole('user');
            $success['token'] = $user->createToken('api-application')->accessToken;
            return $this->sendResponse($success, 'User register successfully.');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], Response::HTTP_NOT_ACCEPTABLE);
        }

 
    }

    public function login(Request $request)
    {
        $logged_in = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]); 
        
        if($logged_in) {
            $user = Auth::user();
            // @ts-ignore
            $success['token'] = $user->createToken('api-application')->accessToken;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('unable to authenticate', [], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout()
    { 
        $check = Auth::check();
        if ($check) {
            Auth::user()->token()->revoke();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 401);
    }

    public function details()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function show($id)
    {
        $res = [
            'data' => User::find($id)
        ];
        return response()->json($res, 200);
    }
}