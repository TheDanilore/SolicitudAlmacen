<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Todos las Salidas</title>
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

        .movimiento-entrada {
            background-color: rgb(31, 180, 78);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .movimiento-salida {
            background-color: rgb(244, 30, 63);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .movimiento-ajuste {
            background-color: rgb(216, 171, 49);
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
    <h1 style="text-align: center;">Todos las Salidas</h1>
    <table>
        <thead>
            <tr>
                <th>ID Salida</th>
                <th>ID</th>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Habia</th>
                <th>Salió</th>
                <th>Precio Unitario</th>
                <th>Costo Total</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemsSalida as $itemSalida)
            <tr>
                <td>{{ $itemSalida->salidaproducto->id }}</td>
                <td>{{ $itemSalida->id }}</td>
                <td>{{ $itemSalida->salidaproducto->audit ? ($itemSalida->salidaproducto->audit->usercreated ? $itemSalida->salidaproducto->audit->usercreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $itemSalida->producto->nombre }}</td>
                <td>{{ $itemSalida->movimiento ? $itemSalida->movimiento->stock_anterior : 'N/A' }}</td>
                <td>{{ $itemSalida->cantidad }}</td>
                <td>S/{{ $itemSalida->p_unitario }}</td>
                <td>S/{{ $itemSalida->costo_total }}</td>
                <td>{{ $itemSalida->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>