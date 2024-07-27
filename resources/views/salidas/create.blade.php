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
                    <h3>Crear Salida de Productos</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('salidas.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="productos">Productos</label>
                                <table class="table">
                                    <thead class="text-center">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>Costo Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productos" class="text-center">
                                        <tr>
                                            <td>
                                                <select name="productos[0][producto_id]" class="form-control producto-select" required>
                                                    <option>Selecciona un producto</option>
                                                    @foreach($productos as $producto)
                                                    <option value="{{ $producto->id }}" data-price="{{ $producto->precio_actual }}">
                                                        {{ $producto->nombre }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="productos[0][cantidad]" class="form-control cantidad-input" min="1" required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="productos[0][precio_unitario]" class="form-control precio-input" min="0.01" readonly required>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="productos[0][costo_total]" class="form-control costo-input" readonly required>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" id="add-producto">Añadir Producto</button>
                            </div>
                            <br>
                            <div class="car-body">
                                <button type="submit" class="btn btn-success">Crear Salida</button>
                                <a href="{{ route('salidas.index') }}" class="btn btn-dark">Salir</a>
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
                    <option>Selecciona un producto</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" data-price="{{ $producto->precio_actual }}">
                                {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="productos[${rowCount}][cantidad]" class="form-control cantidad-input" min="1" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="productos[${rowCount}][precio_unitario]"  class="form-control precio-input" min="0.1" readonly required>
                </td>
                <td>
                    <input type="number" step="0.01" name="productos[${rowCount}][costo_total]" class="form-control costo-input" readonly required>
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

            row.querySelector('.cantidad-input').addEventListener('input', function() {
                calcularCostoTotal(row);
            });
            row.querySelector('.remove-producto').addEventListener('click', function() {
                let row = this.closest('tr');
                row.remove();
            });
        });

        document.querySelectorAll('.cantidad-input').forEach(function(input) {
            input.addEventListener('input', function() {
                calcularCostoTotal(input.closest('tr'));
            });
        });

        function calcularCostoTotal(row) {
            let cantidad = row.querySelector('.cantidad-input').value;
            let precioUnitario = row.querySelector('.precio-input').value;
            let costoTotalInput = row.querySelector('.costo-input');
            costoTotalInput.value = (cantidad * precioUnitario).toFixed(2);
        }

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
                row.querySelector('.precio-input').value = selectedOption.getAttribute('data-price');
                calcularCostoTotal(row);
            }
        }
    });
</script>