<x-app-layout>
    <div class="card-table-index cardIndex">
        <div class="card-body">

            <div class="container">
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
            </div>
            <br>
            <div class="container">
                <div class="card-title text-xl text-gray-900 leading-tight">
                    <h2>{{ __('Solicitudes de Pedidos a almacen') }}</h2>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="tablaSolicitudDependencia" class="table table-striped table-bordered table-condensed  text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Usuario - Correo</th>
                                        <th>Unidad</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($solicitudes as $solicitud)
                                    <tr>
                                        <td>{{ $solicitud->id }}</td>
                                        <td>{{ $solicitud->audit ? ($solicitud->audit->userCreated ? $solicitud->audit->userCreated->name : 'N/A') : 'N/A' }}</td>
                                        <td>{{ $solicitud->audit ? ($solicitud->audit->userCreated ? $solicitud->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                                        <!--<td>{{ $solicitud->unidad->descripcion}}</td>-->
                                        <td>{{ $solicitud->dependencia->nombre}}</td>
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
                                                    @if ($solicitud->estado == 'pendiente')
                                                    <a href="{{ route('solicitud_dependencia_almacen.show', $solicitud->id) }}" class="btn-circle btn-primary">
                                                        <i class="fa-solid fa-eye"></i></a>
                                                    @endif
                                                    @if ($solicitud->estado == 'aprobada')
                                                    <a href="{{ route('solicitud_dependencia_almacen.show', $solicitud->id) }}" class="btn-circle btn-primary">
                                                        <i class="fa-solid fa-eye"></i></a>
                                                    @endif
                                                    @if ($solicitud->estado == 'rechazada')
                                                    <a href="{{ route('solicitud_dependencia_almacen.show', $solicitud->id) }}" class="btn-circle btn-primary">
                                                        <i class="fa-solid fa-eye"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="car-body">
                                <a href="{{ route('solicitud_dependencia.index') }}" class="btn btn-dark"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        var tablaSolicitudDependencia = $("#tablaSolicitudDependencia").DataTable({

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
            },
            "order": [] // Para mantenr el orden que viene desde el backend
        });
    });
</script>