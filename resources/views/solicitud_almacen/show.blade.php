<x-app-layout>

    <div class="container">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('errors'))
        <div class="alert alert-success">
            {{ session('errors') }}
        </div>
        @endif
    </div>
    <div class="card-ver text-gray-900">
        <div class="card-body">
            <div class="text-gray-900">
                <h2>{{ __('Solicitud de Almacen #') }}{{ $solicitud->id }}
                    <a href="{{ route('solicitud_almacen.formatoPdf', $solicitud->id) }}" class="btn btn-success float-right"><i class="fa-solid fa-file-pdf"></i> Exportar a PDF</a>
                </h2>

            </div>
        </div>
    </div>
    <br>
    <div class="card-ver text-gray-900">
        <div class="card-header">
            <h3>Detalle de Solicitud</h3>
        </div>
        <div class="card-body">
            <ul>
                <h5>Solicitado Desde:</h5>
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
                <h5><strong>Solicitado Por (Dependencia):</strong></h5>
                <p><strong>Jefe Dependencia:</strong> {{ $solicitud->audit ? ($solicitud->audit->userCreated ? $solicitud->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
                <p><strong>Jefe Dependencia - Correo:</strong> {{ $solicitud->audit ? ($solicitud->audit->userCreated ? $solicitud->audit->userCreated->email : 'N/A') : 'N/A' }}</p>
                <br>
                <h5><strong>Solicitado Por (Unidad):</strong></h5>
                <p><strong>Empleado Unidad:</strong> {{ $solicitud_unidad->audit ? ($solicitud_unidad->audit->userCreated ? $solicitud_unidad->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
                <p><strong>Empleado Unidad - Correo:</strong> {{ $solicitud_unidad->audit ? ($solicitud_unidad->audit->userCreated ? $solicitud_unidad->audit->userCreated->email : 'N/A') : 'N/A' }}</p>
                <br>
                <h5><strong>Recepcionado Por:</strong></h5>
                <p><strong>Usuario:</strong> {{ $solicitud_almacen ? ($solicitud_almacen->audit->userCreated ? $solicitud_almacen->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
                <p><strong>Correo:</strong> {{ $solicitud_almacen ? ($solicitud_almacen->audit->userCreated ? $solicitud_almacen->audit->userCreated->email : 'N/A') : 'N/A' }}</p>

                <br>
                <p><strong>Fecha:</strong> {{ $solicitud->created_at }}</p>

            </ul>
        </div>
    </div>
    <br>
    <div class="card-ver text-gray-900">
        <div class="car-body">
            <div class="container">
                <br>
                <h3>{{ __('Productos Solicitados') }}</h3>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="tablaSolicitudAlmacen" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>
                                            <h4>Producto</h4>
                                        </th>
                                        <th>
                                            <h4>Cantidad Solicitada</h4>
                                        </th>
                                        <th>
                                            <h4>Stock Actual</h4>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach($solicitud->productos as $producto)
                                    <tr>
                                        <td>
                                            {{ $producto->producto->nombre }}
                                        </td>
                                        <td>
                                            {{ $producto->cantidad}}
                                        </td>
                                        <td>{{ $producto->producto->cantidad }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </table>
                        </div>
                        <div class="car-body">
                            @if($solicitud->estado == 'pendiente')
                            <a href="{{ route('solicitud_almacen.approve', $solicitud->id) }}" class="btn btn-success"><i class="fa-solid fa-thumbs-up"></i> Aprobar</a>
                            <a href="{{ route('solicitud_almacen.reject', $solicitud->id) }}" class="btn btn-danger"><i class="fa-solid fa-thumbs-down"></i> Rechazar</a>

                            @endif


                        </div>
                    </div>
                </div>
                <br>
                <a href="{{ route('solicitud_almacen.index') }}" class="btn btn-dark"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
                <br>
                <br>
            </div>
        </div>
    </div>


</x-app-layout>
<script>
    $(document).ready(function() {
        var tablaSolicitudAlmacen = $("#tablaSolicitudAlmacen").DataTable({

            "language": {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "sProcessing": "Procesando...",
            }
        });
    });
</script>