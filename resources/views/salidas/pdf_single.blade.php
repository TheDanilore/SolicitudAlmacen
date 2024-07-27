<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Salida - {{ $salidaProducto->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .info,
        .productos {
            margin-bottom: 20px;
        }

        .productos table {
            width: 100%;
            border-collapse: collapse;
        }

        .productos table,
        .productos th,
        .productos td {
            border: 1px solid black;
        }

        .productos th,
        .productos td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>Salida - {{ $salidaProducto->id }}</h2>
        </div>
        <div class="info">
            <p><strong>Fecha de Salida:</strong> {{ $salidaProducto->created_at }}</p>
            <p><strong>Usuario:</strong> {{ $salidaProducto->audit ? ($salidaProducto->audit->userCreated ? $salidaProducto->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
            <p><strong>Usuario - Correo:</strong> {{ $salidaProducto->audit ? ($salidaProducto->audit->userCreated ? $salidaProducto->audit->userCreated->email : 'N/A') : 'N/A' }}</p>
        </div>
        <div class="productos">
            <h3>Productos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto ID</th>
                        <th>Producto Nombre</th>
                        <th>Había</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Costo Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salidaProducto->itemsSalida as $itemSalida)
                    <tr>
                        <td>{{ $itemSalida->producto->id }}</td>
                        <td>{{ $itemSalida->producto->nombre }}</td>
                        <td>{{ $itemSalida->movimiento ? $itemSalida->movimiento->stock_anterior : 'N/A' }}</td>
                        <td>{{ $itemSalida->cantidad }}</td>
                        <td>S/{{ $itemSalida->p_unitario }}</td>
                        <td>S/{{ $itemSalida->costo_total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>