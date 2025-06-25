<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;


class ClienteController extends Controller
{

public function register(Request $request)
{
    $request->validate([
        'nombre'    => 'required|string',
        'apellidos' => 'nullable|string',
        'dni'       => 'required|size:8|unique:clientes,dni',
        'celular'   => 'required|string|max:15',
        'email'     => 'required|email|unique:clientes,email',
        'password'  => 'required|string|confirmed',
    ]);

    $cliente = Cliente::create([
        'nombre'    => $request->nombre,
        'apellidos' => $request->apellidos,
        'dni'       => $request->dni,
        'celular'   => $request->celular,
        'email'     => $request->email,
        'password'  => Hash::make($request->password),
    ]);

    return response()->json(['message' => 'Cliente registrado con éxito', 'cliente' => $cliente], 201);
}

public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    $cliente = Cliente::where('email', $request->email)->first();

    if (!$cliente || !Hash::check($request->password, $cliente->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    $token = $cliente->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'cliente'      => $cliente,
    ]);
}

public function me(Request $request)
{
    return response()->json($request->user());
}

public function logout(Request $request)
{
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Sesión cerrada']);
}


    // Listar clientes
    public function index()
    {
        return response()->json(Cliente::all(), 200);
        return Cliente::with('usuario')->get();

    }

    // Mostrar cliente por id
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente, 200);
    }

    // Crear cliente
     public function store(Request $request)
    {
        $data = $request->validate([
            // 'id' => 'required|integer|unique:clientes,id', // quitar
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'dni' => 'required|size:8|unique:clientes,dni',
            'celular' => 'required|string|max:15',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::create($data);

        return response()->json(['mensaje' => 'Cliente creado', 'cliente' => $cliente], 201);
    }

    // Actualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $data = $request->validate([
            'dni' => 'sometimes|required|size:8|unique:clientes,dni,' . $id,
            'nombre' => 'nullable|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'celular' => 'sometimes|required|string|max:15',
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($data);

        return response()->json(['mensaje' => 'Cliente actualizado', 'cliente' => $cliente], 200);
    }
    public function buscarPorDni($dni)
{
    $cliente = Cliente::where('dni', $dni)->first();

    if (!$cliente) {
        return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
    }

    return response()->json($cliente, 200);
}
public function listarReservas($clienteId)
{
    $cliente = Cliente::find($clienteId);

    if (!$cliente) {
        return response()->json(['mensaje' => 'Cliente no encontrado'], 404);
    }

    $reservas = $cliente->reservas; // esto funciona con la relación correcta

    return response()->json($reservas, 200);
}

}
