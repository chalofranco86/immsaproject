<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Propietario;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\ServicioHorario;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrdenes = OrdenTrabajo::count();
        $totalPropietarios = Propietario::count();
        $totalEmpleados = Empleado::count();

        $ordenesRecibidas = OrdenTrabajo::where('estado', 'Recibido')->count();
        $ordenesRevision = OrdenTrabajo::where('estado', 'Revisión')->count();
        $ordenesAutorizadas = OrdenTrabajo::where('estado', 'Autorizado')->count();
        $ordenesEntregadas = OrdenTrabajo::where('estado', 'Entregado')->count();

        $ingresosTotales = OrdenTrabajo::sum('total');
        $pagosPendientes = OrdenTrabajo::where('saldo', '>', 0)->sum('saldo');

        $proximasEntregas = OrdenTrabajo::whereNotNull('fecha_entrega')
            ->where('fecha_entrega', '>=', now())
            ->orderBy('fecha_entrega', 'asc')
            ->with('propietario')
            ->take(5)
            ->get();

        $serviciosSolicitados = Servicio::withCount('ordenes')
            ->orderBy('ordenes_count', 'desc')
            ->take(5)
            ->get();

        $ordenesPorEmpleado = Empleado::withCount('ordenesAsignadas')
            ->orderBy('ordenes_asignadas_count', 'desc')
            ->take(5)
            ->get();

        $coloresServicios = ServicioHorario::select('color', \DB::raw('count(*) as total'))
            ->groupBy('color')
            ->get();

        return view('dashboard', compact(
            'totalOrdenes',
            'totalPropietarios',
            'totalEmpleados',
            'ordenesRecibidas',
            'ordenesRevision',
            'ordenesAutorizadas',
            'ordenesEntregadas',
            'ingresosTotales',
            'pagosPendientes',
            'proximasEntregas',
            'serviciosSolicitados',
            'ordenesPorEmpleado',
            'coloresServicios'
        ));
    }
}
