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
                    <h2>{{ __('Lista de Personas') }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <a id="btnNuevaPersona" class="btn btn-success btnCrear" href="{{ route('personas.create') }}">Nuevo</a>
                        <!-- Botón de descarga con menú desplegable -->
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Descargar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('personas.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                                <a class="dropdown-item" href="{{ route('personas.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                                <a class="dropdown-item" href="{{ route('personas.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
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
                            <table id="tablaPersonas" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                                <thead class="text-center">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Tipo Documento</th>
                                        <th>N° Documento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($personas as $persona)
                                    <tr>
                                        <td>{{ $persona->id}}</td>
                                        <td>{{ $persona->primer_nombre }} {{ $persona->segundo_nombre }}
                                            {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                        </td>
                                        <td>{{ $persona->prefijo_telefono }}{{ $persona->telefono }}</td>
                                        <td>{{ $persona->direccion }}</td>
                                        <td>{{ $persona->tipoDocumentoIdentidad->abreviatura }}</td>
                                        <td>{{ $persona->numero_documento }}</td>
                                        <td>
                                            <div class='text-center'>
                                                <div class='btn-group'>
                                                    <button class='btn-circle btn-primary btnVer' onclick="showPersona({{ $persona->id }})"><i class="fa-solid fa-eye"></i></button>
                                                    &nbsp;
                                                    <a class="btn-circle btn-info btnEditar" href="{{ route('personas.edit', $persona->id) }}"><i class="fa-solid fa-pencil"></i></a>
                                                    &nbsp;
                                                    @hasanyrole('Administrador|Jefe de Almacen')
                                                    <button class='btn-circle btn-danger btnEliminar' onclick="confirmDelete({{ $persona->id }})"><i class="fa-solid fa-trash"></i></button>
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
            <div class="modal fade" id="modalVerPersona" tabindex="-1" role="dialog" aria-labelledby="modalVerPersonaLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalVerPersonaLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>ID:</strong> <span id="id"></span></p>
                                        <p><strong>Primer Nombre:</strong> <span id="primer_nombre"></span></p>
                                        <p><strong>Segundo Nombre:</strong> <span id="segundo_nombre"></span></p>
                                        <p><strong>Apellido Paterno:</strong> <span id="apellido_paterno"></span></p>
                                        <p><strong>Apellido Materno:</strong> <span id="apellido_materno"></span></p>
                                        <p><strong>Telefono:</strong> <span id="telefono"></span></p>
                                        <p><strong>Direccion:</strong> <span id="direccion"></span></p>
                                        <p><strong>Tipo de Documento Identidad:</strong> <span id="tipo_documento_identidad_id"></span></p>
                                        <p><strong>Numero de Documento:</strong> <span id="numero_documento"></span></p>

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
            title: '¿Está seguro de eliminar la persona',
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
                form.action = "{{ route('personas.destroy', '') }}/" + id;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function showPersona(id) {
        fetch(`/personas/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('id').textContent = data.id;
                document.getElementById('primer_nombre').textContent = data.primer_nombre ? data.primer_nombre : 'N/A';
                document.getElementById('segundo_nombre').textContent = data.segundo_nombre ? data.segundo_nombre : 'N/A';
                document.getElementById('apellido_paterno').textContent = data.apellido_paterno ? data.apellido_paterno : 'N/A';
                document.getElementById('apellido_materno').textContent = data.apellido_materno ? data.apellido_materno : 'N/A';
                document.getElementById('telefono').textContent = data.prefijo_telefono ? data.prefijo_telefono + data.telefono : 'N/A';
                document.getElementById('direccion').textContent = data.direccion ? data.direccion : 'N/A';
                document.getElementById('tipo_documento_identidad_id').textContent = data.tipodocumentoidentidad ? data.tipodocumentoidentidad.descripcion : 'N/A';
                document.getElementById('numero_documento').textContent = data.numero_documento ? data.numero_documento : 'N/A';


                document.getElementById('user_id_created').textContent = data.audit && data.audit.usercreated ? data.audit.usercreated.email : 'N/A';
                document.getElementById('user_id_updated').textContent = data.audit && data.audit.userupdated ? data.audit.userupdated.email : 'N/A';

                // Formatear la fecha de creación
                let fechaCreacion = new Date(data.created_at);
                document.getElementById('created_at').textContent = fechaCreacion.toLocaleString();

                let fechaEdicion = new Date(data.updated_at);
                document.getElementById('updated_at').textContent = fechaEdicion.toLocaleString();

                $(".modal-header").css("background-color", "rgba(53, 142, 212)");
                $(".modal-header").css("color", "rgba(222, 235, 245)");
                $(".modal-title").text("Detalles de la Persona");
                $('#modalVerPersona').modal('show');
            });
    }

    // Cerrar el modal al hacer clic en el botón de cancelar o en la 'X'
    $("#modalVerPersona").on('hidden.bs.modal', function() {
        $("#modalVerPersona").trigger("reset");
    });

    $(".close, .btn-secondary").click(function() {
        $("#modalVerPersona").modal("hide");
    });
</script>