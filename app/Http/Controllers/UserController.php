<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'fcm_token'=>''
        ]);
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'fcm_token'=>''
        ]);
        $token=$user->createToken($user->name);
        $user->update([
            'fcm_token'=>$token->plainTextToken,
        ]);

        return response()->json(['message'=>'user has been created',
                'access_token'=>$token->plainTextToken,
            ]
      );

    }
    public function login(Request $request){
        $user=$request->validate([
            'name'=>'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials,true)) {
            $user=$request->user();
            return response()->json(['your access token is'=>$user->fcm_token]);

        }
        else{
            return response()->json(['error'=>'login failed ']);
        }



    }
}
