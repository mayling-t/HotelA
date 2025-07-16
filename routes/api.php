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

use App\Http\Controllers\RegistroController;

// Usuarios (registro y login — sin seguridad)
Route::post('/registro', [UsuarioController::class, 'registro']);
Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/logout', [UsuarioController::class, 'logout']);
Route::post('/registro-cliente', [RegistroController::class, 'registrar']);

// Habitaciones
Route::post('/habitaciones', [HabitacionController::class, 'store']);

Route::get('/habitaciones/disponibles', [HabitacionController::class, 'disponibles']);
Route::post('/habitaciones/asignar', [HabitacionController::class, 'asignar']);
Route::get('/habitaciones/buscar-por-tipo', [HabitacionController::class, 'buscarPorTipo']);

// Finalmente el recurso RESTful (apiResource)
Route::apiResource('habitaciones', HabitacionController::class);
// Reservas

Route::get('/reservas/{id}/servicios-extras', [ReservaController::class, 'show']);

Route::get('/reservas', [ReservaController::class, 'index']);
Route::post('/reservas', [ReservaController::class, 'store']);
Route::put('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar']);
Route::get('/reservas/cliente/{id}', [ReservaController::class, 'reservasPorCliente']);

Route::get('/habitaciones/{id}/disponibilidad', [HabitacionController::class, 'disponibilidadPorHabitacion']);
Route::get('/reservas/cliente/{id}', [ReservaController::class, 'reservasPorCliente']);

// Clientes

Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/clientes/{id}', [ClienteController::class, 'show']);
Route::post('/clientes', [ClienteController::class, 'store']);
Route::put('/clientes/{id}', [ClienteController::class, 'update']);
Route::get('/clientes/{dni}/buscar', [ClienteController::class, 'buscarPorDni']);
Route::get('/cliente/{id}/reservas', [ClienteController::class, 'listarReservas']);
Route::get('/cliente/usuario/{id}', [ClienteController::class, 'buscarPorUsuario']);

// En routes/api.php
Route::get('/clientes/buscar-por-dni', [ClienteController::class, 'buscarClientePorDni']);



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
Route::get('/pagos/{idReserva}', [PagoController::class, 'obtenerPagoPorReserva']);

// Check-in / Check-out
Route::get('/checkin-checkout', [CheckinCheckoutController::class, 'index']);
Route::post('/checkin-checkout', [CheckinCheckoutController::class, 'store']);
Route::put('/checkin-checkout/{id}', [CheckinCheckoutController::class, 'update']);
Route::post('/checkin', [CheckinCheckoutController::class, 'realizarCheckin']);
Route::post('/checkout', [CheckinCheckoutController::class, 'realizarCheckout']);
Route::get('/checkinout/historial', [CheckinCheckoutController::class, 'historial']);

// Relación Reserva - Servicios Extras

Route::post('/reserva-servicios', [ReservaServicioExtraController::class, 'store']);
Route::get('/reservas/{id}/servicios-extras', [ReservaServicioExtraController::class, 'serviciosPorReserva']);
Route::get('/reserva-servicios', [ReservaServicioExtraController::class, 'index']);
Route::delete('/reserva-servicios/{id}', [ReservaServicioExtraController::class, 'destroy']);


Route::get('/reservas/{id}/servicios-extras', [ReservaServicioExtraController::class, 'serviciosPorReserva']);





Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::post('/usuarios', [UsuarioController::class, 'store']);
