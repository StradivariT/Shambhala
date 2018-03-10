<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UsersController extends Controller {
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials))
                return response()->json(['message' => 'Invalid credentials'], 401);
        } catch(JWTException $error) {
            return response()->json(['message' => 'Unexpected auth error', 'error' => $error], 500);
        }

        return response()->json(['token' => $token], 200);
    }
}