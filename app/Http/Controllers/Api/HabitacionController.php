<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HabitacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Habitacion::query();

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|unique:habitacions',
            'tipo' => 'required',
            'precio' => 'required|numeric',
            'estado' => 'required'
        ]);

        $habitacion = Habitacion::create($request->all());

        return response()->json([
            'message' => 'Habitación registrada exitosamente',
            'habitacion' => $habitacion
        ]);
    }

    public function update(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $request->validate([
            'tipo' => 'required',
            'precio' => 'required|numeric',
            'estado' => 'required'
        ]);

        $habitacion->update($request->all());

        return response()->json(['message' => 'Habitación actualizada']);
    }

    public function disponibles(Request $request)
    {
        $request->validate([
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
        ]);

        $ocupadas = DB::table('reservas')
            ->whereBetween('fecha', [$request->fechaInicio, $request->fechaFin])
            ->pluck('id_habitacion');

        $habitacionesDisponibles = Habitacion::whereNotIn('id', $ocupadas)
            ->where('estado', 'disponible')
            ->get();

        return response()->json($habitacionesDisponibles);
    }

    public function asignar(Request $request)
    {
        $request->validate([
            'idReserva' => 'required|exists:reservas,id',
            'idHabitacion' => 'required|exists:habitacions,id',
        ]);

        DB::table('reservas')
            ->where('id', $request->idReserva)
            ->update(['id_habitacion' => $request->idHabitacion]);

        return response()->json(['message' => 'Habitación asignada a la reserva']);
    }
}
