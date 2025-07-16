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
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'estado' => 'nullable|in:confirmada,cancelada,finalizada',
    ], [
        'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o una fecha futura.',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
    ]);

    // Obtener la habitación
    $habitacion = Habitacion::find($data['id_habitacion']);

    // Validar que no esté ocupada
    if ($habitacion->estado === 'ocupada') {
        return response()->json([
            'mensaje' => 'No se puede reservar esta habitación porque está ocupada actualmente.'
        ], 400);
    }

    // Estado por defecto si no se envía
    $data['estado'] = $data['estado'] ?? 'confirmada';

    // Crear reserva
    $reserva = Reserva::create($data);

    // Cambiar estado de la habitación a ocupada si la reserva está confirmada
    if ($data['estado'] === 'confirmada') {
        $habitacion->estado = 'ocupada';
        $habitacion->save();
    }

    return response()->json([
        'mensaje' => 'Reserva registrada y habitación actualizada',
        'reserva' => $reserva
    ], 201);
}




    // Cancelar reserva
    public function cancelar($id)
{
    $reserva = Reserva::with('habitacion')->findOrFail($id);

    // Cambiar estado de la reserva
    $reserva->estado = 'cancelada';
    $reserva->save();

    // Cambiar habitación a disponible
    if ($reserva->habitacion) {
        $reserva->habitacion->estado = 'disponible';
        $reserva->habitacion->save();
    }

    return response()->json(['mensaje' => 'Reserva cancelada y habitación disponible'], 200);
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
                    'imagen' => $reserva->habitacion->imagen, 
                ] : null,
                'precio' => $reserva->habitacion->precio ?? 0,
            ];
        });
    
        return response()->json($reservasTransformadas);
    }

public function show($id)
{
    try {
        $reserva = Reserva::with(['cliente', 'habitacion', 'serviciosExtras'])->findOrFail($id);
        return response()->json($reserva);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



}
