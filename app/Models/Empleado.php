<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'nombre',
        'puesto',
    ];

    protected $table = 'empleados';

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function ordenesTrabajo()
    {
        return $this->hasMany(OrdenTrabajo::class);
    }

    public function ordenesComoResponsable()
    {
        return $this->hasMany(OrdenTrabajo::class, 'responsable');
    }

    public function ordenesAsignadas()
    {
        return $this->hasMany(OrdenTrabajo::class, 'empleado_id');
    }
}