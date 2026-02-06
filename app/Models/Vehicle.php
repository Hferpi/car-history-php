<?php

namespace App\Models;

use App\Models\Recibo;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehiculo';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'modelo_id',
        'marca',
        'matricula',
        'kilometros',
        'avatar',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id');
    }

    public function repairs()
    {
        return $this->hasMany(Recibo::class, 'vehiculo_id');
    }
}
