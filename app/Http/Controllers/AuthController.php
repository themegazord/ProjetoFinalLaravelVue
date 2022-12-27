<?php

namespace App\Http\Controllers;

use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function login(Request $request) {

        //autenticacao do usuario (email e senha)

        $credenciais = $request->all();

        // retorna um JWT
        if(!Auth::attempt($credenciais)) {
            return response()->json(['error' => 'Email ou senha inválido'], 403);
        }

        $user = Auth::user();
        $token = $user->createToken('JWT');

        return response()->json(['token' => $token->plainTextToken], 200);
    }
    public function logout(Request $request) {
        if(!Auth::check()) {
            return response()->json(['error' => 'Token invalido'], 401);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json(['msg' => 'Usuário deslogado com sucesso'], 200);

    }
    public function refresh() {
        if(!Auth::check()) {
            return response()->json(['error' => 'Token invalido'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('Refresh JWT', ['*'], now()->addMinutes(60));

        return response()->json(['refresh_token' => $token->plainTextToken]);
    }

    public function me() {
        return Auth::user();
    }

    public function getLoginRedirect() {
        return response()->json(['error' => 'Usuário não autenticado'], 401);
    }
}
