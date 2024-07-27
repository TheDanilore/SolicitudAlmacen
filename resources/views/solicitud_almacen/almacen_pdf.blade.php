<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitud de Almacén #{{ $solicitud->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Solicitud de Almacén #{{ $solicitud->id }}</h1>
    </div>
    <div class="card-body ">
        <ul>
            <h5><strong>Solicitado Desde:</strong></h5>
            <h5>
                <p><strong>Dependencia:</strong> {{ $solicitud->unidad->dependencia->nombre }}</p>
            </h5>

            <h5><strong>Estado: </strong>

                @if ($solicitud->estado == 'pendiente')
                <div class="estado-pendiente btn-icon-split">
                    <span class="icon text-white-40 estado-pendiente"><i class="fa-solid fa-clock"></i></span>
                    <span class="text estado-pendiente"> {{ $solicitud->estado }}</span>
                </div>

                @elseif ($solicitud->estado == 'aprobada')
                <div class="estado-aprobada btn-icon-split">
                    <span class="icon text-white-40 estado-aprobada"><i class="fa-solid fa-face-smile"></i></span>
                    <span class="text estado-aprobada">{{ $solicitud->estado }}</span>
                </div>
                @elseif ($solicitud->estado == 'rechazada')
                <div class="estado-rechazada btn-icon-split">
                    <span class="icon text-white-40 estado-rechazada"><i class="fa-solid fa-thumbs-down"></i></span>
                    <span class="text estado-rechazada">{{ $solicitud->estado }}</span>
                </div>
                @endif

            </h5>
            <br>
            <h5><strong>Revisado Por:</strong></h5>
            <li>
                <p><strong>Jefe Almacen:</strong> @foreach ($solicitud_almacen as $almacen)
                    {{ $almacen->audit ? ($almacen->audit->userCreated ? $almacen->audit->userCreated->name : 'N/A') : 'N/A' }}
                    @endforeach
                </p>
            </li>
            <li>
                <p><strong>Jefe Almacen:</strong> @foreach ($solicitud_almacen as $almacen)
                    {{ $almacen->audit ? ($almacen->audit->userCreated ? $almacen->audit->userCreated->email : 'N/A') : 'N/A' }}
                    @endforeach
                </p>
            </li>
            <br>

            <h5><strong>Solicitado Por:</strong></h5>
            <li>
                <p><strong>Jefe Dependencia:</strong> {{ $solicitud->audit ? ($solicitud->audit->userCreated ? $solicitud->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
            </li>
            <li>
                <p><strong>Jefe Dependencia - Correo:</strong> {{ $solicitud->audit ? ($solicitud->audit->userCreated ? $solicitud->audit->userCreated->email : 'N/A') : 'N/A' }}</p>
            </li>
            <li>
                <p><strong>Fecha:</strong> {{ $solicitud->created_at }}</p>
            </li>
            <br>

        </ul>
    </div>



    <h3>Productos Solicitados</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad Solicitada</th>
                <th>Stock Actual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solicitud->productos as $producto)
            <tr>
                <td>{{ $producto->producto->nombre }}</td>
                <td>{{ $producto->cantidad }}</td>
                <td>{{ $producto->producto->cantidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>