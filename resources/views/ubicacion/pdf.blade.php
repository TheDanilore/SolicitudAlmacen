<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Ubicaciones</title>
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
    <h1>Lista de Ubicaciones</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Codigo</th>
                <th>Descripción</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ubicaciones as $ubicacion)
            <tr>
                <td>{{ $ubicacion->id }}</td>
                <td>{{ $ubicacion->code }}</td>
                <td>{{ $ubicacion->descripcion }}</td>
                <td>{{ $ubicacion->audit ? ($ubicacion->audit->userCreated ? $ubicacion->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $ubicacion->audit ? ($ubicacion->audit->userUpdated ? $ubicacion->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $ubicacion->created_at }}</td>
                <td>{{ $ubicacion->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>