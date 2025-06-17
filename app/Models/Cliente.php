<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    public $timestamps = false;  // Como no tienes created_at ni updated_at en clientes
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'dni',
        'nombre',
        'apellidos',
        'celular',
        'telefono',
        'direccion',
    ];

    
}
