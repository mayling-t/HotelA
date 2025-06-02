<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Listar todas las reservas
    public function index(Request $request)
    {
        // Si hay filtro por fecha
        if ($request->has('fecha')) {
            $fecha = $request->query('fecha');

            $reservas = Reserva::where('fecha_inicio', '<=', $fecha)
                ->where('fecha_fin', '>=', $fecha)
                ->get();

            return response()->json($reservas, 200);
        }

        return response()->json(Reserva::all(), 200);
    }

    // Crear reserva
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cliente' => 'required|integer|exists:clientes,id',
            'id_habitacion' => 'required|integer|exists:habitaciones,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $reserva = Reserva::create($data);

        return response()->json(['mensaje' => 'Reserva confirmada', 'reserva' => $reserva], 201);
    }

    // Cancelar reserva
    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);

        $reserva->estado = 'cancelada';
        $reserva->save();

        return response()->json(['mensaje' => 'Reserva cancelada correctamente'], 200);
    }
}
