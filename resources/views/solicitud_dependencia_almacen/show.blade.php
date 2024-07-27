<x-app-layout>
    <div class="card-table-index cardIndex">
        <div class="card-body">
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
                        <h2>{{ __('Solicitud a Almacen #') }}{{ $solicitud->id }}
                            <!-- Botón de descarga con menú desplegable -->
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Descargar
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </h2>
                    </div>
                </div>
            </div>

            <br>

            <div class="card-ver text-gray-900">
                <div class="card-header">
                    <h3>Detalle de Solicitud Revisada</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <h5><strong>Solicitado Desde:</strong></h5>
                        <h5>
                            <p><strong>Dependencia:</strong> {{ $solicitud->unidad->dependencia->nombre }}</p>
                        </h5>
                        <h5><strong>Estado: </strong>

                            @if ($solicitud->estado == 'pendiente')
                            <span class="estado-pendiente"><i class="fa-solid fa-clock"></i> {{ $solicitud->estado }}</span>
                            @elseif ($solicitud->estado == 'aprobada')
                            <span class="estado-aprobada"><i class="fa-solid fa-face-smile"></i> {{ $solicitud->estado }}</span>
                            @elseif ($solicitud->estado == 'rechazada')
                            <span class="estado-rechazada"><i class="fa-solid fa-thumbs-down"></i> {{ $solicitud->estado }}</span>
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
                                    <table id="tablaSolicitudDependencia" class="table table-striped table-bordered table-condensed  text-gray-900" style="width:100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th>
                                                    <h4>Producto</h4>
                                                </th>
                                                <th>
                                                    <h4>Cantidad Solicitada</h4>
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
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                        

                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <br>
            <a href="{{ route('solicitud_dependencia_almacen.index') }}" class="btn btn-dark"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
        </div>
    </div>

</x-app-layout>
<script>
    $(document).ready(function() {
        var tablaSolicitudAlmacen = $("#tablaSolicitudDependencia").DataTable({

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