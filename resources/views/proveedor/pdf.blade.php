<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Proveedores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Reducir tamaño de fuente */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">Lista de Proveedores</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ruc</th>
                <th>Razón Social</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proveedores as $proveedor)
            <tr>
                <td>{{ $proveedor->id }}</td>
                <td>{{ $proveedor->ruc }}</td>
                <td>{{ $proveedor->razon_social }}</td>
                <td>{{ $proveedor->direccion }}</td>
                <td>{{ $proveedor->prefijo_telefono }}{{ $proveedor->telefono }}</td>
                <td>
                    <div class='text-center'>
                        @if ($proveedor->estado->descripcion == 'Activo')
                        <span class="estado-activo">{{ $proveedor->estado->descripcion }}</span>
                        @elseif ($proveedor->estado->descripcion == 'Inactivo')
                        <span class="estado-desactivado">{{ $proveedor->estado->descripcion }}</span>
                        @endif
                    </div>
                </td>
                <td>{{ $proveedor->audit ? ($proveedor->audit->userCreated ? $proveedor->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $proveedor->audit ? ($proveedor->audit->userUpdated ? $proveedor->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $proveedor->created_at }}</td>
                <td>{{ $proveedor->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
