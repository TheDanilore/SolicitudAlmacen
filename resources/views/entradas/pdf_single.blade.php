<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada {{ $entradaProducto->id }}</title>
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
            <h2>Entrada #{{ $entradaProducto->id }}</h2>
        </div>
        <div class="info">
            <p><strong>Fecha de Entrada:</strong> {{ $entradaProducto->created_at }}</p>
            <p><strong>Guía de Remisión:</strong> {{ $entradaProducto->guia_remision }}</p>
            <p><strong>Proveedor:</strong> {{ $entradaProducto->proveedor->razon_social }}</p>
            <p><strong>Usuario:</strong> {{ $entradaProducto->audit ? ($entradaProducto->audit->userCreated ? $entradaProducto->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
            <p><strong>Usuario - Correo:</strong> {{ $entradaProducto->audit ? ($entradaProducto->audit->userCreated ? $entradaProducto->audit->userCreated->email : 'N/A') : 'N/A' }}</p>
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
                        <th>IGV</th>
                        <th>Costo Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entradaProducto->itemsEntrada as $itemEntrada)
                    <tr>
                        <td>{{ $itemEntrada->producto->id }}</td>
                        <td>{{ $itemEntrada->producto->nombre }}</td>
                        <td>{{ $itemEntrada->movimiento ? $itemEntrada->movimiento->stock_anterior : 'N/A' }}</td>
                        <td>{{ $itemEntrada->cantidad }}</td>
                        <td>{{ $itemEntrada->p_unitario }}</td>
                        <td>{{ $itemEntrada->igv }}</td>
                        <td>{{ $itemEntrada->costo_total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>