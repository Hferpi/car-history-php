<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibo';
    public $timestamps = false;

    //TODO: añadir km¿¿??
    protected $fillable = [
        'vehiculo_id',
        'fecha',
        'precio',
        'tipo_servicio',
        'observaciones',
        'taller_nombre',
        'foto_patch',
        'foto_disk'
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

