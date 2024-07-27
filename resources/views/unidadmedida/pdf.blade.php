<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Unidades de Medida</title>
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
    <h1>Lista de Usuarios</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Abreviación</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unidadesmedidas as $medida)
            <tr>
            <td>{{ $medida->id }}</td>
                <td>{{ $medida->descripcion }}</td>
                <td>{{ $medida->abreviacion }}</td>
                <td>{{ $medida->audit ? ($medida->audit->userCreated ? $medida->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $medida->audit ? ($medida->audit->userUpdated ? $medida->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $medida->created_at }}</td>
                <td>{{ $medida->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>