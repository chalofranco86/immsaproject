<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioHorario extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_trabajo_id',
        'servicio_id',
        'color',
        'hora_inicio',
        'hora_fin'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
