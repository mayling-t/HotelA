<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Reserva;

class PagoController extends Controller
{
    public function index()
{
    return Pago::with('reserva.cliente')->get()->map(function ($pago) {
        return [
            'id' => $pago->id,
            'monto' => $pago->monto,
            'fecha_pago' => $pago->fecha_pago,
            'metodo_pago' => $pago->metodo_pago,
            'cliente' => $pago->reserva->cliente->nombre . ' ' . $pago->reserva->cliente->apellido,
        ];
    });
}


public function store(Request $request)
{
    $request->validate([
        'dni' => 'required|exists:clientes,dni',
        'fecha_pago' => 'required|date',
        'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
    ]);

    // Buscar cliente
    $cliente = Cliente::where('dni', $request->dni)->first();

    // Última reserva
    $reserva = Reserva::where('id_cliente', $cliente->id)->latest()->first();

    if (!$reserva) {
        return response()->json(['error' => 'El cliente no tiene reservas.'], 404);
    }

    // 🚫 Si ya fue pagada
    if ($reserva->estado === 'finalizada') {
        return response()->json(['error' => 'Esta reserva ya ha sido pagada.'], 400);
    }

    // Obtener precios
    $habitacion = $reserva->habitacion;
    $precioHabitacion = $habitacion ? $habitacion->precio : 0;
    $servicios = $reserva->serviciosExtras;
    $totalServicios = $servicios->sum('precio');
    $montoTotal = $precioHabitacion + $totalServicios;

    // Registrar el pago
    $pago = Pago::create([
        'id_reserva' => $reserva->id,
        'monto' => $montoTotal,
        'fecha_pago' => $request->fecha_pago,
        'metodo_pago' => $request->metodo_pago,
    ]);

    // Actualizar estado reserva
    $reserva->estado = 'finalizada';
    $reserva->save();

    // Liberar habitación
    if ($habitacion) {
        $habitacion->estado = 'disponible';
        $habitacion->save();
    }

    return response()->json([
        'mensaje' => 'Pago registrado correctamente.',
        'monto_calculado' => $montoTotal,
        'pago' => $pago
    ], 201);
}


    public function show($id)
    {
        return Pago::with('reserva')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);
        $pago->update($request->all());
        return $pago;
    }

    public function destroy($id)
    {
        Pago::destroy($id);
        return response()->noContent();
    }
}
