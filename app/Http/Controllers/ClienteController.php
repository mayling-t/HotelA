<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Listar clientes
    public function index()
    {
        return response()->json(Cliente::all(), 200);
        return Cliente::with('usuario')->get();

    }

    // Mostrar cliente por id
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente, 200);
    }

    // Crear cliente
     public function store(Request $request)
    {
        $data = $request->validate([
            // 'id' => 'required|integer|unique:clientes,id', // quitar
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'dni' => 'required|size:8|unique:clientes,dni',
            'celular' => 'required|string|max:15',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::create($data);

        return response()->json(['mensaje' => 'Cliente creado', 'cliente' => $cliente], 201);
    }

    // Actualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $data = $request->validate([
            'dni' => 'sometimes|required|size:8|unique:clientes,dni,' . $id,
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'celular' => 'sometimes|required|string|max:15',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($data);

        return response()->json(['mensaje' => 'Cliente actualizado', 'cliente' => $cliente], 200);
    }
    public function buscarPorDni($dni)
{
    $cliente = Cliente::where('dni', $dni)->first();

    if (!$cliente) {
        return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
    }

    return response()->json($cliente, 200);
}

}
