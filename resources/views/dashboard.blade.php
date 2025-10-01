@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    @if(auth()->user()->hasRole('admin'))
        <div class="container">
            <h1>Bienvenido al Dashboard</h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Órdenes de Trabajo</h5>
                            <p class="card-text">Total: {{ $totalOrdenes }}</p>
                            <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-primary">Ver Órdenes</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Propietarios</h5>
                            <p class="card-text">Total: {{ $totalPropietarios }}</p>
                            <a href="{{ route('propietarios.index') }}" class="btn btn-primary">Ver Propietarios</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Empleados</h5>
                            <p class="card-text">Total: {{ $totalEmpleados }}</p>
                            <a href="{{ route('empleados.index') }}" class="btn btn-primary">Ver Empleados</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Órdenes Recibidas</h5>
                            <p class="card-text">{{ $ordenesRecibidas }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Órdenes en Revisión</h5>
                            <p class="card-text">{{ $ordenesRevision }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Órdenes Autorizadas</h5>
                            <p class="card-text">{{ $ordenesAutorizadas }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Órdenes Entregadas</h5>
                            <p class="card-text">{{ $ordenesEntregadas }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Ingresos Totales</h5>
                            <p class="card-text">Q{{ number_format($ingresosTotales, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pagos Pendientes</h5>
                            <p class="card-text">Q{{ number_format($pagosPendientes, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Estado de Órdenes</h5>
                            <canvas id="estadoOrdenesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Servicios Más Solicitados</h5>
                            <canvas id="serviciosSolicitadosChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Órdenes por Empleado</h5>
                            <canvas id="ordenesPorEmpleadoChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Resumen de Colores de Servicios</h5>
                            <canvas id="coloresServiciosChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Próximas Fechas de Entrega</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Número de Orden</th>
                                        <th>Propietario</th>
                                        <th>Fecha de Entrega</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proximasEntregas as $orden)
                                    <tr>
                                        <td>{{ $orden->numero_orden }}</td>
                                        <td>{{ $orden->propietario->nombre }}</td>
                                        <td>{{ $orden->fecha_entrega->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge
                                                @if($orden->estado == 'Recibido') bg-primary
                                                @elseif($orden->estado == 'Revisión') bg-warning text-dark
                                                @elseif($orden->estado == 'Autorizado') bg-info
                                                @else bg-success @endif">
                                                {{ $orden->estado }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <h1>Bienvenido al Dashboard</h1>
            <div class="alert alert-info">
                No tienes permisos para ver esta información.
            </div>
        </div>
    @endif
@endsection
@section('scripts')
    @if(auth()->user()->hasRole('admin'))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Estado de Órdenes
            const estadoOrdenesCtx = document.getElementById('estadoOrdenesChart').getContext('2d');
            const estadoOrdenesChart = new Chart(estadoOrdenesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Recibidas', 'Revisión', 'Autorizadas', 'Entregadas'],
                    datasets: [{
                        data: [
                            {{ $ordenesRecibidas }},
                            {{ $ordenesRevision }},
                            {{ $ordenesAutorizadas }},
                            {{ $ordenesEntregadas }}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(75, 192, 75, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(75, 192, 75, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });

            // Servicios Más Solicitados
            const serviciosSolicitadosCtx = document.getElementById('serviciosSolicitadosChart').getContext('2d');
            const serviciosSolicitadosChart = new Chart(serviciosSolicitadosCtx, {
                type: 'bar',
                data: {
                    labels: {!! $serviciosSolicitados->pluck('tipo_servicio')->toJson() !!},
                    datasets: [{
                        label: 'Número de Órdenes',
                        data: {!! $serviciosSolicitados->pluck('ordenes_count')->toJson() !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Órdenes por Empleado
            const ordenesPorEmpleadoCtx = document.getElementById('ordenesPorEmpleadoChart').getContext('2d');
            const ordenesPorEmpleadoChart = new Chart(ordenesPorEmpleadoCtx, {
                type: 'bar',
                data: {
                    labels: {!! $ordenesPorEmpleado->pluck('nombre')->toJson() !!},
                    datasets: [{
                        label: 'Número de Órdenes',
                        data: {!! $ordenesPorEmpleado->pluck('ordenes_asignadas_count')->toJson() !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Resumen de Colores de Servicios
            const coloresServiciosCtx = document.getElementById('coloresServiciosChart').getContext('2d');
            const coloresServiciosChart = new Chart(coloresServiciosCtx, {
                type: 'pie',
                data: {
                    labels: {!! $coloresServicios->pluck('color')->transform(function($item, $key) {
                        return ucfirst($item);
                    })->toJson() !!},
                    datasets: [{
                        data: {!! $coloresServicios->pluck('total')->toJson() !!},
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.7)',    // verde
                            'rgba(255, 193, 7, 0.7)',    // amarillo
                            'rgba(0, 123, 255, 0.7)',    // azul
                            'rgba(232, 62, 140, 0.7)',   // rosado
                            'rgba(111, 66, 193, 0.7)'    // morado
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(0, 123, 255, 1)',
                            'rgba(232, 62, 140, 1)',
                            'rgba(111, 66, 193, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        </script>
    @endif
@endsection
