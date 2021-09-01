<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request  $request) {
        $credentials = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credentials);
        if (!$token) {
            return response()->json(['erro' => 'Usuário ou senha inválida!'], 403);
        }

        return response()->json(['token' => $token]);
    }

    public function logout() {
        auth('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    public function refresh() {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me() {
        return response()->json(auth()->user());
    }
}
