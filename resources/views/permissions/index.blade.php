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
                    <h2>{{ __('Lista de Permisos') }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a id="btnNuevoPermiso" class="btn btn-success btnCrear" href="{{ route('permissions.create') }}">Nuevo</a>
                        <!-- Botón de descarga con menú desplegable -->
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Descargar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('permissions.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                                <a class="dropdown-item" href="{{ route('permissions.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="dropdown-item" href="{{ route('permissions.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
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
                            <table id="tablaPermiso" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>NOMBRE</th>
                                        <th>Guard Name</th>
                                        <th>FECHA DE CREACION</th>
                                        <th>FECHA DE MODIFICACION</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($permisos as $permiso)
                                    <tr>
                                        <td>{{ $permiso->id}}</td>
                                        <td>{{ $permiso->name }}</td>
                                        <td>{{ $permiso->guard_name }}</td>
                                        <td>{{ $permiso->created_at }}</td>
                                        <td>{{ $permiso->updated_at }}</td>
                                        <td>
                                            <button class='btn-circle btn-primary btnVer' onclick="showPermiso({{ $permiso->id }})"><i class="fa-solid fa-eye"></i></button>
                                            &nbsp;
                                            <a class="btn-circle btn-info btnEditar" href="{{ route('permissions.edit',$permiso->id) }}"><i class="fa-solid fa-pencil"></i></a>
                                            &nbsp;
                                            @hasanyrole('Administrador|Jefe de Almacen')
                                            <button class='btn-circle btn-danger btnEliminar' onclick="confirmDelete({{ $permiso->id }})"><i class="fa-solid fa-trash"></i></button>
                                            @endhasanyrole
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
            <div class="modal fade" id="modalVerPermiso" tabindex="-1" role="dialog" aria-labelledby="modalVerPermisoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalVerPermisoLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>ID:</strong> <span id="id"></span></p>
                                        <p><strong>Nombre:</strong> <span id="name"></span></p>
                                        <p><strong>Guard Name:</strong> <span id="guard_name"></span></p>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Creado por:</strong> <span id="user_id_created"></span></p>
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
            title: '¿Está seguro de eliminar el permiso?',
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
                form.action = "{{ route('permissions.destroy', '') }}/" + id;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function showPermiso(id) {
        fetch(`/permissions/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('id').textContent = data.id;
                document.getElementById('user_id_created').textContent = data.audit && data.audit.usercreated ? data.audit.usercreated.name : 'N/A';
                document.getElementById('user_id_updated').textContent = data.audit && data.audit.userupdated ? data.audit.userupdated.name : 'N/A';
                document.getElementById('name').textContent = data.name ? data.name : 'N/A';
                document.getElementById('guard_name').textContent = data.guard_name ? data.guard_name : 'N/A';


                // Formatear la fecha de creación
                let fechaCreacion = new Date(data.created_at);
                document.getElementById('created_at').textContent = fechaCreacion.toLocaleString();

                let fechaEdicion = new Date(data.updated_at);
                document.getElementById('updated_at').textContent = fechaEdicion.toLocaleString();

                $(".modal-header").css("background-color", "rgba(53, 142, 212)");
                $(".modal-header").css("color", "rgba(222, 235, 245)");
                $(".modal-title").text("Detalles de la Dependencia");
                $('#modalVerPermiso').modal('show');
            });
    }

    // Cerrar el modal al hacer clic en el botón de cancelar o en la 'X'
    $("#modalVerPermiso").on('hidden.bs.modal', function() {
        $("#modalVerPermiso").trigger("reset");
    });

    $(".close, .btn-secondary").click(function() {
        $("#modalVerPermiso").modal("hide");
    });
</script>