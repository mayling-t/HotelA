<?php

namespace App\Http\Controllers;

use App\Models\CheckinCheckout;
use Illuminate\Http\Request;

class CheckinCheckoutController extends Controller
{
    // Listar checkin/checkout
    public function index()
    {
        return response()->json(CheckinCheckout::all(), 200);
    }

    // Crear registro checkin/checkout
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_reserva' => 'required|integer|exists:reservas,id',
            'fecha_checkin' => 'nullable|date',
            'fecha_checkout' => 'nullable|date',
        ]);

        $registro = CheckinCheckout::create($data);

        return response()->json(['mensaje' => 'Registro creado', 'registro' => $registro], 201);
    }

    // Actualizar registro
    public function update(Request $request, $id)
    {
        $registro = CheckinCheckout::findOrFail($id);

        $data = $request->validate([
            'fecha_checkin' => 'sometimes|date',
            'fecha_checkout' => 'sometimes|date',
        ]);

        $registro->update($data);

        return response()->json(['mensaje' => 'Registro actualizado', 'registro' => $registro], 200);
    }
    public function realizarCheckin(Request $request)
    {
        $data = $request->validate([
            'id_reserva' => 'required|integer|exists:reservas,id',
            'fecha_checkin' => 'nullable|date',
        ]);

        // Busca si ya existe un registro para esa reserva
        $registro = CheckinCheckout::firstOrCreate(
            ['id_reserva' => $data['id_reserva']],
            ['fecha_checkin' => $data['fecha_checkin']]
        );

        return response()->json([
            'mensaje' => 'Check-in confirmado',
            'registro' => $registro
        ], 201);
    }

    // ✅ 2. Realizar Check-out
    public function realizarCheckout(Request $request)
    {
        $data = $request->validate([
            'id_reserva' => 'required|integer|exists:reservas,id',
            'fecha_checkout' => 'nullable|date',
        ]);

        $registro = CheckinCheckout::where('id_reserva', $data['id_reserva'])->first();

        if (!$registro) {
            return response()->json(['mensaje' => 'No se encontró registro para la reserva'], 404);
        }

        $registro->update(['fecha_checkout' => $data['fecha_checkout']]);

        return response()->json([
            'mensaje' => 'Check-out exitoso',
            'registro' => $registro
        ], 200);
    }

    // ✅ 3. Consultar historial
    public function historial()
    {
        return response()->json(CheckinCheckout::all(), 200);
    }
}
