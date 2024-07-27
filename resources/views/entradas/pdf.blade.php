<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Todos las Entradas</title>
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
    <h1 style="text-align: center;">Todos los Ingresos</h1>
    <table>
        <thead>
            <tr>
                <th>ID Entrada</th>
                <th>ID</th>
                <th>Usuario</th>
                <th>Guía Remisión</th>
                <th>Proveedor</th>
                <th>Producto</th>
                <th>Habia</th>
                <th>Ingreso</th>
                <th>Precio Unitario</th>
                <th>IGV</th>
                <th>Costo Total</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemsEntrada as $itemEntrada)
            <tr>
                <td>{{ $itemEntrada->entradaproducto->id }}</td>
                <td>{{ $itemEntrada->id }}</td>
                <td>{{ $itemEntrada->entradaproducto->audit ? ($itemEntrada->entradaproducto->audit->usercreated ? $itemEntrada->entradaproducto->audit->usercreated->email : 'N/A') : 'N/A' }}</td>
                <td>{{ $itemEntrada->entradaproducto->guia_remision }}</td>
                <td>{{ $itemEntrada->entradaproducto->proveedor->razon_social }}</td>
                <td>{{ $itemEntrada->producto->nombre }}</td>
                <td>{{ $itemEntrada->movimiento ? $itemEntrada->movimiento->stock_anterior : 'N/A' }}</td>
                <td>{{ $itemEntrada->cantidad }}</td>
                <td>S/{{ $itemEntrada->p_unitario }}</td>
                <td>{{ $itemEntrada->igv }}</td>
                <td>S/{{ $itemEntrada->costo_total }}</td>
                <td>{{ $itemEntrada->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>