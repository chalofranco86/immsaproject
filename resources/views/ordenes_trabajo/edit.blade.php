<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Orden de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .readonly-field {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        .badge-estado {
            font-size: 0.9rem;
            padding: 0.5em 0.75em;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('title', 'Editar Orden de Trabajo')

@section('content')
<div class="container mt-5">
    <h2>Editar Orden de Trabajo: {{ $orden->numero_orden }}</h2>
    
    <form action="{{ route('ordenes_trabajo.update', $orden->id) }}" method="POST" id="orden-form">
        @csrf
        @method('PUT')
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Información Básica
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="numero_orden" class="form-label">Número de Orden:</label>
                        <input type="text" class="form-control" id="numero_orden" name="numero_orden" value="{{ $orden->numero_orden }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="propietario_id" class="form-label">Propietario:</label>
                        <select class="form-select" id="propietario_id" name="propietario_id" required>
                            <option value="">Selecciona un propietario</option>
                            @foreach($propietarios as $propietario)
                                <option value="{{ $propietario->id }}" {{ $orden->propietario_id == $propietario->id ? 'selected' : '' }}>
                                    {{ $propietario->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_recibido" class="form-label">Fecha Recibido:</label>
                        <input type="date" class="form-control" id="fecha_recibido" name="fecha_recibido" value="{{ $orden->fecha_recibido->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha_entrega" class="form-label">Fecha Entrega (opcional):</label>
                        <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value="{{ $orden->fecha_entrega ? $orden->fecha_entrega->format('Y-m-d') : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ $orden->fecha_fin ? $orden->fecha_fin->format('Y-m-d') : '' }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="empleado_id" class="form-label">Empleado Responsable:</label>
                        <select class="form-select" id="empleado_id" name="empleado_id" required>
                            <option value="">Selecciona un empleado</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id }}" {{ $orden->empleado_id == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado Actual:</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Recibido" {{ $orden->estado == 'Recibido' ? 'selected' : '' }}>Recibido</option>
                            <option value="Revisión" {{ $orden->estado == 'Revisión' ? 'selected' : '' }}>Revisión</option>
                            <option value="Autorizado" {{ $orden->estado == 'Autorizado' ? 'selected' : '' }}>Autorizado</option>
                            <option value="Entregado" {{ $orden->estado == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="Reclamo" {{ $orden->estado == 'Reclamo' ? 'selected' : '' }}>Reclamo</option>

                        </select>
                    </div>
                </div>
            </div>
        </div> 
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Detalles Financieros
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="subtotal" class="form-label">Subtotal:</label>
                        <input type="number" step="0.01" class="form-control readonly-field" id="subtotal" name="subtotal" value="{{ $orden->subtotal }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="descuento" class="form-label">Descuento:</label>
                        <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="{{ $orden->descuento }}" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="total" class="form-label">Total:</label>
                        <input type="number" step="0.01" class="form-control readonly-field" id="total" name="total" value="{{ $orden->total }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="anticipo" class="form-label">Pago/Anticipo:</label>
                        <input type="number" step="0.01" class="form-control" id="anticipo" name="anticipo" value="{{ $orden->anticipo }}" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="saldo" class="form-label">Saldo Pendiente:</label>
                        <input type="number" step="0.01" class="form-control readonly-field" id="saldo" name="saldo" value="{{ $orden->saldo }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Servicios y Observaciones
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label fw-bold">Servicios:</label>
                    <div id="servicios-container">
                        @foreach($orden->servicios as $index => $servicio)
                        <div class="servicio-row row mb-2">
                            <div class="col-md-4">
                                <select class="form-select servicio-select" name="servicios[{{ $index }}][servicio_id]" required>
                                    <option value="">Selecciona un servicio</option>
                                    @foreach($servicios as $serv)
                                        <option value="{{ $serv->id }}" 
                                            data-costo="{{ $serv->costo }}"
                                            {{ $serv->id == $servicio->id ? 'selected' : '' }}>
                                            {{ $serv->tipo_servicio }} (Q{{ number_format($serv->costo, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" class="form-control servicio-costo" 
                                    name="servicios[{{ $index }}][costo]" 
                                    value="{{ $servicio->pivot->costo }}"
                                    placeholder="Costo" required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="servicios[{{ $index }}][responsable]">
                                    <option value="">Selecciona responsable</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->id }}" {{ $servicio->pivot->responsable == $empleado->id ? 'selected' : '' }}>
                                            {{ $empleado->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-servicio">Eliminar</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="add-servicio">Agregar Servicio</button>
                </div>
                
                <div class="mb-3">
                    <label for="observaciones" class="form-label fw-bold">Observaciones:</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="4">{{ $orden->observaciones }}</textarea>
                    <div class="form-text">Notas importantes sobre la orden de trabajo</div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Actualizar Orden
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Función para calcular todos los totales
    function calcularTotales() {
        let subtotal = 0;
        
        // Sumar todos los costos de servicios
        document.querySelectorAll('.servicio-costo').forEach(input => {
            const costo = parseFloat(input.value) || 0;
            subtotal += costo;
        });
        
        // Obtener valores de descuento y anticipo
        const descuento = parseFloat(document.getElementById('descuento').value) || 0;
        const anticipo = parseFloat(document.getElementById('anticipo').value) || 0;
        
        // Calcular total y saldo
        const total = subtotal - descuento;
        const saldo = total - anticipo;
        
        // Actualizar campos
        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
        document.getElementById('saldo').value = saldo.toFixed(2);
    }
    
    // Actualizar costos cuando cambia un servicio
    function actualizarCosto(selectElement) {
        const costoInput = selectElement.closest('.servicio-row').querySelector('.servicio-costo');
        
        // Solo actualizar si el campo de costo está vacío (nuevos servicios)
        if (!costoInput.value || parseFloat(costoInput.value) === 0) {
            const costo = selectElement.options[selectElement.selectedIndex].dataset.costo || 0;
            costoInput.value = parseFloat(costo).toFixed(2);
            calcularTotales();
        }
    }
    
    // Inicializar eventos
    document.addEventListener('DOMContentLoaded', function() {
        // Calcular inicialmente
        calcularTotales();
        
        // Eventos para campos que afectan los cálculos
        document.getElementById('descuento').addEventListener('input', calcularTotales);
        document.getElementById('anticipo').addEventListener('input', calcularTotales);
        
        // Eventos delegados para servicios dinámicos
        document.getElementById('servicios-container').addEventListener('input', function(e) {
            if (e.target.classList.contains('servicio-costo')) {
                calcularTotales();
            }
            if (e.target.classList.contains('servicio-select')) {
                actualizarCosto(e.target);
            }
        });
        
        // Eliminar servicio
        document.querySelectorAll('.remove-servicio').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.servicio-row').remove();
                calcularTotales();
            });
        });
    });
    
    // Agregar nuevo servicio
    document.getElementById('add-servicio').addEventListener('click', function() {
        const container = document.getElementById('servicios-container');
        const rowCount = container.querySelectorAll('.servicio-row').length;
        const newRow = document.createElement('div');
        newRow.className = 'servicio-row row mb-2';
        newRow.innerHTML = `
            <div class="col-md-4">
                <select class="form-select servicio-select" name="servicios[${rowCount}][servicio_id]" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}" data-costo="{{ $servicio->costo }}">
                            {{ $servicio->tipo_servicio }} (Q{{ number_format($servicio->costo, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" class="form-control servicio-costo" name="servicios[${rowCount}][costo]" placeholder="Costo" required>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="servicios[${rowCount}][responsable]">
                    <option value="">Selecciona responsable</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-servicio">Eliminar</button>
            </div>
        `;
        container.appendChild(newRow);
        
        // Evento para actualizar costo al seleccionar
        const select = newRow.querySelector('.servicio-select');
        select.addEventListener('change', function() {
            actualizarCosto(this);
        });
        
        // Evento para eliminar
        newRow.querySelector('.remove-servicio').addEventListener('click', function() {
            newRow.remove();
            calcularTotales();
        });
    });
</script>
@endpush
</body>
</html>