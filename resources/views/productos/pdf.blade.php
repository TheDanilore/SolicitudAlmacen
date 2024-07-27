<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
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
    <h1 style="text-align: center;">Lista de Productos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Unidad Medida</th>
                <th>Stock</th>
                <th>Precio U.</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->id }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->categoria->descripcion }}</td>
                <td>{{ $producto->unidadMedida->abreviacion }}</td>
                <td>{{ $producto->cantidad }}</td>
                <td>{{ $producto->precio_actual }}</td>
                <td>
                    {!! $producto->imagen ? '<img src="' . public_path($producto->imagen) . '">' : 'N/A' !!}
                </td>
                <td>
                    <div class='text-center'>
                        @if ($producto->estado->descripcion == 'Activo')
                        <span class="estado-activo">{{ $producto->estado->descripcion }}</span>
                        @elseif ($producto->estado->descripcion == 'Inactivo')
                        <span class="estado-desactivado">{{ $producto->estado->descripcion }}</span>
                        @endif
                    </div>
                </td>
                <td>{{ $producto->audit ? ($producto->audit->userCreated ? $producto->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $producto->audit ? ($producto->audit->userUpdated ? $producto->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $producto->created_at }}</td>
                <td>{{ $producto->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
