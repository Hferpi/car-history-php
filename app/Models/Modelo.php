<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'modelo';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'marca_id'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }
}
