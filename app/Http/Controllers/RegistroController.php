<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Cliente;

class RegistroController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|digits:8|unique:clientes,dni',
            'email' => 'required|email|unique:usuarios,email|unique:clientes,email',
            'password' => 'required|string|min:6|confirmed',
            'celular' => 'required|string|max:15',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'cliente',
        ]);

        $cliente = Cliente::create([
            'user_id' => $usuario->id, // 👈 muy importante
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'dni' => $request->dni,
            'email' => $request->email,
            'celular' => $request->celular,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        return response()->json([
            'usuario' => $usuario,
            'cliente' => $cliente,
        ], 201);
    }
}
