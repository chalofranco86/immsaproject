<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Orden de Trabajo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .readonly-field {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
@extends('layouts.app')

@section('title', 'Crear Orden de Trabajo')

@section('content')
<div class="container mt-5">
    <h2>Crear Orden de Trabajo</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('ordenes_trabajo.store') }}" method="POST" id="orden-form">
        @csrf
        <div class="row">
        <div class="col-md-6 mb-3">
            <label for="numero_orden" class="form-label">Número de Orden:</label>
            <input type="text" class="form-control" id="numero_orden" name="numero_orden" value="{{ $numeroOrden }}">
        </div>
            <div class="col-md-5 mb-3">
                <label for="propietario_id" class="form-label">Propietario:</label>
                <div class="d-flex">
                    <select class="form-select select2-propietario" id="propietario_id" name="propietario_id" required>
                        <option value="">Selecciona un propietario</option>
                        @foreach($propietarios as $propietario)
                            <option value="{{ $propietario->id }}">{{ $propietario->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoPropietarioModal">
                        Agregar Nuevo
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha_recibido" class="form-label">Fecha Recibido:</label>
                <input type="date" class="form-control" id="fecha_recibido" name="fecha_recibido" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="fecha_entrega" class="form-label">Fecha Entrega (opcional):</label>
                <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega">
            </div>
            <div class="col-md-6 mb-3">
                <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="subtotal" class="form-label">Subtotal:</label>
                <input type="number" step="0.01" class="form-control readonly-field" id="subtotal" name="subtotal" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="descuento" class="form-label">Descuento:</label>
                <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="0" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="total" class="form-label">Total:</label>
                <input type="number" step="0.01" class="form-control readonly-field" id="total" name="total" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="anticipo" class="form-label">Anticipo:</label>
                <input type="number" step="0.01" class="form-control" id="anticipo" name="anticipo" value="0" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="saldo" class="form-label">Saldo:</label>
                <input type="number" step="0.01" class="form-control readonly-field" id="saldo" name='saldo' readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="empleado_id" class="form-label">Empleado:</label>
                <select class="form-select" id="empleado_id" name="empleado_id" required>
                    <option value="">Selecciona un empleado</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Servicios:</label>
            <div id="servicios-container">
                @foreach(old('servicios', [['servicio_id' => '', 'costo' => '', 'responsable' => '']]) as $index => $servicio)
                <div class="servicio-row row mb-2">
                    <div class="col-md-4">
                        <select class="form-select servicio-select" name="servicios[{{ $index }}][servicio_id]" required>
                            <option value="">Selecciona un servicio</option>
                            @foreach($servicios as $serv)
                                <option value="{{ $serv->id }}" data-costo="{{ $serv->costo }}" {{ $serv->id == $servicio['servicio_id'] ? 'selected' : '' }}>
                                    {{ $serv->tipo_servicio }} (Q{{ number_format($serv->costo, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" class="form-control servicio-costo" name="servicios[{{ $index }}][costo]"
                            placeholder="Costo" value="{{ $servicio['costo'] }}" required>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="servicios[{{ $index }}][responsable]" >
                            <option value="">Selecciona responsable</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id }}" {{ $empleado->id == $servicio['responsable'] ? 'selected' : '' }}>
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
            <button type="button" class="btn btn-secondary" id="add-servicio">Agregar Servicio</button>
        </div>
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones:</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
    </form>
</div>

<!-- Modal para agregar nuevo propietario -->
<!-- Modal para agregar nuevo propietario -->
<div class="modal fade" id="nuevoPropietarioModal" tabindex="-1" aria-labelledby="nuevoPropietarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoPropietarioModalLabel">Agregar Nuevo Propietario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoPropietario">
                    @csrf
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Propietario *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección *</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono *</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarPropietario">Guardar</button>
            </div>
        </div>
    </div>
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
        const costo = selectElement.options[selectElement.selectedIndex].dataset.costo || 0;
        const costoInput = selectElement.closest('.servicio-row').querySelector('.servicio-costo');
        costoInput.value = parseFloat(costo).toFixed(2);
        calcularTotales();
    }

    // Inicializar eventos
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Select2 en el campo de propietarios
        $('.select2-propietario').select2({
            placeholder: "Selecciona un propietario",
            allowClear: true,
            width: 'resolve'
        });

        // Resto de tu código JavaScript existente
        function calcularTotales() {
            let subtotal = 0;

            document.querySelectorAll('.servicio-costo').forEach(input => {
                const costo = parseFloat(input.value) || 0;
                subtotal += costo;
            });

            const descuento = parseFloat(document.getElementById('descuento').value) || 0;
            const anticipo = parseFloat(document.getElementById('anticipo').value) || 0;

            const total = subtotal - descuento;
            const saldo = total - anticipo;

            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('total').value = total.toFixed(2);
            document.getElementById('saldo').value = saldo.toFixed(2);
        }

        function actualizarCosto(selectElement) {
            const costo = selectElement.options[selectElement.selectedIndex].dataset.costo || 0;
            const costoInput = selectElement.closest('.servicio-row').querySelector('.servicio-costo');
            costoInput.value = parseFloat(costo).toFixed(2);
            calcularTotales();
        }

        // Inicializar eventos
        calcularTotales();

        document.getElementById('descuento').addEventListener('input', calcularTotales);
        document.getElementById('anticipo').addEventListener('input', calcularTotales);

        document.getElementById('servicios-container').addEventListener('input', function(e) {
            if (e.target.classList.contains('servicio-costo')) {
                calcularTotales();
            }
            if (e.target.classList.contains('servicio-select')) {
                actualizarCosto(e.target);
            }
        });

        document.querySelectorAll('.servicio-select').forEach(select => {
            actualizarCosto(select);
        });

        document.getElementById('guardarPropietario').addEventListener('click', function() {
            const nombre = document.getElementById('nombre').value;
            const direccion = document.getElementById('direccion').value;
            const telefono = document.getElementById('telefono').value;

            if (!nombre || !direccion || !telefono) {
                alert('Por favor completa todos los campos obligatorios');
                return;
            }

            fetch('{{ route("propietarios.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nombre: nombre,
                    direccion: direccion,
                    telefono: telefono
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Error en la solicitud');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const select = $('#propietario_id');
                    const option = new Option(data.propietario.nombre, data.propietario.id, false, true);
                    select.append(option).trigger('change');

                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('nuevoPropietarioModal'));
                    modal.hide();

                    // Limpiar el formulario
                    document.getElementById('formNuevoPropietario').reset();

                    alert('Propietario agregado exitosamente');
                } else {
                    alert('Error al agregar el propietario: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al agregar el propietario: ' + error.message);
            });
        });

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
                <div class="col-md-3">
                    <input type="number" step="0.01" class="form-control servicio-costo" name="servicios[${rowCount}][costo]" placeholder="Costo" required>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="servicios[${rowCount}][responsable]" >
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

            const select = newRow.querySelector('.servicio-select');
            select.addEventListener('change', function() {
                actualizarCosto(this);
            });

            newRow.querySelector('.remove-servicio').addEventListener('click', function() {
                newRow.remove();
                calcularTotales();
            });
        });

        document.querySelectorAll('.remove-servicio').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.servicio-row').remove();
                calcularTotales();
            });
        });
    });
</script>
@endpush
</body>
</html>