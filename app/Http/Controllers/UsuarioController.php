<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Registrar usuario (cliente o recepcionista)
    public function index()
    {
        return Usuario::all();
    }
    public function store(Request $request)
{
    $usuario = Usuario::create($request->all());
    return response()->json($usuario, 201);
}

    public function registro(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:cliente,recepcionista',
        ]);

        $data['password'] = Hash::make($data['password']);

        $usuario = Usuario::create($data);

        return response()->json(['usuario' => $usuario], 201);
    }

    // Login
   public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    $usuario = Usuario::where('email', $credentials['email'])->first();

    if (!$usuario || !Hash::check($credentials['password'], $usuario->password)) {
        return response()->json(['mensaje' => 'Credenciales inválidas'], 401);
    }

    $cliente = \App\Models\Cliente::where('user_id', $usuario->id)->first();

    return response()->json([
        'mensaje' => 'Login exitoso',
        'usuario' => $usuario,
        'cliente_id' => $cliente ? $cliente->id : null,
        'token' => $usuario->createToken('auth_token')->plainTextToken,
    ]);
}


    // Logout
    public function logout(Request $request)
{
    if (!$request->user()) {
        return response()->json(['mensaje' => 'Usuario no autenticado'], 401);
    }

    $request->user()->currentAccessToken()->delete();

    return response()->json(['mensaje' => 'Sesión cerrada'], 200);
}
}
