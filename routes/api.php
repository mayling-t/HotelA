<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServicioExtraController;
use App\Http\Controllers\CheckinCheckoutController;
use App\Http\Controllers\ReservaServicioExtraController;
use App\Http\Controllers\PagoController;

Route::post('/cliente/register', [ClienteController::class, 'register']);
Route::post('/cliente/login', [ClienteController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cliente/me', [ClienteController::class, 'me']);
    Route::post('/cliente/logout', [ClienteController::class, 'logout']);
});



// Habitaciones
Route::get('/habitaciones/disponibles', [HabitacionController::class, 'disponibles']);
Route::post('/habitaciones/asignar', [HabitacionController::class, 'asignar']);
Route::get('/habitaciones/buscar-por-tipo', [HabitacionController::class, 'buscarPorTipo']);

// Finalmente el recurso RESTful (apiResource)
Route::apiResource('habitaciones', HabitacionController::class);
// Reservas
Route::get('/reservas', [ReservaController::class, 'index']);
Route::post('/reservas', [ReservaController::class, 'store']);
Route::put('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar']);
Route::get('/habitaciones/{id}/disponibilidad', [HabitacionController::class, 'disponibilidadPorHabitacion']);

// Clientes

Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/clientes/{id}', [ClienteController::class, 'show']);
Route::post('/clientes', [ClienteController::class, 'store']);
Route::put('/clientes/{id}', [ClienteController::class, 'update']);
Route::get('/clientes/{dni}/buscar', [ClienteController::class, 'buscarPorDni']);
Route::get('/clientes/{id}/reservas', [ReservaController::class, 'reservasPorCliente']);
// Servicios Extras
Route::get('/servicios-extras', [ServicioExtraController::class, 'index']);
Route::post('/servicios-extras', [ServicioExtraController::class, 'store']);
Route::put('/servicios-extras/{id}', [ServicioExtraController::class, 'update']);
Route::post('/servicios-extras/asignar', [ServicioExtraController::class, 'asignar']);


Route::get('/pagos', [PagoController::class, 'index']);
Route::post('/pagos', [PagoController::class, 'store']);
Route::get('/pagos/{id}', [PagoController::class, 'show']);
Route::put('/pagos/{id}', [PagoController::class, 'update']);
Route::delete('/pagos/{id}', [PagoController::class, 'destroy']);

// Check-in / Check-out
Route::get('/checkin-checkout', [CheckinCheckoutController::class, 'index']);
Route::post('/checkin-checkout', [CheckinCheckoutController::class, 'store']);
Route::put('/checkin-checkout/{id}', [CheckinCheckoutController::class, 'update']);
Route::post('/checkin', [CheckinCheckoutController::class, 'realizarCheckin']);
Route::post('/checkout', [CheckinCheckoutController::class, 'realizarCheckout']);
Route::get('/checkinout/historial', [CheckinCheckoutController::class, 'historial']);

// Relación Reserva - Servicios Extras
Route::get('/reserva-servicios-extras', [ReservaServicioExtraController::class, 'index']);
Route::post('/reserva-servicios-extras', [ReservaServicioExtraController::class, 'store']);
Route::delete('/reserva-servicios-extras/{id}', [ReservaServicioExtraController::class, 'destroy']);


Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::post('/usuarios', [UsuarioController::class, 'store']);
