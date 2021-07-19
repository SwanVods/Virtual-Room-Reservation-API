<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class UserController extends Controller
{
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
            return response()->json($validation->errors(),202);
        }
        $alldata = $request->all();
        // dd($alldata);
        $alldata['password'] = bcrypt($alldata['password']);
        $user = User::create($alldata);
        // dd($user);
        
        $resArr = [];
        
        $resArr['token']=$user->createToken('api-application')->accessToken;
        $resArr['name']=$user->name;

        return response()->json($resArr,200);
    }
    public function login(Request $request)
    {
        if(Auth::attempt([
            'email' => $request->email, 
            'password' => $request->password
        ])){
            $user =Auth::user();
            $resArr = [];
            $resArr['token']=$user->createToken('api-application')->accessToken;
            $resArr['name']=$user->name;
            return response()->json($resArr,200);
        }else{
            return response()->json(['error'=>'Unauthorized Access'],203);
        }
    }
}
