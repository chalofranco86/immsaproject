<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Propietario;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\ServicioHorario;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Admin ve todas las órdenes
            $ordenes = OrdenTrabajo::with(['propietario', 'empleado']);
        } elseif ($user->hasRole('empleado')) {
            // Empleado ve sólo las órdenes donde está asignado como empleado o como responsable de servicios
            $empleadoId = $user->empleado_id;
            
            $ordenes = OrdenTrabajo::with(['propietario', 'empleado'])
                ->where(function($query) use ($empleadoId) {
                    $query->where('empleado_id', $empleadoId)
                        ->orWhereHas('servicios', function($q) use ($empleadoId) {
                            $q->where('responsable', $empleadoId);
                        });
                });
        } else {
            // Para otros roles (como supervisor), puedes ajustar según necesites
            $ordenes = OrdenTrabajo::with(['propietario', 'empleado']);
        }

        $ordenes = $ordenes->get()->map(function ($orden) {
            $orden->fecha_recibido = \Carbon\Carbon::parse($orden->fecha_recibido);
            
            if ($orden->fecha_entrega) {
                $orden->fecha_entrega = \Carbon\Carbon::parse($orden->fecha_entrega);
            }
            
            return $orden;
        });

        return view('ordenes_trabajo.index', compact('ordenes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $propietarios = Propietario::all();
        $empleados = Empleado::all();
        $servicios = Servicio::all();
        $numeroOrden = $this->generarNumeroOrden();
        return view('ordenes_trabajo.create', compact('propietarios', 'empleados', 'servicios', 'numeroOrden'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_orden' => 'required|string|unique:ordenes_trabajo',
            'propietario_id' => 'required|exists:propietarios,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_recibido' => 'required|date',
            'fecha_entrega' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'descuento' => 'required|numeric',
            'anticipo' => 'required|numeric',
            'servicios' => 'required|array',
            'servicios.*.servicio_id' => 'required|exists:servicios,id',
            'servicios.*.costo' => 'required|numeric',
            'servicios.*.responsable' => 'nullable|exists:empleados,id',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Calcular subtotal automáticamente
        $subtotal = 0;
        foreach ($request->servicios as $servicio) {
            $subtotal += $servicio['costo'];
        }

        // Calcular total y saldo
        $descuento = $request->descuento;
        $total = $subtotal - $descuento;
        $anticipo = $request->anticipo;
        $saldo = $total - $anticipo;

        $ordenTrabajo = OrdenTrabajo::create([
            'numero_orden' => $request->numero_orden,
            'propietario_id' => $request->propietario_id,
            'empleado_id' => $request->empleado_id,
            'fecha_recibido' => $request->fecha_recibido,
            'fecha_entrega' => $request->fecha_entrega,
            'fecha_fin' => $request->fecha_fin,
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'total' => $total,
            'anticipo' => $anticipo,
            'saldo' => $saldo,
            'estado' => 'Recibido', // Estado inicial
            'observaciones' => $request->observaciones,
        ]);

        foreach ($request->servicios as $servicio) {
            $ordenTrabajo->servicios()->attach($servicio['servicio_id'], [
                'costo' => $servicio['costo'],
                'responsable' => $servicio['responsable']
            ]);
        }

        return redirect()->route('ordenes_trabajo.index')->with('success', 'Orden creada exitosamente');
    }


    private function generarNumeroOrden()
    {
        $ultimaOrden = OrdenTrabajo::latest()->first();
        if ($ultimaOrden) {
            $numero = intval(substr($ultimaOrden->numero_orden, 0, -1)) + 1;
        } else {
            $numero = 1;
        }
        $letra = chr(65 + ($numero - 1) % 26); // A, B, C, ..., Z
        return str_pad($numero, 3, '0', STR_PAD_LEFT) . $letra;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $orden = OrdenTrabajo::with([
            'propietario:id,nombre',
            'empleado:id,nombre',
            'servicios:id,tipo_servicio',
            'servicioHorarios'
        ])->findOrFail($id);

        $responsableIds = $orden->servicios->pluck('pivot.responsable')->unique()->filter();
        $empleadosResponsables = Empleado::whereIn('id', $responsableIds)->get()->keyBy('id');

        $orden->servicios->each(function ($servicio) use ($empleadosResponsables) {
            $servicio->empleadoResponsable = $empleadosResponsables->get($servicio->pivot->responsable);
        });

        return view('ordenes_trabajo.show', compact('orden'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $orden = OrdenTrabajo::with(['servicios' => function($query) {
            $query->withPivot('costo', 'responsable');
        }])->findOrFail($id);
        
        $propietarios = Propietario::all();
        $empleados = Empleado::all();
        $servicios = Servicio::all();
                
        return view('ordenes_trabajo.edit', compact('orden', 'propietarios', 'empleados', 'servicios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'propietario_id' => 'required|exists:propietarios,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_recibido' => 'required|date',
            'fecha_entrega' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'descuento' => 'required|numeric',
            'anticipo' => 'required|numeric',
            'estado' => 'required|in:Recibido,Revisión,Autorizado,Entregado',
            'observaciones' => 'nullable|string|max:500',
            'servicios' => 'required|array',
            'servicios.*.servicio_id' => 'required|exists:servicios,id',
            'servicios.*.costo' => 'required|numeric',
            'servicios.*.responsable' => 'nullable|exists:empleados,id',
        ]);

        // Calcular valores financieros
        $subtotal = 0;
        foreach ($request->servicios as $servicio) {
            $subtotal += $servicio['costo'];
        }
        
        $total = $subtotal - $request->descuento;
        $saldo = $total - $request->anticipo;

        $orden = OrdenTrabajo::findOrFail($id);
        $orden->update([
            'propietario_id' => $request->propietario_id,
            'empleado_id' => $request->empleado_id,
            'fecha_recibido' => $request->fecha_recibido,
            'fecha_entrega' => $request->fecha_entrega,
            'fecha_fin' => $request->fecha_fin,
            'subtotal' => $subtotal,
            'descuento' => $request->descuento,
            'total' => $total,
            'anticipo' => $request->anticipo,
            'saldo' => $saldo,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        // Sincronizar servicios
        $serviciosData = [];
        foreach ($request->servicios as $servicio) {
            $serviciosData[$servicio['servicio_id']] = [
                'costo' => $servicio['costo'],
                'responsable' => $servicio['responsable']
            ];
        }
        $orden->servicios()->sync($serviciosData);

        return redirect()->route('ordenes_trabajo.show', $orden->id)
            ->with('success', 'Orden actualizada exitosamente');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function downloadPDF($id)
    {
        $orden = OrdenTrabajo::with([
            'propietario:id,nombre',
            'empleado:id,nombre',
            'servicios:id,tipo_servicio'
        ])->findOrFail($id);

        // Obtener los IDs de empleados responsables de los servicios
        $responsableIds = $orden->servicios->pluck('pivot.responsable')->unique()->filter();
        $empleadosResponsables = Empleado::whereIn('id', $responsableIds)->get()->keyBy('id');

        $pdf = PDF::loadView('ordenes_trabajo.pdf', compact('orden', 'empleadosResponsables'));
        
        return $pdf->download('orden_trabajo_'.$orden->numero_orden.'.pdf');
    }

    public function generateReport(Request $request)
    {
        // Obtener los parámetros de filtro
        $filters = $request->only(['search', 'status', 'fecha_inicio', 'fecha_fin', 'saldo']);
        
        // Construir la consulta base
        $query = OrdenTrabajo::with(['propietario', 'empleado']);
        
        // Aplicar filtros
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('numero_orden', 'like', '%' . $filters['search'] . '%')
                ->orWhereHas('propietario', function($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['search'] . '%');
                })
                ->orWhereHas('empleado', function($q) use ($filters) {
                    $q->where('nombre', 'like', '%' . $filters['search'] . '%');
                });
            });
        }
        
        if (!empty($filters['status'])) {
            $query->where('estado', $filters['status']);
        }
        
        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_recibido', '>=', $filters['fecha_inicio']);
        }
        
        if (!empty($filters['fecha_fin'])) {
            $query->whereDate('fecha_recibido', '<=', $filters['fecha_fin']);
        }
        
        if (!empty($filters['saldo'])) {
            if ($filters['saldo'] === 'pendiente') {
                $query->where('saldo', '>', 0);
            } elseif ($filters['saldo'] === 'pagado') {
                $query->where('saldo', '<=', 0);
            }
        }
        
        // Obtener las órdenes filtradas
        $ordenes = $query->get()->map(function ($orden) {
            $orden->fecha_recibido = \Carbon\Carbon::parse($orden->fecha_recibido);
            
            if ($orden->fecha_entrega) {
                $orden->fecha_entrega = \Carbon\Carbon::parse($orden->fecha_entrega);
            }
            
            return $orden;
        });
        
        // Cargar la vista del reporte
        $pdf = PDF::loadView('ordenes_trabajo.report', compact('ordenes', 'filters'));
        
        return $pdf->download('reporte_ordenes_trabajo_' . date('Y-m-d') . '.pdf');
    }

    public function marcarServicioFinalizado(Request $request, $ordenId, $servicioId)
    {
        $user = auth()->user();
        $empleadoId = $user->empleado_id;

        // Buscar el registro en la tabla pivote
        $ordenServicio = \DB::table('orden_servicio')
            ->where('orden_trabajo_id', $ordenId)
            ->where('servicio_id', $servicioId)
            ->first();

        if (!$ordenServicio) {
            return redirect()->back()->with('error', 'El servicio no existe en esta orden.');
        }

        // Validar permisos
        $esResponsable = $ordenServicio->responsable == $empleadoId;
        $esAdmin = $user->hasRole('admin');
        $esSupervisor = $user->hasRole('supervisor');

        if (!$esResponsable && !$esAdmin && !$esSupervisor) {
            return redirect()->back()->with('error', 'No tienes permiso para modificar este servicio.');
        }

        // Cambiar el estado de finalizado
        $nuevoEstado = !$ordenServicio->finalizado;

        // Actualizar el estado del servicio
        \DB::table('orden_servicio')
            ->where('orden_trabajo_id', $ordenId)
            ->where('servicio_id', $servicioId)
            ->update(['finalizado' => $nuevoEstado]);

        $mensaje = $nuevoEstado ? 'Servicio marcado como finalizado.' : 'Servicio marcado como NO finalizado.';

        return redirect()->back()->with('success', $mensaje);
    }
    public function asignarColorServicio(Request $request, $ordenId, $servicioId)
    {
        $user = auth()->user();

        // Validar que el usuario sea supervisor o admin
        if (!$user->hasRole('supervisor') && !$user->hasRole('admin')) {
            return redirect()->back()->with('error', 'No tienes permiso para asignar colores.');
        }

        // Validar el color proporcionado
        $request->validate([
            'color' => 'required|in:verde,amarillo,azul,rosado,morado',
        ]);

        // Buscar el registro en la tabla pivote
        $ordenServicio = \DB::table('orden_servicio')
            ->where('orden_trabajo_id', $ordenId)
            ->where('servicio_id', $servicioId)
            ->first();

        if (!$ordenServicio) {
            return redirect()->back()->with('error', 'El servicio no existe en esta orden.');
        }

        // Actualizar el color del servicio
        \DB::table('orden_servicio')
            ->where('orden_trabajo_id', $ordenId)
            ->where('servicio_id', $servicioId)
            ->update(['color' => $request->color]);

        return redirect()->back()->with('success', 'Color asignado correctamente.');
    }

    public function agregarHorarioColor(Request $request, $ordenId, $servicioId)
    {
        $user = auth()->user();

        if (!$user->hasRole('supervisor') && !$user->hasRole('admin')) {
            return redirect()->back()->with('error', 'No tienes permiso para asignar horarios y colores.');
        }

        $request->validate([
            'color' => 'required|in:verde,amarillo,azul,rosado,morado',
            'hora_inicio' => 'nullable|date_format:H:i',
            'hora_fin' => 'nullable|date_format:H:i|after:hora_inicio',
        ]);

        ServicioHorario::create([
            'orden_trabajo_id' => $ordenId,
            'servicio_id' => $servicioId,
            'color' => $request->color,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        return redirect()->back()->with('success', 'Horario y color asignados correctamente.');
    }

    public function eliminarHorarioColor($horarioId)
    {
        $user = auth()->user();

        if (!$user->hasRole('supervisor') && !$user->hasRole('admin')) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar horarios y colores.');
        }

        $horario = ServicioHorario::findOrFail($horarioId);
        $horario->delete();

        return redirect()->back()->with('success', 'Horario y color eliminados correctamente.');
    }
}
