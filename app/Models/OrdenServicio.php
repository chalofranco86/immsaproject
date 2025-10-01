<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    use HasFactory;

    protected $table = 'orden_servicio';
    
    protected $fillable = [
        'orden_trabajo_id',
        'servicio_id',
        'responsable',
        'costo',
        'color',
        'finalizado'
    ];

    // Relación con OrdenTrabajo
    public function ordenTrabajo()
    {
        return $this->belongsTo(OrdenTrabajo::class, 'orden_trabajo_id');
    }

    // Relación con Servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    // Relación con Empleado (responsable)
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'responsable');
    }
}