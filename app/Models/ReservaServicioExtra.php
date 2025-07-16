<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaServicioExtra extends Model
{
    protected $table = 'reserva_servicios_extras';

    protected $fillable = ['id_reserva', 'id_servicio_extra'];

    public $timestamps = false; // Si no usas timestamps
}