<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Categorías de Productos</title>
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
    <h1 style="text-align: center;">Lista de Categorías de Productos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categoriaproductos as $categoria)
            <tr>
                <td>{{ $categoria->id }}</td>
                <td>{{ $categoria->descripcion }}</td>
                <td>{{ $categoria->audit ? ($categoria->audit->userCreated ? $categoria->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $categoria->audit ? ($categoria->audit->userUpdated ? $categoria->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $categoria->created_at }}</td>
                <td>{{ $categoria->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>