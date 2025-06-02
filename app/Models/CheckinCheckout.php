<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinCheckout extends Model
{
    use HasFactory;

    protected $table = 'checkin_checkout';

    protected $fillable = [
        'id_reserva',
        'fecha_checkin',
        'fecha_checkout',
    ];

    // Relación con reserva
    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'id_reserva', 'id');
    }
}
