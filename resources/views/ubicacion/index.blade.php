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
          <h2>{{ __('Lista de Ubicaciones') }}</h2>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <a href="{{ route('ubicacion.create') }}" class="btn btn-success btnCrear">Crear Ubicación</a>
            <!-- Botón de descarga con menú desplegable -->
            <div class="btn-group float-right">
              <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Descargar
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('ubicacion.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                <a class="dropdown-item" href="{{ route('ubicacion.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="dropdown-item" href="{{ route('ubicacion.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
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
              <table id="tablaUbicaciones" class="table table-striped table-bordered table-condensed  text-gray-900" style="width:100%">
                <thead class="text-center">
                  <tr>
                    <th>ID</th>
                    <th>Codigo</th>
                    <th>Descripción</th>
                    <th>FECHA DE CREACION</th>
                    <th>ACCIONES</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ubicaciones as $ubicacion)
                  <tr>
                    <td>{{ $ubicacion->id }}</td>
                    <td>{{ $ubicacion->code }}</td>
                    <td>{{ $ubicacion->descripcion }}</td>
                    <td>{{ $ubicacion->created_at }}</td>
                    <td>
                      <div class='text-center'>
                        <div class='btn-group'>
                          <button class='btn-circle btn-primary btnVer' onclick="showUbicacion({{ $ubicacion->id }})"><i class="fa-solid fa-eye"></i></button>
                          &nbsp;
                          <a href="{{ route('ubicacion.edit', $ubicacion->id) }}" class="btn-circle btn-info btnEditar"><i class="fa-solid fa-pencil"></i></a>
                          &nbsp;
                          @hasanyrole('Administrador|Jefe de Almacen')
                          <button class='btn-circle btn-danger btnEliminar' onclick="confirmDelete({{ $ubicacion->id }})"><i class="fa-solid fa-trash"></i></button>
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
      <div class="modal fade" id="modalVerUbicacion" tabindex="-1" role="dialog" aria-labelledby="modalVerUbicacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalVerUbicacionLabel"></h5>
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
                    <p><strong>Codigo:</strong> <span id="code"></span></p>
                    <p><strong>Ubicación:</strong> <span id="descripcion"></span></p>
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
  $(document).ready(function() {
    var tablaUbicaciones = $("#tablaUbicaciones").DataTable({

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

  function confirmDelete(id) {
    Swal.fire({
      title: '¿Está seguro de eliminar la ubicación??',
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
        form.action = "{{ route('ubicacion.destroy', '') }}/" + id;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function showUbicacion(id) {
    fetch(`/ubicacion/${id}`)
      .then(response => response.json())
      .then(data => {
        document.getElementById('id').textContent = data.id;
        document.getElementById('user_id_created').textContent = data.audit && data.audit.usercreated ? data.audit.usercreated.name : 'N/A';
        document.getElementById('user_id_updated').textContent = data.audit && data.audit.userupdated ? data.audit.userupdated.name : 'N/A';
        document.getElementById('descripcion').textContent = data.descripcion ? data.descripcion : 'N/A';
        document.getElementById('code').textContent = data.code ? data.code : 'N/A';

        // Formatear la fecha de creación
        let fechaCreacion = new Date(data.created_at);
        document.getElementById('created_at').textContent = fechaCreacion.toLocaleString();

        let fechaEdicion = new Date(data.updated_at);
        document.getElementById('updated_at').textContent = fechaEdicion.toLocaleString();

        $(".modal-header").css("background-color", "rgba(53, 142, 212)");
        $(".modal-header").css("color", "rgba(222, 235, 245)");
        $(".modal-title").text("Detalles de la Ubicación");
        $('#modalVerUbicacion').modal('show');
      });
  }

  // Cerrar el modal al hacer clic en el botón de cancelar o en la 'X'
  $("#modalVerUbicacion").on('hidden.bs.modal', function() {
    $("#modalVerUbicacion").trigger("reset");
  });

  $(".close, .btn-secondary").click(function() {
    $("#modalVerUbicacion").modal("hide");
  });
</script>