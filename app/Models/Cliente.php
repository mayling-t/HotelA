<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    public $timestamps = true; // Tu migración sí tiene timestamps
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'dni',
        'email',
        'nombre',
        'apellidos',
        'celular',
        'telefono',
        'direccion',
    ];
}
