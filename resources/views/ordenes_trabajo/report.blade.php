<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Órdenes de Trabajo</title>
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
        .filters {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .filters h4 {
            margin-bottom: 10px;
        }
        .filters ul {
            margin: 0;
            padding-left: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-recibido {
            background-color: #e0f7fa;
            color: #006064;
        }
        .badge-revision {
            background-color: #fff3e0;
            color: #e65100;
        }
        .badge-autorizado {
            background-color: #e3f2fd;
            color: #0d47a1;
        }
        .badge-entregado {
            background-color: #e8f5e9;
            color: #1b5e20;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Órdenes de Trabajo</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    @if(!empty(array_filter($filters)))
    <div class="filters">
        <h4>Filtros aplicados:</h4>
        <ul>
            @if(!empty($filters['search']))
                <li>Búsqueda: "{{ $filters['search'] }}"</li>
            @endif
            @if(!empty($filters['status']))
                <li>Estado: {{ $filters['status'] }}</li>
            @endif
            @if(!empty($filters['fecha_inicio']))
                <li>Fecha desde: {{ \Carbon\Carbon::parse($filters['fecha_inicio'])->format('d/m/Y') }}</li>
            @endif
            @if(!empty($filters['fecha_fin']))
                <li>Fecha hasta: {{ \Carbon\Carbon::parse($filters['fecha_fin'])->format('d/m/Y') }}</li>
            @endif
            @if(!empty($filters['saldo']))
                <li>Estado de pago: {{ $filters['saldo'] == 'pendiente' ? 'Con saldo pendiente' : 'Pagado completo' }}</li>
            @endif
        </ul>
    </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>Orden</th>
                <th>Serie del Motor</th>
                <th>NIT de Factura</th>
                <th>Propietario</th>
                <th>Empleado</th>
                <th>Fecha Recibido</th>
                <th>Fecha Entrega</th>
                <th>Total</th>
                <th>Saldo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ordenes as $orden)
            <tr>
                <td>{{ $orden->numero_orden }}</td>
                <td>{{ $orden->serie_motor ?? 'No especificada' }}</td>
                <td>{{ $orden->nit_factura ?? 'No especificado' }}</td>
                <td>{{ $orden->propietario->nombre }}</td>
                <td>{{ $orden->empleado->nombre }}</td>
                <td>{{ $orden->fecha_recibido->format('d/m/Y') }}</td>
                <td>
                    @if($orden->fecha_entrega)
                        {{ $orden->fecha_entrega->format('d/m/Y') }}
                    @else
                        Pendiente
                    @endif
                </td>
                <td>Q{{ number_format($orden->total, 2) }}</td>
                <td>
                    @if($orden->saldo > 0)
                        Q{{ number_format($orden->saldo, 2) }}
                    @else
                        Pagado
                    @endif
                </td>
                <td>
                    <span class="status-badge
                        @if($orden->estado == 'Recibido') badge-recibido
                        @elseif($orden->estado == 'Revisión') badge-revision
                        @elseif($orden->estado == 'Autorizado') badge-autorizado
                        @else badge-entregado @endif">
                        {{ $orden->estado }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">No se encontraron órdenes con los filtros aplicados</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}</p>
        <p>Total de órdenes: {{ count($ordenes) }}</p>
    </div>
</body>
</html>
