<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HabitacionController;

Route::get('/', function () {
    return view('welcome');
});


// Endpoint para obtener habitaciones
// Otros endpoints...
