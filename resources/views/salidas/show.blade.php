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
                        <h2>{{ __('Salida #') }}{{ $salida->id }}
                        <a href="{{ route('salidas.formatoPdf', $salida->id) }}" class="btn btn-success float-right"><i class="fa-solid fa-file-pdf"></i> Exportar a PDF</a>
                        </h2>

                    </div>
                </div>
            </div>
            <br>
            <div class="card-ver text-gray-900">
                <div class="card-header">
                    <h3>Información de Salida</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            <p><strong>Fecha de Salida:</strong>{{ $salida->created_at }}</p>
                        </li>
                        <li>
                            <p><strong>Usuario:</strong> {{ $salida->audit ? ($salida->audit->userCreated ? $salida->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
                        </li>
                        <li>
                            <p><strong>Usuario - Correo:</strong> {{ $salida->audit ? ($salida->audit->userCreated ? $salida->audit->userCreated->email : 'N/A') : 'N/A' }}</p>
                        </li>

                    </ul>
                </div>
            </div>
            <br>
            <div class="card-ver text-gray-900">
                <div class="car-body">
                    <div class="container">
                        <br>
                        <h3>{{ __('Productos') }}</h3>
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="table-responsive">
                                    <table id="tablaSalidaProducto" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Producto ID</th>
                                                <th>Producto Nombre</th>
                                                <th>Habia</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unitario</th>
                                                <th>Costo Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($salida->itemsSalida as $itemSalida)
                                            <tr>
                                                <td>{{ $itemSalida->producto->id }}</td>
                                                <td>{{ $itemSalida->producto->nombre }}</td>
                                                <td>{{ $itemSalida->movimiento ? $itemSalida->movimiento->stock_anterior : 'N/A' }}</td>
                                                <td>{{ $itemSalida->cantidad }}</td>
                                                <td>{{ $itemSalida->p_unitario }}</td>
                                                <td>{{ $itemSalida->costo_total }}</td>
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
            <a href="{{ route('salidas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-right-from-bracket"></i> Volver</a>
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