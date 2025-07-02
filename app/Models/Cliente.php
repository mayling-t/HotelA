<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'user_id',        // ✅ ¡esto era lo que faltaba!
        'dni',
        'email',
        'nombre',
        'apellidos',
        'celular',
        'telefono',
        'direccion',
    ];

    public $timestamps = true;

    // (opcional) relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
