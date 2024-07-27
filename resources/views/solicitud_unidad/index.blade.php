<x-app-layout>
    <div class="card-table-index cardIndex">
        <div class="card-body">
            <div class="container">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
            </div>
            <div class="container">
                <div class="card-title text-xl text-gray-900 leading-tight">
                    <h2>{{ __('Solicitudes de Pedidos a Jefatura') }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a id="btnNuevo" class="btn btn-success btnCrear" href="{{ route('solicitud_unidad.create') }}">Nuevo</a>
                        <!-- Botón de descarga con menú desplegable -->
                         <!--
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Descargar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('solicitud_unidad.export', ['format' => 'pdf']) }}">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="tablaSolicitudUnidad" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Unidad</th>
                                        <th>Dependencia</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitudes as $solicitud)
                                    <tr>
                                        <td>{{ $solicitud->id }}</td>
                                        <td>{{ $solicitud->audit ? ($solicitud->audit->userCreated ? $solicitud->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                                        <td>{{ $solicitud->unidad->descripcion }}</td>
                                        <td>{{ $solicitud->dependencia->nombre }}</td>
                                        <td>
                                            <div class='text-center'>
                                                @if ($solicitud->estado == 'pendiente')
                                                <div class="btn-sm estado-pendiente btn-icon-split">
                                                    <span class="icon text-white-40 estado-pendiente"><i class="fa-solid fa-clock"></i></span>
                                                    <span class="text estado-pendiente"> {{ $solicitud->estado }}</span>
                                                </div>

                                                @elseif ($solicitud->estado == 'aprobada')
                                                <div class="btn-sm estado-aprobada btn-icon-split">
                                                    <span class="icon text-white-40 estado-aprobada"><i class="fa-solid fa-face-smile"></i></span>
                                                    <span class="text estado-aprobada">{{ $solicitud->estado }}</span>
                                                </div>
                                                @elseif ($solicitud->estado == 'rechazada')
                                                <div class="btn-sm estado-rechazada btn-icon-split">
                                                    <span class="icon text-white-40 estado-rechazada"><i class="fa-solid fa-thumbs-down"></i></span>
                                                    <span class="text estado-rechazada">{{ $solicitud->estado }}</span>
                                                </div>
                                                @endif

                                            </div>
                                        </td>
                                        <td>{{ $solicitud->created_at }}</td>
                                        <td>
                                            <div class='text-center'>
                                                <div class='btn-group'>

                                                    <a href="{{ route('solicitud_unidad.show', $solicitud->id) }}" class="btn-circle btn-primary">
                                                        <i class="fa-solid fa-eye"></i></a>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        var tablaSolicitudUnidad = $("#tablaSolicitudUnidad").DataTable({

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