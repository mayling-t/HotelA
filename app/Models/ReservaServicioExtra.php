<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaServicioExtra extends Model
{
    use HasFactory;

    protected $table = 'reserva_servicios_extras';

    public $timestamps = false;

    protected $fillable = [
        'id_reserva',
        'id_servicio',
    ];
}
