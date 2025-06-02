<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'id_cliente',
        'id_habitacion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    // Relación con Habitacion
    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion', 'id');
    }
    public function serviciosExtras()
{
    return $this->belongsToMany(ServicioExtra::class, 'reserva_servicios_extras', 'id_reserva', 'id_servicio');
}

}
