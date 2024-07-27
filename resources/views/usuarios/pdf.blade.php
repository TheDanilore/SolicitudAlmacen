<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .estado-activo {
            background-color: rgb(23, 211, 83);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .estado-desactivado {
            background-color: rgb(244, 30, 63);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 50px;
            max-height: 50px;
        }
    </style>
</head>

<body>
    <h1>Lista de Usuarios</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Apodo</th>
                <th>Correo</th>
                <th>Persona</th>
                <th>Rol</th>
                <th>Unidad</th>
                <th>Dependencia</th>
                <th>Avatar</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->persona->primer_nombre }} {{ $usuario->persona->segundo_nombre }} {{ $usuario->persona->apellido_paterno }} {{ $usuario->persona->apellido_materno }}</td>
                <td>{{ $usuario->roles->pluck('name')->implode(', ') }}</td>
                <td>{{ $usuario->unidad->descripcion ?? 'N/A' }}</td>
                <td>{{ $usuario->unidad->dependencia->nombre ?? 'N/A' }}</td>
                <td>{!! $usuario->avatar ? '<img src="' . public_path($usuario->avatar) . '">' : 'N/A' !!}</td>
                <td>
                    <div class='text-center'>
                        @if ($usuario->estadoUsuario->descripcion == 'Activo')
                        <span class="estado-activo">{{ $usuario->estadoUsuario->descripcion }}</span>
                        @elseif ($usuario->estadoUsuario->descripcion == 'Inactivo')
                        <span class="estado-desactivado">{{ $usuario->estadoUsuario->descripcion }}</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>