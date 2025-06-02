<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HabitacionController extends Controller
{
    // Listar todas las habitaciones
   public function index(Request $request)
{
    $habitaciones = Habitacion::all();

    return response()->json($habitaciones); // <---- sin 'data'
}

    // Registrar una nueva habitación
    public function store(Request $request)
    {
        $data = $request->validate([
            'numero' => 'required|unique:habitaciones',
            'tipo' => 'required|in:simple,doble,suite',
            'precio' => 'required|numeric',
            'capacidad' => 'required|integer',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|string',
            'estado' => 'required|in:disponible,ocupada,mantenimiento,inactiva',
        ]);

        $habitacion = Habitacion::create($data);

        return response()->json(['mensaje' => 'Habitación creada', 'habitacion' => $habitacion], 201);
    }

    // Actualizar habitación
    public function update(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $data = $request->validate([
            'tipo' => 'sometimes|required|in:simple,doble,suite',
            'precio' => 'sometimes|required|numeric',
            'capacidad' => 'sometimes|required|integer',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|string',
            'estado' => 'sometimes|required|in:disponible,ocupada,mantenimiento,inactiva',
        ]);

        $habitacion->update($data);

        return response()->json(['mensaje' => 'Habitación actualizada', 'habitacion' => $habitacion], 200);
    }

    // Consultar habitaciones disponibles por fecha
    public function disponibles(Request $request)
    {
        $request->validate([
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        // Obtener habitaciones ocupadas en las fechas indicadas
        $habitacionesOcupadas = DB::table('reservas')
            ->where('estado', 'confirmada')
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                      ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                      ->orWhereRaw('? BETWEEN fecha_inicio AND fecha_fin', [$fechaInicio])
                      ->orWhereRaw('? BETWEEN fecha_inicio AND fecha_fin', [$fechaFin]);
            })
            ->pluck('id_habitacion');

        // Habitaciones disponibles
        $habitacionesDisponibles = Habitacion::whereNotIn('id', $habitacionesOcupadas)
            ->where('estado', 'disponible')
            ->get();

        return response()->json($habitacionesDisponibles, 200);
    }

    // Asignar habitación a reserva
    public function asignar(Request $request)
    {
        $data = $request->validate([
            'idReserva' => 'required|integer|exists:reservas,id',
            'idHabitacion' => 'required|integer|exists:habitaciones,id',
        ]);

        $reserva = \App\Models\Reserva::findOrFail($data['idReserva']);
        $reserva->id_habitacion = $data['idHabitacion'];
        $reserva->save();

        return response()->json(['mensaje' => 'Habitación asignada a la reserva'], 200);
    }

    // Buscar habitación por tipo
    public function buscarPorTipo(Request $request)
    {
        $tipo = $request->query('tipo');

        if (!$tipo) {
            return response()->json(['mensaje' => 'Debe especificar el tipo'], 400);
        }

        $habitaciones = Habitacion::where('tipo', $tipo)->get();

        return response()->json($habitaciones, 200);
    }
}
