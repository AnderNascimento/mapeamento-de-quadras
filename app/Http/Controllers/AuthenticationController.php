<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (! $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ], 200);
    }

    public function logout()
    {
        $logout = Auth::logout();

        if ($logout) {
            return response()->json([
                'message' => 'Logout bem sucedido',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Usuário não está logado',
            ], 404);
        }
    }
}
