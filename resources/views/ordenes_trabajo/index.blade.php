<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Órdenes de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem;
        }
        
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .filter-group {
            margin-bottom: 0;
        }
        
        .filter-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
            font-size: 0.9rem;
        }
        
        .status-badge {
            padding: 0.5em 0.75em;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
            min-width: 90px;
            text-align: center;
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
        
        .action-btn {
            padding: 0.3rem 0.5rem;
            font-size: 0.9rem;
        }
        
        .action-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .pagination .page-link {
            color: var(--primary);
        }
        
        .summary-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        /* Responsive styles */
        @media (max-width: 992px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                text-align: center;
            }
            
            .table-header > div {
                margin-bottom: 1rem;
            }
            
            .table-header .btn {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 0.5rem;
            }
            
            .action-group {
                flex-direction: column;
                gap: 0.3rem;
            }
            
            .action-group .btn {
                width: 100%;
                justify-content: center;
            }
            
            .status-badge {
                min-width: auto;
                display: block;
            }
            
            .filter-section {
                padding: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .table th:nth-child(3),
            .table td:nth-child(3),
            .table th:nth-child(6),
            .table td:nth-child(6) {
                display: none;
            }
            
            .pagination {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body class="bg-light">
@extends('layouts.app')

@section('title', 'Lista de Órdenes de Trabajo')

@section('content')
<div class="container py-4">
    <div class="table-container">
        <div class="table-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Órdenes de Trabajo</h2>
                <p class="mb-0 opacity-75">Administración de todas las órdenes registradas</p>
            </div>
            <div>
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                    <a href="{{ route('ordenes_trabajo.create') }}" class="btn btn-light text-primary me-2">
                        <i class="fas fa-plus me-1"></i> Nueva Orden
                    </a>
                    <!-- Botón para generar PDF -->
                    <button id="generatePdfBtn" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-1"></i> Generar PDF
                    </button>
                @endif
            </div>
        </div>
        
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Sección de filtros reorganizada -->
            <div class="filter-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros</h5>
                    <button class="btn btn-sm btn-outline-secondary" id="resetFilters">
                        <i class="fas fa-sync-alt me-1"></i> Limpiar filtros
                    </button>
                </div>
                
                <div class="filter-grid">
                    <!-- Filtro de búsqueda -->
                    <div class="filter-group">
                        <div class="filter-label">Búsqueda general</div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Buscar por número, propietario...">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filtro de estado -->
                    <div class="filter-group">
                        <div class="filter-label">Estado de la orden</div>
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos los estados</option>
                            <option value="Recibido">Recibido</option>
                            <option value="Revisión">Revisión</option>
                            <option value="Autorizado">Autorizado</option>
                            <option value="Entregado">Entregado</option>
                        </select>
                    </div>
                    
                    <!-- Filtro de fechas -->
                    <div class="filter-group">
                        <div class="filter-label">Fecha de recepción</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" class="form-control" id="fechaInicio" placeholder="Inicio">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" id="fechaFin" placeholder="Fin">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtro de pago -->
                    <div class="filter-group">
                        <div class="filter-label">Estado de pago</div>
                        <select class="form-select" id="saldoFilter">
                            <option value="">Todos</option>
                            <option value="pendiente">Con saldo pendiente</option>
                            <option value="pagado">Pagado completo</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Resumen de estadísticas -->
            <div class="summary-card">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="d-flex flex-column align-items-center">
                            <span class="fs-4 fw-bold" id="totalCount">{{ count($ordenes) }}</span>
                            <span class="text-muted">Total Órdenes</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="d-flex flex-column align-items-center">
                            <span class="fs-4 fw-bold text-primary" id="receivedCount">{{ $ordenes->where('estado', 'Recibido')->count() }}</span>
                            <span class="text-muted">Recibidas</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex flex-column align-items-center">
                            <span class="fs-4 fw-bold text-success" id="deliveredCount">{{ $ordenes->where('estado', 'Entregado')->count() }}</span>
                            <span class="text-muted">Entregadas</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex flex-column align-items-center">
                            <span class="fs-4 fw-bold text-danger" id="pendingCount">{{ $ordenes->where('saldo', '>', 0)->count() }}</span>
                            <span class="text-muted">Pendientes de pago</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de órdenes -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable" data-sort="numero_orden">
                                <span>Orden</span>
                                <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th>Propietario</th>
                            <th class="d-none d-md-table-cell">Empleado</th>
                            <th class="sortable" data-sort="fecha_recibido">
                                <span>Recibido</span>
                                <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="sortable" data-sort="fecha_entrega">
                                <span>Entrega</span>
                                <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="sortable" data-sort="total">
                                <span>Total</span>
                                <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th>Saldo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordenes as $orden)
                        <tr data-id="{{ $orden->id }}" 
                            data-estado="{{ $orden->estado }}" 
                            data-fecha-recidibo="{{ \Carbon\Carbon::parse($orden->fecha_recibido)->format('Y-m-d') }}"
                            data-saldo="{{ $orden->saldo > 0 ? 'pendiente' : 'pagado' }}"
                            data-text="{{ strtolower($orden->numero_orden . ' ' . $orden->propietario->nombre . ' ' . $orden->empleado->nombre) }}">
                            <td>
                                <div class="fw-bold">{{ $orden->numero_orden }}</div>
                                <small class="text-muted d-md-none">#{{ $orden->id }}</small>
                            </td>
                            <td>
                                <div>{{ $orden->propietario->nombre }}</div>
                                <small class="text-muted d-md-none">ID: {{ $orden->propietario->id }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div>{{ $orden->empleado->nombre }}</div>
                                <small class="text-muted">ID: {{ $orden->empleado->id }}</small>
                            </td>
                            <td>
                                <div>{{ \Carbon\Carbon::parse($orden->fecha_recibido)->format('d/m/Y') }}</div>
                                <small class="text-muted d-md-none">{{ \Carbon\Carbon::parse($orden->fecha_recibido)->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($orden->fecha_entrega)
                                    <div>{{ \Carbon\Carbon::parse($orden->fecha_entrega)->format('d/m/Y') }}</div>
                                    <small class="text-muted d-md-none">{{ \Carbon\Carbon::parse($orden->fecha_entrega)->diffForHumans() }}</small>
                                @else
                                    <div class="text-warning">Pendiente</div>
                                @endif
                            </td>
                            <td class="fw-bold">Q{{ number_format($orden->total, 2) }}</td>
                            <td>
                                @if($orden->saldo > 0)
                                    <span class="badge bg-danger">Q{{ number_format($orden->saldo, 2) }}</span>
                                @else
                                    <span class="badge bg-success">Pagado</span>
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
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('ordenes_trabajo.show', $orden->id) }}" 
                                    class="btn btn-sm btn-outline-primary action-btn" 
                                    title="Ver detalles">
                                        <i class="fas fa-eye d-none d-md-inline"></i>
                                        <span class="d-md-none">Ver</span>
                                    </a>
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                                        <a href="{{ route('ordenes_trabajo.edit', $orden->id) }}"
                                        class="btn btn-sm btn-outline-warning action-btn"
                                        title="Editar">
                                            <i class="fas fa-edit d-none d-md-inline"></i>
                                            <span class="d-md-none">Editar</span>
                                        </a>
                                    @endif
                                    @if(auth()->user()->hasRole('admin'))
                                        <form action="{{ route('ordenes_trabajo.destroy', $orden->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta orden?')">
                                                <i class="fas fa-trash d-none d-md-inline"></i>
                                                <span class="d-md-none">Eliminar</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Sin resultados -->
            <div class="text-center py-5 d-none" id="noResults">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>No se encontraron órdenes</h4>
                <p class="text-muted">Intenta ajustar tus filtros o términos de búsqueda</p>
                <button class="btn btn-primary mt-2" id="resetFiltersBtn">
                    <i class="fas fa-sync-alt me-1"></i> Limpiar filtros
                </button>
            </div>
            
            <!-- Paginación -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4">
                <div class="text-muted mb-2 mb-sm-0">
                    Mostrando <span id="showingCount">{{ count($ordenes) }}</span> de 
                    <span id="totalCount">{{ count($ordenes) }}</span> registros
                </div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos de filtro
        const filterElements = {
            search: document.getElementById('searchInput'),
            status: document.getElementById('statusFilter'),
            fechaInicio: document.getElementById('fechaInicio'),
            fechaFin: document.getElementById('fechaFin'),
            saldo: document.getElementById('saldoFilter')
        };
        
        // Botones
        const searchBtn = document.getElementById('searchBtn');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const resetFiltersBtn2 = document.getElementById('resetFiltersBtn');
        
        // Estado actual de los filtros
        const filters = {
            search: '',
            status: '',
            fechaInicio: '',
            fechaFin: '',
            saldo: ''
        };
        
        // Event listeners
        searchBtn.addEventListener('click', applyFilters);
        resetFiltersBtn.addEventListener('click', resetFilters);
        resetFiltersBtn2.addEventListener('click', resetFilters);
        
        filterElements.search.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') applyFilters();
        });
        
        filterElements.status.addEventListener('change', applyFilters);
        filterElements.fechaInicio.addEventListener('change', applyFilters);
        filterElements.fechaFin.addEventListener('change', applyFilters);
        filterElements.saldo.addEventListener('change', applyFilters);
        
        // Función para aplicar filtros
        function applyFilters() {
            // Actualizar valores de filtro
            filters.search = filterElements.search.value.toLowerCase();
            filters.status = filterElements.status.value;
            filters.fechaInicio = filterElements.fechaInicio.value;
            filters.fechaFin = filterElements.fechaFin.value;
            filters.saldo = filterElements.saldo.value;
            
            const rows = document.querySelectorAll('#ordersTable tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const rowData = {
                    estado: row.getAttribute('data-estado'),
                    fechaRecibido: row.getAttribute('data-fecha-recidibo'),
                    saldo: row.getAttribute('data-saldo'),
                    text: row.getAttribute('data-text')
                };
                
                // Aplicar filtros
                const matchesSearch = !filters.search || rowData.text.includes(filters.search);
                const matchesStatus = !filters.status || rowData.estado === filters.status;
                
                // Filtro de fechas
                let matchesDate = true;
                if (filters.fechaInicio && filters.fechaFin) {
                    matchesDate = rowData.fechaRecibido >= filters.fechaInicio && 
                                  rowData.fechaRecibido <= filters.fechaFin;
                } else if (filters.fechaInicio) {
                    matchesDate = rowData.fechaRecibido >= filters.fechaInicio;
                } else if (filters.fechaFin) {
                    matchesDate = rowData.fechaRecibido <= filters.fechaFin;
                }
                
                // Filtro de saldo
                const matchesSaldo = !filters.saldo || rowData.saldo === filters.saldo;
                
                // Mostrar u ocultar fila según los filtros
                if (matchesSearch && matchesStatus && matchesDate && matchesSaldo) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            updateVisibility(visibleCount);
            updateCounters();
        }
        
        // Función para resetear filtros
        function resetFilters() {
            filterElements.search.value = '';
            filterElements.status.value = '';
            filterElements.fechaInicio.value = '';
            filterElements.fechaFin.value = '';
            filterElements.saldo.value = '';
            
            filters.search = '';
            filters.status = '';
            filters.fechaInicio = '';
            filters.fechaFin = '';
            filters.saldo = '';
            
            applyFilters();
        }
        
        // Actualizar visibilidad de la tabla
        function updateVisibility(visibleCount) {
            const noResults = document.getElementById('noResults');
            const tableContainer = document.querySelector('.table-responsive');
            
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
                tableContainer.classList.add('d-none');
            } else {
                noResults.classList.add('d-none');
                tableContainer.classList.remove('d-none');
            }
            
            document.getElementById('showingCount').textContent = visibleCount;
        }
        
        // Actualizar contadores
        function updateCounters() {
            const rows = document.querySelectorAll('#ordersTable tbody tr:not([style*="display: none"])');
            
            // Contar estados
            let received = 0;
            let delivered = 0;
            let pending = 0;
            
            rows.forEach(row => {
                const status = row.getAttribute('data-estado');
                const saldo = row.getAttribute('data-saldo');
                
                if (status === 'Recibido') received++;
                if (status === 'Entregado') delivered++;
                if (saldo === 'pendiente') pending++;
            });
            
            document.getElementById('receivedCount').textContent = received;
            document.getElementById('deliveredCount').textContent = delivered;
            document.getElementById('pendingCount').textContent = pending;
        }
        
        // Ordenamiento de columnas
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                const column = this.getAttribute('data-sort');
                const isAsc = this.classList.contains('asc');
                
                // Resetear todos los indicadores
                document.querySelectorAll('.sortable i').forEach(icon => {
                    icon.className = 'fas fa-sort ms-1';
                });
                
                // Actualizar indicador actual
                const icon = this.querySelector('i');
                icon.className = isAsc ? 'fas fa-sort-down ms-1' : 'fas fa-sort-up ms-1';
                
                // Alternar clase
                this.classList.toggle('asc');
                
                // Aquí iría la lógica para ordenar la tabla
                console.log('Ordenando por:', column, isAsc ? 'DESC' : 'ASC');
            });
        });
        
        // Función para generar PDF con filtros aplicados
        document.getElementById('generatePdfBtn').addEventListener('click', function() {
            // Crear un formulario temporal para enviar los filtros
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route("ordenes_trabajo.generateReport") }}';
            form.target = '_blank'; // Abrir en nueva pestaña
            
            // Agregar los filtros actuales como campos ocultos
            const currentFilters = {
                search: filters.search,
                status: filters.status,
                fecha_inicio: filters.fechaInicio,
                fecha_fin: filters.fechaFin,
                saldo: filters.saldo
            };
            
            for (const [key, value] of Object.entries(currentFilters)) {
                if (value) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
            }
            
            // Agregar el formulario al documento y enviarlo
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });
    });
</script>
@endpush
</body>
</html>