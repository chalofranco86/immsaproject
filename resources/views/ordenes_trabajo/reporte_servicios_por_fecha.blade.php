<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Servicios por Fecha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-success { background-color: #28a745; color: white; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        .text-muted {
            color: #6c757d;
        }
        .color-badge {
            padding: 2px 4px;
            border-radius: 3px;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }
        .verde { background-color: #28a745; }
        .amarillo { background-color: #ffc107; color: black; }
        .azul { background-color: #007bff; }
        .rosado { background-color: #e83e8c; }
        .morado { background-color: #6f42c1; }
        .small-text {
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Servicios por Fecha - Estado: Autorizado</h1>
        <p>Fecha: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    @forelse($serviciosPorFecha as $empleado => $servicios)
    <div class="section">
        <h3>Empleado: {{ $empleado }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Servicio</th>
                    <th>Propietario</th>
                    <th>Serie Motor</th>
                    <th>Observaciones</th>
                    <th>Estado Servicio</th>
                    <th>Color</th>
                    <th>Horario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->ordenTrabajo->numero_orden }}</td>
                    <td>{{ $servicio->servicio->tipo_servicio }}</td>
                    <td>
                        @if($servicio->ordenTrabajo->propietario)
                            {{ $servicio->ordenTrabajo->propietario->nombre }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $servicio->ordenTrabajo->serie_motor ?? 'N/A' }}</td>
                    <td class="small-text">{{ $servicio->ordenTrabajo->observaciones ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $servicio->finalizado ? 'success' : 'warning' }}">
                            {{ $servicio->finalizado ? 'Finalizado' : 'Pendiente' }}
                        </span>
                    </td>
                    <td>
                        @if($servicio->color)
                            <span class="color-badge {{ $servicio->color }}">
                                {{ ucfirst($servicio->color) }}
                            </span>
                        @else
                            Sin asignar
                        @endif
                    </td>
                    <td>
                        @php
                            $horario = $servicio->ordenTrabajo->servicioHorarios->where('servicio_id', $servicio->servicio_id)->first();
                        @endphp
                        @if($horario && $horario->hora_inicio && $horario->hora_fin)
                            {{ $horario->hora_inicio->format('H:i') }} - {{ $horario->hora_fin->format('H:i') }}
                        @elseif($horario && $horario->hora_inicio)
                            {{ $horario->hora_inicio->format('H:i') }} - 
                        @else
                            Sin horario
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @empty
    <div class="section">
        <p>No se encontraron servicios autorizados para esta fecha.</p>
    </div>
    @endforelse
    
    <div class="footer">
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}</p>
        <p>Filtro aplicado: Estado = Autorizado</p>
    </div>
</body>
</html>