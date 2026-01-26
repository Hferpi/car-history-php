<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    protected $table = 'taller';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'tipo',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Modelo::class, 'vehicle_id');
    }
}
