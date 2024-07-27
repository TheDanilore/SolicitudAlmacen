<x-app-layout>
    <div class="card cardForm" style="max-width: 2000px;">
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
                    <h3>Crear Solicitud (Unidad)</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12  text-gray-900">
                        <form method="POST" action="{{ route('solicitud_unidad.store') }}">
                            @csrf
                            <h4><strong>Dependencia:</strong> {{ $unidadUsuario->dependencia->nombre }}</h4>
                            <div class="form-group">
                                <h4><strong>Unidad:</strong> {{ $unidadUsuario->descripcion }}</h4>
                                <!-- Campo oculto para enviar la unidad del usuario -->
                                <input type="hidden" name="unidad_id" value="{{ $unidadUsuario->id }}">
                            </div>
                            <div class="form-group">
                                <label for="tipo_requerimiento_id" class="col-form-label">Tipo de Requerimiento</label>
                                <select class="form-control @error('tipo_requerimiento_id') is-invalid @enderror" id="tipo_requerimiento_id" name="tipo_requerimiento_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach($tipoRequerimiento as $tipo_req)
                                    <option value="{{ $tipo_req->id}}" {{ old('tipo_requerimiento_id') == $tipo_req->id ? 'selected' : '' }}>{{ $tipo_req->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_requerimiento')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="productos">Productos</label>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productos">
                                        <tr>
                                            <td>
                                                <select name="productos[0][producto_id]" class="form-control producto-select" required>
                                                    <option value="">Seleccione un producto</option>
                                                    @foreach($productos as $producto)
                                                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="productos[0][cantidad]" class="form-control" min="1" required>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" id="add-producto"><i class="fa-solid fa-plus"></i> Añadir Producto</button>
                            </div>
                            <br>
                            <div class="car-body">
                                <button type="submit" class="btn btn-success">Crear Solicitud</button>
                                <a href="{{ route('solicitud_unidad.index') }}" class="btn btn-dark">Salir</a>
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
        document.querySelectorAll('.producto-select').forEach(function(select) {
            select.addEventListener('change', function() {
                validarProductoSeleccionado(select);
            });
        });
        document.getElementById('add-producto').addEventListener('click', function() {
            let tbody = document.getElementById('productos');
            let rowCount = tbody.rows.length;
            let row = tbody.insertRow(rowCount);
            row.innerHTML = `
            <td>
                <select name="productos[${rowCount}][producto_id]" class="form-control producto-select" required>
                    <option value="">Seleccione un producto</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="productos[${rowCount}][cantidad]" class="form-control"  min="1" required>
            </td>
            <td>
                <div>
                    <button type="button" class="btn btn-danger remove-producto"><i class="fa-solid fa-trash-can"></i></button>
                </div>
                </td>
        `;
            let select = row.querySelector('.producto-select');
            select.addEventListener('change', function() {
                validarProductoSeleccionado(select);
            });
            row.querySelector('.remove-producto').addEventListener('click', function() {
                let row = this.closest('tr');
                row.remove();
            });

        });

        function validarProductoSeleccionado(select) {
            let selectedValue = select.value;
            let selects = document.querySelectorAll('.producto-select');
            let count = 0;
            selects.forEach(function(sel) {
                if (sel.value === selectedValue) {
                    count++;
                }
            });
            if (count > 1) {
                alert('Este producto ya ha sido seleccionado.');
                select.value = '';
            } else {
                let selectedOption = select.options[select.selectedIndex];
                let row = select.closest('tr');
            }
        }
    })
</script>