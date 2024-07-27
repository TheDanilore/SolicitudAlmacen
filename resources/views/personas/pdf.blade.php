<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lista de Personas</title>
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
    <h1 style="text-align: center;">Lista de Personas</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Tipo Documento</th>
                <th>N° Documento</th>
                <th>Usuario que Creó</th>
                <th>Usuario que Actualizó</th>
                <th>Fecha de Creación</th>
                <th>Fecha de Actualización</th>
            </tr>
        </thead>
        <tbody>
            @foreach($personas as $persona)
            <tr>
                <td>{{ $persona->id}}</td>
                <td>{{ $persona->primer_nombre }} {{ $persona->segundo_nombre }}
                    {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                </td>
                <td>{{ $persona->prefijo_telefono }}{{ $persona->telefono }}</td>
                <td>{{ $persona->direccion }}</td>
                <td>{{ $persona->tipoDocumentoIdentidad->abreviatura }}</td>
                <td>{{ $persona->numero_documento }}</td>
                <td>{{ $persona->audit ? ($persona->audit->userCreated ? $persona->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $persona->audit ? ($persona->audit->userUpdated ? $persona->audit->userUpdated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $persona->created_at }}</td>
                <td>{{ $persona->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>