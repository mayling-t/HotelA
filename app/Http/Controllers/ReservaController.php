<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Habitacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Listar todas las reservas
    public function index(Request $request)
{
    $query = Reserva::with('cliente', 'habitacion');

    // Si quieres filtrar por fecha
    if ($request->has('fecha')) {
        $query->where('fecha_inicio', '<=', $request->fecha)
              ->where('fecha_fin', '>=', $request->fecha);
    }

    return response()->json($query->get());
}

    // Crear reserva
public function store(Request $request)
{
    $data = $request->validate([
        'id_cliente' => 'required|exists:clientes,id',
        'id_habitacion' => 'required|exists:habitaciones,id',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'estado' => 'nullable|in:confirmada,cancelada,finalizada',
    ]);

    // Establece estado predeterminado como 'confirmada' si no se pasa
    $data['estado'] = $data['estado'] ?? 'confirmada';

    // Crear la reserva
    $reserva = Reserva::create($data);

    // Si la reserva está confirmada, actualizar estado de habitación a 'ocupada'
    if ($data['estado'] === 'confirmada') {
        $habitacion = Habitacion::find($data['id_habitacion']);
        $habitacion->estado = 'ocupada';
        $habitacion->save();
    }

    return response()->json(['mensaje' => 'Reserva registrada y habitación actualizada', 'reserva' => $reserva], 201);
}

    // Cancelar reserva
    public function cancelar($id)
    {
        $reserva = Reserva::findOrFail($id);

        $reserva->estado = 'cancelada';
        $reserva->save();

        return response()->json(['mensaje' => 'Reserva cancelada correctamente'], 200);
    }
    
    public function reservasPorCliente($id)
    {
        $reservas = Reserva::with('habitacion')->where('id_cliente', $id)->get();
    
        if ($reservas->isEmpty()) {
            return response()->json([]);
        }
    
        $reservasTransformadas = $reservas->map(function ($reserva) {
            return [
                'id' => $reserva->id,
                'fecha_inicio' => $reserva->fecha_inicio,
                'fecha_fin' => $reserva->fecha_fin,
                'estado' => $reserva->estado,
                'habitacion' => $reserva->habitacion ? [
                    'numero' => $reserva->habitacion->numero, // Mostrar número de habitación
                    'precio' => $reserva->habitacion->precio,
                ] : null,
                'precio' => $reserva->habitacion->precio ?? 0,
            ];
        });
    
        return response()->json($reservasTransformadas);
    }



}
