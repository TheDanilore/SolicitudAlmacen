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
                    <h2>{{ __('Movimientos') }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="car-body">
                            @hasanyrole('Administrador|Jefe de Almacen')
                            <a href="{{ route('entradas.create') }}" class="btn btn-primary btnCrear"><i class="fa-solid fa-circle-plus"></i> Crear Entrada</a>
                            <a href="{{ route('salidas.create') }}" class="btn btn-primary btnCrear"><i class="fa-solid fa-circle-minus"></i> Crear Salida</a>
                            @endhasanyrole


                            <!-- Botón de descarga con menú desplegable -->
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Descargar
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('movimientos.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                                    <a class="dropdown-item" href="{{ route('movimientos.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                    <a class="dropdown-item" href="{{ route('movimientos.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
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
                            <table id="tablaMovimientos" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Producto</th>
                                        <th>Habia</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
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
                                        <td>
                                            <a href="{{ route('movimientos.kardexPdf', $movimiento->producto_id) }}" class="btn btn-circle btn-success float-right"><i class="fa-solid fa-file-pdf"></i></a>
                                            @if($movimiento->tipo == 'entrada')
                                            <div class='text-center'>
                                                <div class='btn-group'>
                                                    <a href="{{ route('entradas.show', $movimiento->itemEntrada->entradaProducto->id) }}" class="btn-circle btn-info btnVer"><i class="fa-solid fa-eye"></i></a>
                                                </div>
                                            </div>
                                            @elseif ($movimiento->tipo == 'salida')
                                            <div class='text-center'>
                                                <div class='btn-group'>
                                                    <a href="{{ route('salidas.show', $movimiento->itemSalida->salidaProducto->id) }}" class="btn-circle btn-info btnVer"><i class="fa-solid fa-eye"></i></a>
                                                </div>
                                            </div>
                                            @elseif ($movimiento->tipo == 'ajuste')
                                            <div class='text-center'>
                                                <div class='btn-group'>
                                                </div>
                                            </div>
                                            @endif
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
        var tablaMovimientos = $("#tablaMovimientos").DataTable({
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