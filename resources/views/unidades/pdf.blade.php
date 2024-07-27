<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Unidades (SubÁrea)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
    <h1>Lista de Unidades</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Dependencia</th>
                <th>Descripción</th>
                <th>Responsable</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unidades as $unidad)
            <tr>
                <td>{{ $unidad->id }}</td>
                <td>{{ $unidad->dependencia->nombre }}</td>
                <td>{{ $unidad->descripcion }}</td>
                <td>
                  @foreach($unidad->usuarios as $usuario)
                  - {{ $usuario->name }}
                  @endforeach
                </td>
                <td>{{ $unidad->audit ? ($unidad->audit->userCreated ? $unidad->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $unidad->audit ? ($unidad->audit->userUpdated ? $unidad->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $unidad->created_at }}</td>
                <td>{{ $unidad->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>