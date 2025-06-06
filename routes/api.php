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

// Usuarios (registro y login — sin seguridad)
Route::post('/registro', [UsuarioController::class, 'registro']);
Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/logout', [UsuarioController::class, 'logout']);

// Habitaciones
Route::get('/habitaciones', [HabitacionController::class, 'index']);
Route::post('/habitaciones', [HabitacionController::class, 'store']);
Route::apiResource('habitaciones', HabitacionController::class);


Route::get('/habitaciones/disponibles', [HabitacionController::class, 'disponibles']);
Route::post('/habitaciones/asignar', [HabitacionController::class, 'asignar']);
Route::get('/habitaciones/buscar-por-tipo', [HabitacionController::class, 'buscarPorTipo']);

// Reservas
Route::get('/reservas', [ReservaController::class, 'index']);
Route::post('/reservas', [ReservaController::class, 'store']);
Route::put('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar']);

// Clientes
Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/clientes/{id}', [ClienteController::class, 'show']);
Route::post('/clientes', [ClienteController::class, 'store']);
Route::put('/clientes/{id}', [ClienteController::class, 'update']);

// Servicios Extras
Route::get('/servicios-extras', [ServicioExtraController::class, 'index']);
Route::post('/servicios-extras', [ServicioExtraController::class, 'store']);
Route::put('/servicios-extras/{id}', [ServicioExtraController::class, 'update']);

// Check-in / Check-out
Route::get('/checkin-checkout', [CheckinCheckoutController::class, 'index']);
Route::post('/checkin-checkout', [CheckinCheckoutController::class, 'store']);
Route::put('/checkin-checkout/{id}', [CheckinCheckoutController::class, 'update']);

// Relación Reserva - Servicios Extras
Route::get('/reserva-servicios-extras', [ReservaServicioExtraController::class, 'index']);
Route::post('/reserva-servicios-extras', [ReservaServicioExtraController::class, 'store']);
Route::delete('/reserva-servicios-extras/{id}', [ReservaServicioExtraController::class, 'destroy']);


Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::post('/usuarios', [UsuarioController::class, 'store']);
