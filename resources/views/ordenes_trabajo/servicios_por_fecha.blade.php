<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios por Fecha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Botón para regresar a órdenes de trabajo -->
        <div class="mb-3">
            <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver a Órdenes de Trabajo
            </a>
        </div>

        <h1>Servicios por Fecha (Estado: Autorizado)</h1>
        
        <!-- Formulario de filtro -->
        <form method="GET" action="{{ route('ordenes_trabajo.servicios_por_fecha') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Seleccionar Fecha:</label>
                    <input type="date" name="fecha" id="fecha" value="{{ $fecha }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        @if($serviciosPorFecha->count() > 0)
            <!-- Mostrar servicios agrupados por responsable -->
            @foreach($serviciosPorFecha as $responsableNombre => $servicios)
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Responsable: {{ $responsableNombre }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Orden de Trabajo</th>
                                        <th>Servicio</th>
                                        <th>Propietario</th>
                                        <th>Serie Motor</th>
                                        <th>Observaciones</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servicios as $servicio)
                                        <tr>
                                            <td>
                                                @if($servicio->ordenTrabajo)
                                                    {{ $servicio->ordenTrabajo->numero_orden }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($servicio->servicio)
                                                    {{ $servicio->servicio->tipo_servicio }}
                                                @else
                                                    Servicio eliminado
                                                @endif
                                            </td>
                                            <td>
                                                @if($servicio->ordenTrabajo && $servicio->ordenTrabajo->propietario)
                                                    {{ $servicio->ordenTrabajo->propietario->nombre }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($servicio->ordenTrabajo)
                                                    {{ $servicio->ordenTrabajo->serie_motor ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($servicio->ordenTrabajo)
                                                    {{ Str::limit($servicio->ordenTrabajo->observaciones, 50) ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $servicio->finalizado ? 'success' : 'warning' }}">
                                                    {{ $servicio->finalizado ? 'Finalizado' : 'Pendiente' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Botón para generar reporte PDF -->
            <div class="mt-3">
                <a href="{{ route('ordenes_trabajo.generar_reporte_servicios_por_fecha', ['fecha' => $fecha]) }}" 
                   class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i> Generar PDF
                </a>
            </div>
        @else
            <div class="alert alert-info">
                No hay servicios autorizados registrados para la fecha {{ $fecha }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>