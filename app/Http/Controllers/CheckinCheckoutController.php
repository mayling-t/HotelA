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
}
