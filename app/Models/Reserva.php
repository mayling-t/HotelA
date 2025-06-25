<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
   protected $table = 'reservas';

    // Asegúrate que este campo exista en fillable o guarded según configures
    protected $fillable = ['id_cliente', 'id_habitacion', 'fecha_inicio', 'fecha_fin', 'estado', 'precio'];

    // Relación con Cliente
    public function cliente()
{
    return $this->belongsTo(Cliente::class, 'id_cliente');
}

    // Relación con Habitacion
    // Reserva.php (modelo)
 public function habitacion()
{
    return $this->belongsTo(Habitacion::class, 'id_habitacion');
}
    public function serviciosExtras()
{
    return $this->belongsToMany(ServicioExtra::class, 'reserva_servicios_extras', 'id_reserva', 'id_servicio');
}


   

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_reserva');
    }

}
