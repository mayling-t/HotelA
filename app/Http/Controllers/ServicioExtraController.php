<?php

namespace App\Http\Controllers;

use App\Models\ServicioExtra;
use Illuminate\Http\Request;

class ServicioExtraController extends Controller
{
    // Listar servicios extras
    public function index()
    {
        return response()->json(ServicioExtra::all(), 200);
    }

    // Crear servicio extra
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
        ]);

        $servicio = ServicioExtra::create($data);

        return response()->json(['mensaje' => 'Servicio extra creado', 'servicio' => $servicio], 201);
    }

    // Actualizar servicio extra
    public function update(Request $request, $id)
    {
        $servicio = ServicioExtra::findOrFail($id);

        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|required|numeric',
        ]);

        $servicio->update($data);

        return response()->json(['mensaje' => 'Servicio extra actualizado', 'servicio' => $servicio], 200);
    }
}
