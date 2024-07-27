<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Dependencias (Áreas)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            /* Reducir tamaño de fuente */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
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

        .text-center {
            text-align: center;
        }

        img {
            max-width: 50px;
            max-height: 50px;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">Lista de Áreas</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dependencias as $dependencia)
            <tr>
                <td>{{ $dependencia->id }}</td>
                <td>{{ $dependencia->nombre }}</td>
                <td>
                    <div class='text-center'>
                        @if ($dependencia->estado->descripcion == 'Activo')
                        <span class="estado-activo">{{ $dependencia->estado->descripcion}}</span>
                        @elseif ($dependencia->estado->descripcion == 'Inactivo')
                        <span class="estado-desactivado">{{ $dependencia->estado->descripcion}}</span>
                        @endif
                    </div>
                </td>
                <td>{{ $dependencia->audit ? ($dependencia->audit->userCreated ? $dependencia->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $dependencia->audit ? ($dependencia->audit->userUpdated ? $dependencia->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $dependencia->created_at }}</td>
                <td>{{ $dependencia->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>