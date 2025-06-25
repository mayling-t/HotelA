<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;


    protected $table = 'usuarios'; // Nombre de la tabla en la BD
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
    ];
    public $timestamps = false; // o true si usas created_at/updated_at

}
