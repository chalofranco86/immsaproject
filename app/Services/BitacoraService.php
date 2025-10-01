<?php
namespace App\Services;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Request;

class BitacoraService
{
    public static function registrar($accion, $modelo = null, $modeloId = null, $datosAnteriores = null, $datosNuevos = null)
    {
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion' => $accion,
            'modelo' => $modelo,
            'modelo_id' => $modeloId,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos' => $datosNuevos,
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }
}
