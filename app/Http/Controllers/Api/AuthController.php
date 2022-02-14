<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use  HasApiTokens;


    public function register(Request $request)
    {
        //validatoin
        $request->validate([
            'email' => 'required|email|unique:User',
            'password' => 'required|confirmed',
            'name' => 'required',

        ]);
        //create user
        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->name = $request->name;

        $user->save();

        //reponse
        return response()->json([
            'status' => 1,
            'message' => 'user created succesfully',
        ]);
    }
    public function login(Request $request)
    {
        //validation
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;
                return response()->json(
                    [
                        'status' => 1,
                        'message' => 'logged in successfully ',
                        'access_token' => $token,
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => 0,
                        'message' => 'incorrect password'
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    'status' => 0,
                    'message' => 'incorrect email'
                ]
            );
        }
    }
    public function profile()
    {
        return response()->json([
            'statu' => 1,
            'message' => 'profile',
            'data' => auth()->user()
        ]);
    }
    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return response()->json([
            'status'=>1,
            'message'=>'logout successfully',
        ]);
    }
}
