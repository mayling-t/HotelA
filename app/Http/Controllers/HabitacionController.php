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
        'numero' => 'sometimes|required|unique:habitaciones,numero,' . $id,
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


// Obtener una habitación por su ID
public function show($id)
{
    $habitacion = Habitacion::find($id);

    if (!$habitacion) {
        return response()->json(['mensaje' => 'Habitación no encontrada'], 404);
    }

    return response()->json($habitacion, 200);
}


    // Consultar habitaciones disponibles por fecha
    public function disponibles(Request $request)
{
    $request->validate([
        'fechaInicio' => 'required|date',
        'fechaFin' => 'required|date|after_or_equal:fechaInicio',
    ]);

    $fechaInicio = $request->query('fechaInicio');
    $fechaFin = $request->query('fechaFin');

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

    $habitacionesDisponibles = Habitacion::whereNotIn('id', $habitacionesOcupadas)
        ->where('estado', 'disponible')
        ->get();

    return response()->json(['habitaciones' => $habitacionesDisponibles], 200);
}

    // Asignar habitación a reserva
    public function asignar(Request $request)
{
    // Validar que vengan los datos y existan en la BD
    $data = $request->validate([
        'idReserva' => 'required|integer|exists:reservas,id',
        'idHabitacion' => 'required|integer|exists:habitaciones,id',
    ]);

    // Buscar la reserva
    $reserva = \App\Models\Reserva::find($data['idReserva']);
    if (!$reserva) {
        return response()->json(['mensaje' => 'Reserva no encontrada'], 404);
    }

    // Buscar la habitación
    $habitacion = \App\Models\Habitacion::find($data['idHabitacion']);
    if (!$habitacion) {
        return response()->json(['mensaje' => 'Habitación no encontrada'], 404);
    }

    // Asignar la habitación a la reserva
    $reserva->id_habitacion = $habitacion->id;
    $reserva->save();

    return response()->json([
        'mensaje' => 'Habitación asignada a la reserva correctamente',
        'reserva' => $reserva,
        'habitacion' => $habitacion
    ], 200);
}


    // Buscar habitación por tipo
   public function buscarPorTipo(Request $request)
{
    $tipo = $request->query('tipo');

    // Buscar TODAS las habitaciones que coincidan con el tipo
    $habitaciones = Habitacion::where('tipo', $tipo)->get();

    // Retornar un array (puede estar vacío si no hay resultados)
    return response()->json(['habitaciones' => $habitaciones], 200);
}
public function disponibilidadPorHabitacion(Request $request, $id)
{
    $request->validate([
        'fechaInicio' => 'nullable|date',
        'fechaFin' => 'nullable|date|after_or_equal:fechaInicio',
    ]);

    $habitacion = Habitacion::find($id);
    if (!$habitacion) {
        return response()->json(['mensaje' => 'Habitación no encontrada'], 404);
    }

    // Rango para consultar disponibilidad
    $fechaInicio = $request->fechaInicio ?? date('Y-m-d');
    $fechaFin = $request->fechaFin ?? null; // Puede ser null (sin límite)

    // Obtener reservas confirmadas que se traslapan con el rango consultado
    $reservas = DB::table('reservas')
        ->where('id_habitacion', $id)
        ->where('estado', 'confirmada')
        ->where(function ($query) use ($fechaInicio, $fechaFin) {
            if ($fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                      ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                      ->orWhereRaw('? BETWEEN fecha_inicio AND fecha_fin', [$fechaInicio])
                      ->orWhereRaw('? BETWEEN fecha_inicio AND fecha_fin', [$fechaFin]);
            } else {
                // Si no hay fechaFin, solo que fecha_inicio >= fechaInicio
                $query->where('fecha_fin', '>=', $fechaInicio);
            }
        })
        ->orderBy('fecha_inicio')
        ->get(['fecha_inicio', 'fecha_fin']);

    $disponibilidades = [];

    // Si no hay reservas en el rango, toda la ventana es disponible
    if ($reservas->isEmpty()) {
        $disponibilidades[] = ['desde' => $fechaInicio, 'hasta' => $fechaFin];
    } else {
        // Inicializamos cursor para el inicio del rango disponible
        $cursor = $fechaInicio;

        foreach ($reservas as $reserva) {
            // Si hay espacio entre cursor y la siguiente reserva
            if ($cursor < $reserva->fecha_inicio) {
                $disponibilidades[] = [
                    'desde' => $cursor,
                    'hasta' => $reserva->fecha_inicio
                ];
            }
            // Movemos cursor al fin de la reserva (si es mayor)
            if ($cursor < $reserva->fecha_fin) {
                $cursor = $reserva->fecha_fin;
            }
        }

        // Si hay espacio después de la última reserva hasta fechaFin
        if (!$fechaFin || $cursor < $fechaFin) {
            $disponibilidades[] = [
                'desde' => $cursor,
                'hasta' => $fechaFin,
            ];
        }
    }

    // Si el rango consultado incluye hoy, y la habitación está ocupada en hoy, marcar estado ocupado
    $estadoHabitacion = 'disponible';
    $hoy = date('Y-m-d');
    foreach ($reservas as $r) {
        if ($hoy >= $r->fecha_inicio && $hoy <= $r->fecha_fin) {
            $estadoHabitacion = 'ocupada';
            break;
        }
    }

    return response()->json([
        'habitacion' => $habitacion,
        'disponibilidades' => $disponibilidades,
        'estado_actual' => $estadoHabitacion,
    ]);
}

}
