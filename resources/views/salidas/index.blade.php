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
                    <h2>{{ __('Lista de Salidas de Productos') }}</h2>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="car-body">
                            @hasanyrole('Administrador|Jefe de Almacen')
                            <a href="{{ route('salidas.create') }}" class="btn btn-primary mb-3 btnCrear"><i class="fa-solid fa-circle-plus"></i> Nueva</a>
                            @endhasanyrole
                            
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Descargar
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('salidas.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                                    <a class="dropdown-item" href="{{ route('salidas.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                    <a class="dropdown-item" href="{{ route('salidas.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="tablaSalidaProducto" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Usuario - Correo</th>
                                        <th>Fecha de Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salidas as $salida)
                                    <tr>
                                        <td>{{ $salida->id }}</td>
                                        <td>{{ $salida->audit ? ($salida->audit->userCreated ? $salida->audit->userCreated->name : 'N/A') : 'N/A' }}</td>
                                        <td>{{ $salida->audit ? ($salida->audit->userCreated ? $salida->audit->userCreated->email : 'N/A') : 'N/A' }}</td>
                                        <td>{{ $salida->created_at }}</td>
                                        <td>
                                            <div class='text-center'>
                                                <div class='btn-group'>
                                                    <a href="{{ route('salidas.show', $salida->id) }}" class="btn-circle btn-info btnVer"><i class="fa-solid fa-eye"></i></a>
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
        var tablaSalidaProducto = $("#tablaSalidaProducto").DataTable({

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