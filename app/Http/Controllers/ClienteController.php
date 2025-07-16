<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // ✅ Obtener todos los clientes
    public function index()
    {
        return response()->json(Cliente::all(), 200);
        // Nota: esta línea nunca se ejecutará porque la anterior hace return
        // return Cliente::with('usuario')->get();
    }

    // ✅ Mostrar un cliente por su ID
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }

    // ✅ Obtener cliente por ID de usuario
    public function obtenerPorUsuario($idUsuario)
    {
        $cliente = Cliente::where('user_id', $idUsuario)->first();

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }

    // ✅ Crear un nuevo cliente (cuando se registra el usuario)
    public function store(Request $request)
    {
       $data = $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'dni' => 'required|size:8|unique:clientes,dni',
            'email' => 'required|email|unique:clientes,email',
            'celular' => 'required',
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::create($data);

        return response()->json(['mensaje' => 'Cliente creado', 'cliente' => $cliente], 201);
    }

    // ✅ Actualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        $data = $request->validate([
            'dni' => 'sometimes|required|size:8|unique:clientes,dni,' . $id,
            'email' => 'sometimes|required|email|unique:clientes,email,' . $id,
            'celular' => 'sometimes|required|string|max:15',
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($data);

        return response()->json(['mensaje' => 'Cliente actualizado', 'cliente' => $cliente]);
    }

    // ✅ Eliminar cliente
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        $cliente->delete();

        return response()->json(['mensaje' => 'Cliente eliminado correctamente']);
    }

    // ✅ Buscar cliente por DNI
    public function buscarPorDni($dni)
    {
        $cliente = Cliente::where('dni', $dni)->first();

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }

    // ✅ Listar reservas de un cliente
    public function listarReservas($clienteId)
    {
        $cliente = Cliente::find($clienteId);

        if (!$cliente) {
            return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
        }

$reservas = $cliente->reservas()->with('habitacion')->get();

        return response()->json($reservas, 200);
    }
    
    public function buscarPorUsuario($id)
{
    $cliente = Cliente::where('user_id', $id)->first();

    if (!$cliente) {
        return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
    }

    return response()->json($cliente);
}

// En ClienteController.php
public function buscarClientePorDni(Request $request)
{
    $dni = $request->query('dni');
    $cliente = Cliente::where('dni', $dni)->first();

    if (!$cliente) {
        return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
    }

    return response()->json($cliente);
}


}
