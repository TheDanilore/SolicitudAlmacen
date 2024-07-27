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
                        <h2>{{ __('Entrada #') }}{{ $entrada->id }}
                            <!--<a href="{{ route('entradas.exportSingleToPdf', $entrada->id) }}" class="btn btn-success float-right"><i class="fa-solid fa-file-pdf"></i> Exportar a PDF</a>-->
                            <a href="{{ route('entradas.formatoPdf', $entrada->id) }}" class="btn btn-success float-right"><i class="fa-solid fa-file-pdf"></i> Exportar a PDF</a>
                        </h2>
                    </div>
                </div>
            </div>
            <br>

            <div class="card-ver text-gray-900">
                <div class="card-header">
                    <h3>Información de Entrada</h3>
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            <p><strong>Fecha de Entrada:</strong> {{ $entrada->created_at }}</p>
                        </li>
                        <li>
                            <p><strong>Guia de Remisión:</strong>{{ $entrada->guia_remision }}</p>
                        </li>
                        <li>
                            <p><strong>Proveedor:</strong>{{ $entrada->proveedor->razon_social }}</p>
                        </li>
                        <li>
                            <p><strong>Usuario:</strong> {{ $entrada->audit ? ($entrada->audit->userCreated ? $entrada->audit->userCreated->name : 'N/A') : 'N/A' }}</p>
                        </li>
                        <li>
                            <p><strong>Usuario - Correo:</strong> {{ $entrada->audit ? ($entrada->audit->userCreated ? $entrada->audit->userCreated->email : 'N/A') : 'N/A' }}</p>
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
                        <h3>{{ __('Productos') }}</h3>
                        <div class="table-responsive">
                            <table id="tablaEntradaProducto" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>

                                        <th>Producto ID</th>
                                        <th>Producto</th>
                                        <th>Habia</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>IGV</th>
                                        <th>Costo Total</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($itemsEntrada as $itemEntrada)
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
                        <br>
                    </div>
                </div>
            </div>
            <br>
            <a href="{{ route('entradas.index') }}" class="btn btn-secondary"><i class="fa-solid fa-right-from-bracket"></i> Volver</a>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        var tablaEntradaProducto = $("#tablaEntradaProducto").DataTable({

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