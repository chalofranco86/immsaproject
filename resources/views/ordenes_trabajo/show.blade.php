<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Orden {{ $orden->numero_orden }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .color-badge {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }
        .verde { background-color: #28a745; }
        .amarillo { background-color: #ffc107; color: black; }
        .azul { background-color: #007bff; }
        .rosado { background-color: #e83e8c; }
        .morado { background-color: #6f42c1; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Detalle de Orden {{ $orden->numero_orden }}</h2>
            <div>
                <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <a href="{{ route('ordenes_trabajo.pdf', $orden->id) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
                </a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Propietario: {{ $orden->propietario->nombre ?? 'No asignado' }}</h5>
                <p class="card-text">Empleado: {{ $orden->empleado->nombre ?? 'No asignado' }}</p>
                <p class="card-text">Fecha Recibido: {{ $orden->fecha_recibido ? $orden->fecha_recibido->format('d/m/Y') : 'No especificada' }}</p>
                <p class="card-text">Fecha Entrega:
                    @if($orden->fecha_entrega)
                        {{ $orden->fecha_entrega->format('d/m/Y') }}
                    @else
                        <span class="text-muted">Pendiente</span>
                    @endif
                </p>
                <p class="card-text">Fecha Fin: {{ $orden->fecha_fin ? $orden->fecha_fin->format('d/m/Y') : 'No especificada' }}</p>
                <p class="card-text">Subtotal: Q{{ number_format($orden->subtotal, 2) }}</p>
                <p class="card-text">Descuento: Q{{ number_format($orden->descuento, 2) }}</p>
                <p class="card-text">Total: Q{{ number_format($orden->total, 2) }}</p>
                <p class="card-text">Anticipo: Q{{ number_format($orden->anticipo, 2) }}</p>
                <p class="card-text">Saldo: Q{{ number_format($orden->saldo, 2) }}</p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Estado:
                    <span class="badge
                        @if($orden->estado == 'Recibido') bg-primary
                        @elseif($orden->estado == 'Revisión') bg-warning text-dark
                        @elseif($orden->estado == 'Autorizado') bg-info
                        @else bg-success @endif">
                        {{ $orden->estado }}
                    </span>
                </h5>
                <p class="card-text">
                    <strong>Observaciones:</strong><br>
                    {{ $orden->observaciones ?? 'Ninguna' }}
                </p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Servicios Realizados</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Costo</th>
                            <th>Responsable</th>
                            <th>Finalizado</th>
                            <th>Horarios y Colores</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orden->servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->tipo_servicio }}</td>
                            <td>Q{{ number_format($servicio->pivot->costo, 2) }}</td>
                            <td>{{ $servicio->empleadoResponsable->nombre ?? 'No asignado' }}</td>
                            <td>
                                @if($servicio->pivot->finalizado)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-danger">No</span>
                                @endif
                            </td>
                            <td>
                                @foreach($orden->servicioHorarios->where('servicio_id', $servicio->id) as $horario)
                                    <div class="mb-2">
                                        <span class="badge color-badge {{ $horario->color }}">
                                            {{ ucfirst($horario->color) }}
                                            @if($horario->hora_inicio && $horario->hora_fin)
                                                : {{ $horario->hora_inicio->format('H:i') }} - {{ $horario->hora_fin->format('H:i') }}
                                            @elseif($horario->hora_inicio)
                                                : {{ $horario->hora_inicio->format('H:i') }} -
                                            @endif
                                        </span>
                                        @if(auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('admin'))
                                            <form method="POST" action="{{ route('ordenes_trabajo.eliminar_horario_color', $horario->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                                @if(auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('admin'))
                                    <form method="POST" action="{{ route('ordenes_trabajo.agregar_horario_color', ['orden' => $orden->id, 'servicio' => $servicio->id]) }}" style="display: flex; gap: 5px;">
                                        @csrf
                                        <select name="color" class="form-select form-select-sm" style="width: auto;" required>
                                            <option value="">Seleccionar color</option>
                                            <option value="verde">Verde (9:30 am - 11:29 am)</option>
                                            <option value="amarillo">Amarillo (11:30 am - 14:29 hrs)</option>
                                            <option value="azul">Azul (14:30 hrs - 16:29 hrs)</option>
                                            <option value="rosado">Rosado (16:30 hrs - 17:59 hrs)</option>
                                            <option value="morado">Morado (18:00 hrs - 20:00 hrs)</option>
                                        </select>
                                        <input type="time" name="hora_inicio" class="form-control form-control-sm" style="width: auto;">
                                        <input type="time" name="hora_fin" class="form-control form-control-sm" style="width: auto;">
                                        <button type="submit" class="btn btn-sm btn-primary">Agregar</button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($servicio->pivot->finalizado)
                                    <span class="badge bg-success">Sí</span>
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                                        <form action="{{ route('ordenes_trabajo.marcar_finalizado', ['orden' => $orden->id, 'servicio' => $servicio->id]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning">Marcar como NO finalizado</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="badge bg-danger">No</span>
                                    @if(auth()->user()->empleado_id == $servicio->pivot->responsable || auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                                        <form action="{{ route('ordenes_trabajo.marcar_finalizado', ['orden' => $orden->id, 'servicio' => $servicio->id]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-primary">Marcar como finalizado</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No hay servicios asociados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
