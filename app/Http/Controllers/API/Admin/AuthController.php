<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //login to program
    public function login(Request $request)
    {
        // Check if email and password are provided
        if(!$request->filled('email') && !$request->filled('password')){
            return response()->json(['message' => 'Email and Password are required'], 400);
        }else if (!$request->filled('email')) {
            return response()->json(['message' => 'Email is required'], 400);
        }else if (!$request->filled('password')) {
            return response()->json(['message' => 'Password is required'], 400);
        }
        // Validation rules
        $rules = [
            "email" => "required|exists:users,Email",
            "password" => "required",
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid Email or Password', 'success' => false], 400);
        }
        // Find the user by email
        $user = User::where('Email', $request->input('email'))->first();
        if ($user) {
            // Check if the provided password matches the encrypt password
            if ($request->input('password') === decrypt($user->Password)) {
                // Generate JWT token
                $token = JWTAuth::fromUser($user);
                $user->api_token = $token;
                return response()->json(['user' => $user, 'success' => true], 200);
            } else {
                return response()->json(['message' => 'Invalid Email or Password', 'success' => false], 401);
            }
        } else {
            return response()->json(['message' => 'User not found', 'success' => false], 404);
        }
    }


    //logout from the program
    public function logout(Request $request){
        $token = $request->header('Authorization');
        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            try {
                JWTAuth::setToken($token)->invalidate();
                return response()->json(['message' => 'Logout successfully', 'success' => true], 200);
            } catch (Exception $e) {
                return response()->json(['message' => 'Invalid token or already logged out', 'success' => false]);
            }
        } else {
            return response()->json(['message' => 'Token not provided', 'success' => false]);
        }
    }
}

