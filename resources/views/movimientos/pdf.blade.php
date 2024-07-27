<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Todos los Movimientos</title>
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
    <h1 style="text-align: center;">Todos los Movimientos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Habia</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movimientos as $movimiento)
            <tr>
                <td>{{ $movimiento->id }}</td>
                <td>
                    {{ $movimiento->audit ? ($movimiento->audit->userCreated ? $movimiento->audit->userCreated->email : 'N/A') : 'N/A' }}
                </td>
                <td>
                    @if($movimiento->tipo == 'entrada')
                    {{ $movimiento->itemEntrada->producto->nombre }}
                    @elseif ($movimiento->tipo == 'salida')
                    {{ $movimiento->itemSalida->producto->nombre }}
                    @elseif ($movimiento->tipo == 'ajuste')
                    {{$movimiento->producto->nombre}}
                    @endif
                </td>
                <td>{{ $movimiento->stock_anterior }}</td>
                <td>
                    <div class='text-center'>
                        @if ($movimiento->tipo == 'entrada')
                        <span class="movimiento-entrada">{{ $movimiento->tipo }}</span>
                        @elseif ($movimiento->tipo == 'salida')
                        <span class="movimiento-salida">{{ $movimiento->tipo }}</span>
                        @elseif ($movimiento->tipo == 'ajuste')
                        <span class="movimiento-ajuste">{{ $movimiento->tipo }}</span>
                        @endif
                    </div>
                </td>
                <td>{{ $movimiento->cantidad }}</td>
                <td>{{ $movimiento->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>