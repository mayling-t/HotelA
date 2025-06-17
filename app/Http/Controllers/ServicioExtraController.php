<?php

namespace App\Http\Controllers;

use App\Models\ServicioExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importa el Facade DB

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
    public function asignar(Request $request)
{
    $data = $request->validate([
        'idReserva' => 'required|integer|exists:reservas,id',
        'idServicio' => 'required|integer|exists:servicio_extras,id',
    ]);

    // Asumiendo que la tabla pivot 'reserva_servicio_extra' existe:
    DB::table('reserva_servicio_extra')->insert([
        'reserva_id' => $data['idReserva'],
        'servicio_extra_id' => $data['idServicio'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return response()->json(['mensaje' => 'Servicio asignado exitosamente'], 200);
}

}
