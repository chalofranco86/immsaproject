<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'tipo_servicio',
    ];

    public function ordenesTrabajo()
    {
        return $this->belongsToMany(OrdenTrabajo::class)->withPivot('costo');
    }
    public function ordenes()
    {
        return $this->belongsToMany(OrdenTrabajo::class, 'orden_servicio');
    }
}