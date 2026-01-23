<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRepair extends Model
{
    protected $table = 'vehicle_repair'; // o reparacion si usas español
    public $timestamps = false;

    protected $fillable = [
        'vehiculo_id',
        'taller_id',
        'fecha',
        'precio',
        'tipo_servicio',
        'observaciones',
        'foto'
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehicle::class, 'vehiculo_id');
    }

    public function taller()
    {
        return $this->belongsTo(Taller::class, 'taller_id');
    }
}

