<head>
  <link href="{{ asset('css/toggle-switch.css') }}" rel="stylesheet" type="text/css">
</head>

<x-app-layout>
<div class="card cardForm" style="max-width: 800px;">
    <div class="card-body">
      <div class="container">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
      </div>

      <div class="container">
        <div class="card-title text-gray-800">
          <h3>Editar Dependencia</h3>
        </div>
        <div class="row">
          <div class="col-lg-12 text-gray-900">
            <form method="POST" action="{{ route('dependencias.update', $dependencia->id) }}">
              @csrf
              @method('PUT')

              <div class="form-group">
                <label for="nombre" class="col-form-label">Nombre:</label>
                <input type="text" minlength="5" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $dependencia->nombre) }}" required autofocus>
                @error('nombre')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="estado_id">Estado:</label><br>
                <div class="switch-button">
                  <input type="hidden" name="estado_id" value="2"> <!-- Valor por defecto cuando está apagado -->
                  <input type="checkbox" id="estado_id" value="1" name="estado_id" class="switch-button__checkbox" {{ old('estado_id', $dependencia->estado_id) == 1 ? 'checked' : '' }}>
                  <label for="estado_id" class="switch-button__label"></label>
                </div>
                <span id="estado_label">{{ old('estado_id', $dependencia->estado_id) == 1 ? 'Activo' : 'Desactivado' }}</span>
              </div>

              <br>

              <div class="card-body">
                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                <a id="btnSalirDependencia" class="btn btn-dark" href="{{ route('dependencias.index') }}">Salir</a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>