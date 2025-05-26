<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HabitacionController;

// Endpoint para obtener habitaciones
// Otros endpoints...
Route::apiResource('habitaciones', HabitacionController::class);