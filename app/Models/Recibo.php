<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibo';
    public $timestamps = false;

    protected $fillable = [
        'vehiculo_id',
        'fecha',
        'taller_nombre',
        'precio',
        'tipo_servicio',
        'observaciones',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehicle::class, 'vehiculo_id');
    }
}
