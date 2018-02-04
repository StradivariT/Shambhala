<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\User;

class UsersController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials))
                return response()->json(['error' => 'La información ingresada es incorrecta'], 401);
        } catch(JWTException $error) {
            return response()->json(['error' => 'Un error inesperado ocurrió al tratar de iniciar sesión, intenta de nuevo'], 500);
        }

        return response()->json(['token' => $token], 200);
    }
}
