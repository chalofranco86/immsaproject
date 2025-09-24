<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Propietarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Lista de Propietarios</h2>
        <a href="{{ route('propietarios.create') }}" class="btn btn-primary mb-3">Nuevo Propietario</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach($propietarios as $propietario)
                <tr>
                    <td>{{ $propietario->id }}</td>
                    <td>{{ $propietario->nombre }}</td>
                    <td>{{ $propietario->direccion }}</td>
                    <td>{{ $propietario->telefono }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
