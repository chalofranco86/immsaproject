<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
    ];

    public function ordenesTrabajo()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }
}