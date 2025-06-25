<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
     use HasFactory;

    protected $fillable = [
        'id_reserva',
        'monto',
        'fecha_pago',
        'metodo_pago',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva');
    }
}
