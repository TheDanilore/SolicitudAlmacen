<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Roles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
    <h1>Lista de Roles</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Rol</th>
                <th>Name</th>
                <th>Estado</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->guard_name }}</td>
                <td>
                    <div class='text-center'>
                        @if ($role->estadoRol->descripcion == 'Activo')
                        <span class="estado-activo">{{ $role->estadoRol->descripcion }}</span>
                        @elseif ($role->estadoRol->descripcion == 'Inactivo')
                        <span class="estado-desactivado">{{ $role->estadoRol->descripcion }}</span>
                        @endif
                    </div>
                </td>
                <td>{{ $role->audit ? ($role->audit->userCreated ? $role->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $role->audit ? ($role->audit->userUpdated ? $role->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $role->created_at }}</td>
                <td>{{ $role->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>