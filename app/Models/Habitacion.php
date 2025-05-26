<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $fillable = ['numero', 'tipo', 'precio', 'estado'];

}
