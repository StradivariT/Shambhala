<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UsersController extends Controller {
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials))
                return response()->json('Invalid credentials', 401);
        } catch(JWTException $error) {
            //TODO: Log $error
            return response()->json('Unexpected auth error', 500);
        }

        return response()->json($token, 200);
    }
}