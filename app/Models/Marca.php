<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marca';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function modelos()
    {
        return $this->hasMany(Modelo::class, 'marca_id');
    }
}
