<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    // Laravel por defecto busca la tabla en plural (vehiculos),
    // como la tuya es singular, la definimos:
    protected $table = 'vehiculo';

    // Tu tabla no tiene updated_at, así que desactivamos el manejo automático de timestamps
    // o lo personalizamos si solo quieres created_at
    public $timestamps = false;

    // Campos que permitimos llenar de forma masiva
    protected $fillable = [
        'usuario_id',
        'matricula',
        'modelo_id',
        'kilometros'
    ];
}