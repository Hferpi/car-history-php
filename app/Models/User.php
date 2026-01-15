<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nombre de la tabla en la base de datos.
     */
    protected $table = 'usuario';

    /**
     * Desactivamos los timestamps automáticos de Laravel (updated_at)
     * ya que tu tabla solo tiene created_at o es manual.
     */
    public $timestamps = false;

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    /**
     * Atributos que deben ocultarse en arrays o JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casteo de atributos (conversión automática).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}