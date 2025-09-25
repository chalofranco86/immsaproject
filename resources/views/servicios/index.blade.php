<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Servicios</h2>
            <div>
                <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary me-2">
                    <i class="bi bi-house"></i> Inicio
                </a>
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                    <a href="{{ route('servicios.create') }}" class="btn btn-primary">Nuevo Servicio</a>
                @endif
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo de Servicio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->id }}</td>
                    <td>{{ $servicio->tipo_servicio }}</td>
                    <td>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
                            <a href="{{ route('servicios.edit', $servicio->id) }}" class="btn btn-warning btn-sm me-2">
                                Editar
                            </a>
                        @endif
                        @if(auth()->user()->hasRole('admin'))
                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio?')">
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
