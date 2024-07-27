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
          <h2>{{ __('Lista de Unidades de Medida') }}</h2>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <button id="btnNuevo" type="button" class="btn btn-success btnCrear" data-toggle="modal">Nuevo</button>
            <!-- Botón de descarga con menú desplegable -->
            <div class="btn-group float-right">
              <button type="button" class="btn btn-primary dropdown-toggle" id="btnDescargar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Descargar
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('unidadmedida.export', ['format' => 'excel']) }}"><i class="fas fa-file-excel"></i> Excel</a>
                <a class="dropdown-item" href="{{ route('unidadmedida.export', ['format' => 'pdf']) }}"><i class="fas fa-file-pdf"></i> PDF</a>
                <a class="dropdown-item" href="{{ route('unidadmedida.export', ['format' => 'word']) }}"><i class="fas fa-file-word"></i> Word</a>
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
              <table id="tablaUnidadMedida" class="table table-striped table-bordered table-condensed text-gray-900" style="width:100%">
                <thead class="text-center">
                  <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Abreviación</th>
                    <th>FECHA DE CREACION</th>
                    <th>ACCIONES</th>
                  </tr>
                </thead>
                <tbody>

                  @foreach ($unidadmedida as $medida)
                  <tr>
                    <td>{{ $medida->id }}</td>
                    <td>{{ $medida->descripcion }}</td>
                    <td>{{ $medida->abreviacion }}</td>
                    <td>{{ $medida->created_at }}</td>
                    <td>

                    </td>
                  </tr>
                  @endforeach

                </tbody>

              </table>
            </div>

          </div>
        </div>
      </div>
      <!--Modal para CRUD - nuevo-->
      <div class="modal fade" id="modalCRUDUnidadMedida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="formnuevoUnidadMedida" method="POST" action="{{ route('unidadmedida.store') }}">
              @csrf
              <div class="modal-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                <div class="form-group">
                  <label for="descripcion" class="col-form-label">Nombre:</label>
                  <input type="text" class="form-control @error('descripcion') is-invalid @enderror" minlength="5" id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required autofocus>
                  @error('descripcion')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="abreviacion" class="col-form-label">Abreviatura:</label>
                  <input type="text" class="form-control @error('abreviacion') is-invalid @enderror" minlength="1" id="abreviacion" name="abreviacion" value="{{ old('abreviacion') }}" required autofocus>
                  @error('abreviacion')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Modal para CRUD - editar -->
      <div class="modal fade" id="modalEditarUnidadMedida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar Unidad de Medida</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="formEditarUnidadMedida" method="POST" action="">
              @csrf
              @method('PUT')
              <div class="modal-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                <div class="form-group">
                  <label for="descripcion" class="col-form-label">Nombre:</label>
                  <input type="text" class="form-control @error('descripcion') is-invalid @enderror" minlength="5" id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required autofocus>
                  @error('descripcion')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="abreviacion" class="col-form-label">Abreviatura:</label>
                  <input type="text" class="form-control @error('abreviacion') is-invalid @enderror" minlength="1" id="abreviacion" name="abreviacion" value="{{ old('abreviacion') }}" required autofocus>
                  @error('abreviacion')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="created_at" class="col-form-label">Fecha de creacion:</label>
                  <input type="text" class="form-control @error('created_at') is-invalid @enderror" id="created_at" name="created_at" value="{{ old('created_at') }}" required autofocus readonly>
                  @error('created_at')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="updated_at" class="col-form-label">Fecha de Modificación:</label>
                  <input type="text" class="form-control @error('updated_at') is-invalid @enderror" id="updated_at" name="updated_at" value="{{ old('updated_at') }}" required autofocus readonly>
                  @error('updated_at')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
              </div>
            </form>
          </div>
        </div>
      </div>

</x-app-layout>