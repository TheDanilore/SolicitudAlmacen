<head>
  <link href="{{ asset('css/toggle-switch.css') }}" rel="stylesheet" type="text/css">
</head>

<x-app-layout>
<div class="card cardForm" style="max-width: 1000px;">
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
          <h3>Editar Producto</h3>
        </div>
        <div class="row">
          <div class="col-lg-12 text-gray-900">
            <form method="POST" action="{{ route('productos.update', $producto->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="form-group">
                <label for="nombre" lass="col-form-label">Nombre:</label>
                <input type="text" minlength="4" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required autofocus>
                @error('nombre')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="descripcion" class="col-form-label">Descripcion:</label>
                <input type="text" minlength="5" class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" value="{{ old('descripcion', $producto->descripcion) }}" required autofocus>
                @error('descripcion')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>


              <div class="form-group">
                <label for="imagen" minlength="5" class="col-form-label">Imagen del Producto:</label>
                <div class="mt-2 flex justify-center items-center">
                  <div>
                    @if($producto->imagen)
                    <img id="previewImage" src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-productos-update">
                    @else
                    <span>No Imagen</span>
                    @endif
                  </div>
                  <div id="dropArea" class="flex flex-col justify-center items-center text-gray-900 border border-dashed border-gray-400 p-3 text-center">
                    <h4>Arrastra y suelta tu imagen aquí</h4>
                    <h1><i class="fa-regular fa-images"></i></h1>
                    <input type="file" name="imagen" id="imagen" value="{{ old('imagen', $producto->imagen) }}" accept="image/*" class="d-none">
                  </div>
                </div>

                @error('imagen')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="categoria_productos_id">Categoria:</label>
                <select class="form-control" id="categoria_productos_id" name="categoria_productos_id" required>
                  <option value="">Seleccionar</option>
                  @foreach($categoria_productos as $categoria)
                  <option value="{{ $categoria->id }}" {{ $categoria->id == $producto->categoria_productos_id ? 'selected' : '' }}>
                    {{ $categoria->descripcion }}
                  </option>
                  @endforeach
                </select>
                @error('categoria_productos_id')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="unidad_medida_id">Unidad de Medida:</label>
                <select class="form-control" id="unidad_medida_id" name="unidad_medida_id" required>
                  <option value="">Seleccionar</option>
                  @foreach ($unidades as $medida)
                  <option value="{{ $medida->id }}" {{ $medida->id == $producto->unidad_medida_id ? 'selected' : '' }}>
                    {{ $medida->descripcion }}
                  </option>
                  @endforeach
                </select>
                @error('unidad_medida_id')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="ubicacion_id">Ubicación:</label>
                <select class="form-control" id="ubicacion_id" name="ubicacion_id" required>
                  <option value="">Seleccionar Ubicación</option>
                  @foreach ($ubicaciones as $ubicacion)
                  <option value="{{ $ubicacion->id }}" {{ $ubicacion->id == $producto->ubicacion_id ? 'selected' : '' }}>
                    {{ $ubicacion->descripcion }}
                  </option>
                  @endforeach
                </select>
                @error('ubicacion_id')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="cantidad" class="col-form-label">Cantidad Actual:</label>
                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" min="0" value="{{ old('cantidad', $producto->cantidad) }}" required>
                @error('cantidad')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="precio_actual" class="col-form-label">Precio Actual:</label>
                <input type="number" step="0.01" class="form-control @error('precio_actual') is-invalid @enderror" id="precio_actual" name="precio_actual" min="0.01" value="{{ old('precio_actual', $producto->precio_actual) }}" required>
                @error('precio_actual')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="form-group">
                <label for="estado_producto_id">Estado:</label><br>
                <div class="switch-button">
                  <input type="hidden" name="estado_producto_id" value="2"> <!-- Valor por defecto cuando está apagado -->
                  <input type="checkbox" id="estado_producto_id" value="1" name="estado_producto_id" class="switch-button__checkbox" {{ old('estado_producto_id', $producto->estado_producto_id) == 1 ? 'checked' : '' }}>
                  <label for="estado_producto_id" class="switch-button__label"></label>
                </div>
                <span id="estado_label">{{ old('estado_producto_id', $producto->estado_producto_id) == 1 ? 'Activo' : 'Desactivado' }}</span>
              </div>



              <div class="card-body">
                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                <a id="btnSalirProducto" class="btn btn-dark" href="{{ route('productos.index') }}">Salir</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('dropArea');
    const profileImageInput = document.getElementById('imagen');
    const previewImage = document.getElementById('previewImage');

    dropArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropArea.classList.add('border-primary');
    });

    dropArea.addEventListener('dragleave', () => {
      dropArea.classList.remove('border-primary');
    });

    dropArea.addEventListener('drop', (e) => {
      e.preventDefault();
      dropArea.classList.remove('border-primary');
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        profileImageInput.files = files;
        previewImageFile(files[0]);
      }
    });

    dropArea.addEventListener('click', () => {
      profileImageInput.click();
    });

    profileImageInput.addEventListener('change', (e) => {
      const files = e.target.files;
      if (files.length > 0) {
        previewImageFile(files[0]);
      }
    });

    function previewImageFile(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImage.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>