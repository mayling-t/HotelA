<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RegistroController extends Controller
{
    public function registrar(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => 'required|digits:8|unique:clientes,dni',
            'email' => 'required|email|unique:usuarios,email|unique:clientes,email',
            'celular' => 'required|string|max:15',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
            'password' => 'required|string|min:6', // Validación para la contraseña
        ]);
    
        try {
            DB::beginTransaction();
    
            $usuario = Usuario::create([
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']), // Encriptar la contraseña
                'rol' => 'cliente',
            ]);
    
            Cliente::create([
                'nombre' => $data['nombre'],
                'apellidos' => $data['apellidos'],
                'dni' => $data['dni'],
                'email' => $data['email'],
                'celular' => $data['celular'],
                'telefono' => $data['telefono'] ?? '',
                'direccion' => $data['direccion'] ?? '',
                //'user_id' => $usuario->id,

            ]);
    
            DB::commit();
    
            return response()->json([
                'mensaje' => 'Cliente y usuario registrados correctamente',
                'usuario' => $usuario
            ], 201);
    
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error al registrar cliente: " . $e->getMessage());
            return response()->json([
                'mensaje' => 'Error al registrar cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
