<?php

namespace App\Http\Controllers;

use App\Models\ReservaServicioExtra;
use Illuminate\Http\Request;

class ReservaServicioExtraController extends Controller
{
    // Listar relaciones reserva-servicio
    public function index()
    {
        return response()->json(ReservaServicioExtra::all(), 200);
    }

    // Crear relación reserva-servicio
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_reserva' => 'required|integer|exists:reservas,id',
            'id_servicio_extra' => 'required|integer|exists:servicios_extras,id',
        ]);

        $relacion = ReservaServicioExtra::create($data);

        return response()->json(['mensaje' => 'Servicio extra asignado a reserva', 'relacion' => $relacion], 201);
    }

    // Eliminar relación
    public function destroy($id)
    {
        $relacion = ReservaServicioExtra::findOrFail($id);
        $relacion->delete();

        return response()->json(['mensaje' => 'Relación eliminada'], 200);
    }
}
