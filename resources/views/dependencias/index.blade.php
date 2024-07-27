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
          <h2>{{ __('Lista de Dependencias') }}</h2>
        </div>
        <div class="row">
          <div class="col-lg-12">

            <div class="car-body">
              <a id="btnNuevaDependencia" class="btn btn-success btnCrear" href="{{ route('dependencias.create') }}">Nueva Dependencia</a>
              <!-- Botón de descarga con menú desplegable -->
              <div class="btn-group float-right">
                <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Descargar
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('dependencias.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                  <a class="dropdown-item" href="{{ route('dependencias.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                  <a class="dropdown-item" href="{{ route('dependencias.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
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
              <table id="tablaDependencia" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                <thead class="text-center">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>FECHA DE CREACION</th>
                    <th>FECHA DE MODIFICACION</th>
                    <th>ACCIONES</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach ($dependencias as $dependencia)
                  <tr>
                    <td>{{ $dependencia->id }}</td>
                    <td>{{ $dependencia->nombre }}</td>
                    <td>
                      <div class='text-center'>
                        @if ($dependencia->estado->descripcion == 'Activo')
                        <span class="estado-activo">{{ $dependencia->estado->descripcion}}</span>
                        @elseif ($dependencia->estado->descripcion == 'Inactivo')
                        <span class="estado-desactivado">{{ $dependencia->estado->descripcion}}</span>
                        @endif
                      </div>
                    </td>
                    <td>{{ $dependencia->created_at }}</td>
                    <td>{{ $dependencia->updated_at }}</td>
                    <td>
                      <div class='text-center'>
                        <div class='btn-group'>
                          <button class='btn-circle btn-primary btnVer' onclick="showDependencia({{ $dependencia->id }})"><i class="fa-solid fa-eye"></i></button>
                          &nbsp;
                          <a class="btn-circle btn-info btnEditar" href="{{ route('dependencias.edit', $dependencia->id) }}"><i class="fa-solid fa-pencil"></i></a>
                          &nbsp;
                          @hasanyrole('Administrador|Jefe de Almacen')
                          <button class='btn-circle btn-danger btnEliminar' onclick="confirmDelete({{ $dependencia->id }})"><i class="fa-solid fa-trash"></i></button>
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
      <div class="modal fade" id="modalVerDependencia" tabindex="-1" role="dialog" aria-labelledby="modalVerDependenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalVerDependenciaLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>ID:</strong> <span id="id"></span></p>
                    <p><strong>Creado por:</strong> <span id="user_id_created"></span></p>
                    <p><strong>Actualizado por:</strong> <span id="user_id_updated"></span></p>
                    <p><strong>Nombre:</strong> <span id="nombre"></span></p>
                    <p><strong>Estado:</strong> <span id="estado_dependencia_div"></span>
                    </p>
                  </div>
                  <div class="col-md-6">
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
      title: '¿Está seguro de eliminar la dependencia?',
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
        form.action = "{{ route('dependencias.destroy', '') }}/" + id;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
      }
    });
  }



  function showDependencia(id) {
    fetch(`/dependencias/${id}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('id').textContent = data.id;
        document.getElementById('user_id_created').textContent = data.audit && data.audit.usercreated ? data.audit.usercreated.name : 'N/A';
        document.getElementById('user_id_updated').textContent = data.audit && data.audit.userupdated ? data.audit.userupdated.name : 'N/A';
        document.getElementById('nombre').textContent = data.nombre ? data.nombre : 'N/A';

        // Actualizar el estado del producto
        let estado = data.estado ? data.estado.descripcion : 'N/A';

        let estadoDiv = document.getElementById('estado_dependencia_div');
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
        $(".modal-title").text("Detalles de la Dependencia");
        $('#modalVerDependencia').modal('show');
      });
  }

  // Cerrar el modal al hacer clic en el botón de cancelar o en la 'X'
  $("#modalVerDependencia").on('hidden.bs.modal', function() {
    $("#modalVerDependencia").trigger("reset");
  });

  $(".close, .btn-secondary").click(function() {
    $("#modalVerDependencia").modal("hide");
  });
</script>