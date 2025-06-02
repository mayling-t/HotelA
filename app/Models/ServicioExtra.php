<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioExtra extends Model
{
    use HasFactory;

    protected $table = 'servicios_extras';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
    ];

    // Relación con reservas (muchos a muchos)
    public function reservas()
    {
        return $this->belongsToMany(Reserva::class, 'reserva_servicios_extras', 'id_servicio', 'id_reserva');
    }
}
