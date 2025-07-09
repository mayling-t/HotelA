<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar datos
        $credentials = $request->only('email', 'password');

        // Buscar al usuario
        $usuario = Usuario::where('email', $credentials['email'])->first();

        // Si no existe o contraseña incorrecta
        if (!$usuario || !Hash::check($credentials['password'], $usuario->password)) {
            return response()->json(['mensaje' => 'Credenciales inválidas'], 401);
        }

        // Crear token con Sanctum
        try {
            $token = $usuario->createToken('token')->plainTextToken;
        } catch (\Throwable $e) {
            return response()->json(['mensaje' => 'Error al generar el token', 'error' => $e->getMessage()], 500);
        }

        // Buscar cliente relacionado (user_id)
        $cliente = Cliente::where('user_id', $usuario->id)->first();

        return response()->json([
            'usuario' => $usuario,
            'cliente' => $cliente,
            'token' => $token
        ]);
    }
}
