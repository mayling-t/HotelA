<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    public $timestamps = false; // ✅ IMPORTANTE: Desactiva los campos created_at y updated_at

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
     // ✅ RELACIÓN: Usuario tiene un Cliente
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }
}
