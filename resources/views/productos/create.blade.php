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
                    <h3>Crear Producto</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="nombre" class="col-form-label">Nombre:</label>
                                <input type="text" minlength="4" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus>
                                @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="descripcion" minlength="5" class="col-form-label">Descripción:</label>
                                <input type="text" class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" value="{{ old('descripcion') }}" required autofocus>
                                @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="imagen" minlength="5" class="col-form-label">Imagen del Producto:</label>
                                <div>
                                    <img id="previewImage" class="img-productos-update">
                                </div>
                                <br>
                                <div id="dropArea" class="flex flex-col justify-center items-center text-gray-900 border border-dashed border-gray-400 p-3 text-center">
                                    <h4>Arrastra y suelta tu imagen aquí</h4>
                                    <h1><i class="fa-regular fa-images"></i></h1>
                                    <input type="file" name="imagen" id="imagen" value="{{ old('imagen') }}" accept="image/*" class="d-none" require autofocus>
                                </div>

                                @error('imagen')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>

                            <div class="form-group">
                                <label for="categoria_productos_id" class="col-form-label">Categoría:</label>
                                <select class="form-control @error('categoria_productos_id') is-invalid @enderror" id="categoria_productos_id" name="categoria_productos_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach($categoria_productos as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_productos_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="unidad_medida_id" class="col-form-label">Unidad de Medida:</label>
                                <select class="form-control @error('unidad_medida_id') is-invalid @enderror" id="unidad_medida_id" name="unidad_medida_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach($unidades as $medida)
                                    <option value="{{ $medida->id }}">{{ $medida->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('unidad_medida_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="ubicacion_id" class="col-form-label">Ubicación:</label>
                                <select class="form-control @error('ubicacion_id') is-invalid @enderror" id="ubicacion_id" name="ubicacion_id" required>
                                    <option value="">Seleccione Ubicación</option>
                                    @foreach($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->id }}">{{ $ubicacion->code }} - {{ $ubicacion->descripcion }}</option>
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
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" min="0" name="cantidad" value="{{ old('cantidad') }}" required>
                                @error('cantidad')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="precio_actual" class="col-form-label">Precio Actual:</label>
                                <input type="number" step="0.01" class="form-control @error('precio_actual') is-invalid @enderror" min="0.01" id="precio_actual" name="precio_actual" value="{{ old('precio_actual') }}" required>
                                @error('precio_actual')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <br>
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