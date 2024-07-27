<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Permisos</title>
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
            color: green;
        }

        .estado-desactivado {
            color: red;
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
    <h1 style="text-align: center;">Lista de Permisos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>Guard Name</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permisos as $permiso)
            <tr>
                <td>{{ $permiso->id}}</td>
                <td>{{ $permiso->name }}</td>
                <td>{{ $permiso->guard_name }}</td>
                <td>{{ $permiso->audit ? ($permiso->audit->userCreated ? $permiso->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $permiso->audit ? ($permiso->audit->userUpdated ? $permiso->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $permiso->created_at }}</td>
                <td>{{ $permiso->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>