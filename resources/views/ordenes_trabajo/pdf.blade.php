<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Trabajo {{ $orden->numero_orden }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 15px;
        }
        .badge {
            padding: 5px;
            border-radius: 3px;
            font-weight: bold;
        }
        .bg-primary { background-color: #007bff; color: white; }
        .bg-warning { background-color: #ffc107; color: black; }
        .bg-info { background-color: #17a2b8; color: white; }
        .bg-success { background-color: #28a745; color: white; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .text-muted {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orden de Trabajo: {{ $orden->numero_orden }}</h1>
        <p>Fecha de Emisión: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <h3>Información Básica</h3>
        <p><strong>Propietario:</strong> {{ $orden->propietario->nombre ?? 'No asignado' }}</p>
        <p><strong>Empleado:</strong> {{ $orden->empleado->nombre ?? 'No asignado' }}</p>
        <p><strong>Fecha Recibido:</strong> {{ $orden->fecha_recibido ? $orden->fecha_recibido->format('d/m/Y') : 'No especificada' }}</p>
        <p><strong>Fecha Entrega:</strong> 
            @if($orden->fecha_entrega)
                {{ $orden->fecha_entrega->format('d/m/Y') }}
            @else
                <span class="text-muted">Pendiente</span>
            @endif
        </p>
        <p><strong>Fecha Fin:</strong> {{ $orden->fecha_fin ? $orden->fecha_fin->format('d/m/Y') : 'No especificada' }}</p>
    </div>

    <div class="section">
        <h3>Estado y Observaciones</h3>
        <p><strong>Estado:</strong> 
            <span class="badge 
                @if($orden->estado == 'Recibido') bg-primary
                @elseif($orden->estado == 'Revisión') bg-warning
                @elseif($orden->estado == 'Autorizado') bg-info
                @else bg-success @endif">
                {{ $orden->estado }}
            </span>
        </p>
        <p><strong>Observaciones:</strong><br>
            {{ $orden->observaciones ?? 'Ninguna' }}
        </p>
    </div>

    <div class="section">
        <h3>Detalles Financieros</h3>
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>Q{{ number_format($orden->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Descuento:</strong></td>
                <td>Q{{ number_format($orden->descuento, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td>Q{{ number_format($orden->total, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Anticipo:</strong></td>
                <td>Q{{ number_format($orden->anticipo, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Saldo Pendiente:</strong></td>
                <td>Q{{ number_format($orden->saldo, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Servicios Realizados</h3>
        <table>
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Costo</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orden->servicios as $servicio)
                <tr>
                    <td>{{ $servicio->tipo_servicio }}</td>
                    <td>Q{{ number_format($servicio->pivot->costo, 2) }}</td>
                    <td>
                        @php
                            // Obtener el nombre del responsable si existe
                            $responsableId = $servicio->pivot->responsable;
                            $responsable = $empleadosResponsables->get($responsableId);
                        @endphp
                        {{ $responsable ? $responsable->nombre : 'No asignado' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">No hay servicios asociados.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Total: Q{{ number_format($orden->subtotal, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>