<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';
    protected $fillable = [
        'numero_orden',
        'propietario_id',
        'empleado_id',
        'fecha_recibido',
        'fecha_entrega',
        'fecha_fin',
        'subtotal',
        'descuento',
        'total',
        'anticipo',
        'saldo',
        'estado',
        'observaciones',
        'serie_motor', 
        'nit_factura',
        'repuestos', // Nuevo campo
    ];

    protected $casts = [
        'fecha_recibido' => 'date',
        'fecha_entrega' => 'date',
        'fecha_fin' => 'date',
        'estado' => 'string'
    ];
    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'propietario_id');
    }
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'orden_servicio')
            ->withPivot('costo', 'responsable', 'finalizado', 'color');
    }

    public function servicioHorarios()
    {
        return $this->hasMany(ServicioHorario::class, 'orden_trabajo_id');
    }
}
