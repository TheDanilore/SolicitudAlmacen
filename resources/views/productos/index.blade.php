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
                    <h2>{{ __('Lista de Productos') }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a id="btnNuevoProveedor" class="btn btn-success btnCrear" href="{{ route('productos.create') }}">Nuevo</a>
                        <!-- Botón de descarga con menú desplegable -->
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Descargar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('productos.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                                <a class="dropdown-item" href="{{ route('productos.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="dropdown-item" href="{{ route('productos.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
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
                            <table id="tablaProducto" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Unidad Medida</th>
                                        <th>Stock</th>
                                        <th>Precio U.</th>
                                        <th>Imagen</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->id }}</td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->categoria->descripcion }}</td>
                                        <td>{{ $producto->unidadMedida->abreviacion }}</td>

                                        <td>{{ $producto->cantidad }}</td>
                                        <td>{{ $producto->precio_actual }}</td>
                                        <td>
                                            @if($producto->imagen)
                                            <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" width="50" height="50">
                                            @else
                                            <span>No Imagen</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class='text-center'>
                                                @if ($producto->estado->descripcion == 'Activo')
                                                <span class="estado-activo">{{ $producto->estado->descripcion }}</span>
                                                @elseif ($producto->estado->descripcion == 'Inactivo')
                                                <span class="estado-desactivado">{{ $producto->estado->descripcion }}</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class='text-center'>
                                                <div class='btn-group'>
                                                    <button class='btn-circle btn-primary btnVer' onclick="showProducto({{ $producto->id }})"><i class="fa-solid fa-eye"></i></button>
                                                    &nbsp;
                                                    <a class=" btn-circle btn-info btnEditar" href="{{ route('productos.edit', $producto->id) }}"><i class="fa-solid fa-pencil"></i></a>
                                                    &nbsp;
                                                    @hasanyrole('Administrador|Jefe de Almacen')
                                                    <button class=' btn-danger btn-circle btnEliminar' onclick="confirmDelete({{ $producto->id }})"><i class="fa-solid fa-trash"></i></button>
                                                    @endhasanyrole
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

            <!-- Modal para ver información -->
            <div class="modal fade" id="modalVerProducto" tabindex="-1" role="dialog" aria-labelledby="modalVerProductoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalVerProductoLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>ID:</strong> <span id="id"></span></p>
                                        <p><strong>Producto:</strong> <span id="nombre"></span></p>
                                        <p><strong>Imagen:</strong><br> <img id="imagen" src="" alt="Imagen del Producto" width="150" height="150"></p>
                                        <p><strong>Descripcion:</strong> <span id="descripcion"></span></p>
                                        <p><strong>Categoría:</strong> <span id="categoria_productos_id"></span></p>
                                        <p><strong>Unidad de Medida:</strong> <span id="unidad_medida_id"></span></p>
                                        <p><strong>Cantidad:</strong> <span id="cantidad"></span></p>
                                        <p><strong>Estado:</strong> <span id="estado_producto_div"></span>
                                            <!--<div id="estado_producto_div">                               </div>-->
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Ubicación:</strong> <span id="ubicacion_id"></span></p>
                                        <p><strong>IGV:</strong> <span id="igv"></span></p>
                                        <p><strong>Precio Actual:</strong> <span id="precio_actual"></span></p>
                                        <p><strong>Creado por:</strong> <span id="user_id"></span></p>
                                        <p><strong>Actualizado por:</strong> <span id="user_id_updated"></span></p>
                                        <p><strong>Fecha de Creación:</strong> <span id="created_at"></span></p>
                                        <p><strong>Fecha de Edición:</strong> <span id="updated_at"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Está seguro de eliminar el producto?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('productos.destroy', '') }}/" + id;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function showProducto(id) {
        fetch(`/productos/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('id').textContent = data.id;
                document.getElementById('user_id').textContent = data.audit && data.audit.usercreated ? data.audit.usercreated.name : 'N/A';
                document.getElementById('user_id_updated').textContent = data.audit && data.audit.userupdated ? data.audit.userupdated.name : 'N/A';
                document.getElementById('nombre').textContent = data.nombre ? data.nombre : 'N/A';
                document.getElementById('descripcion').textContent = data.descripcion ? data.descripcion : 'N/A';
                document.getElementById('categoria_productos_id').textContent = data.categoria ? data.categoria.descripcion : 'N/A';
                document.getElementById('unidad_medida_id').textContent = data.unidadmedida ? data.unidadmedida.descripcion : 'N/A';
                document.getElementById('ubicacion_id').textContent = data.ubicacion ? data.ubicacion.descripcion : 'N/A';
                document.getElementById('cantidad').textContent = data.cantidad;
                document.getElementById('igv').textContent = data.igv;
                document.getElementById('precio_actual').textContent = data.precio_actual;

                // Mostrar la imagen del producto
                document.getElementById('imagen').src = data.imagen ? `{{ asset('${data.imagen}') }}` : '';


                // Actualizar el estado del producto
                let estado = data.estado ? data.estado.descripcion : 'N/A';

                let estadoDiv = document.getElementById('estado_producto_div');
                if (estado === 'Activo') {
                    estadoDiv.innerHTML = "<span class='estado-activo'>Activo</span>";
                } else if (estado === 'Inactivo') {
                    estadoDiv.innerHTML = "<span class='estado-desactivado'>Inactivo</span>";
                } else {
                    estadoDiv.innerHTML = "<span class='estado-desconocido'>Desconocido</span>";
                }

                // Formatear la fecha de creación
                let fechaCreacion = new Date(data.created_at);
                document.getElementById('created_at').textContent = fechaCreacion.toLocaleString();

                let fechaEdicion = new Date(data.updated_at);
                document.getElementById('updated_at').textContent = fechaEdicion.toLocaleString();

                $(".modal-header").css("background-color", "rgba(53, 142, 212)");
                $(".modal-header").css("color", "rgba(222, 235, 245)");
                $(".modal-title").text("Detalles del Producto");
                $('#modalVerProducto').modal('show');
            });
    }

    // Cerrar el modal al hacer clic en el botón de cancelar o en la 'X'
    $("#modalVerProducto").on('hidden.bs.modal', function() {
        $("#modalVerProducto").trigger("reset");
    });

    $(".close, .btn-secondary").click(function() {
        $("#modalVerProducto").modal("hide");
    });
</script>