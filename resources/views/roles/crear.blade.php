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
                    <h3>Crear Rol</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('roles.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">Nombre del Rol:</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" minlength="5" id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label>
                                    <h4>Permisos para este Rol:</h4>
                                </label>
                                <br>
                                <!-- Checkbox para seleccionar todos -->

                                <input type="checkbox" id="select-all-permissions">
                                <label>
                                    <h5>Seleccionar Todos</h5>
                                </label>
                                <br>
                                <br>
                                @foreach($permission as $permi)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $permi->name }}" class="name" {{ in_array($permi->name, (array) old('permission', [])) ? 'checked' : '' }}>
                                    {{ $permi->name }}
                                </label>
                                <br>
                                @endforeach
                            </div>

                            <div class="card-body">
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <a id="btnSalirRoles" class="btn btn-dark" href="{{ route('roles.index') }}">Salir</a>
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
        // Obtener referencia al checkbox 'Seleccionar Todos'
        const selectAllCheckbox = document.getElementById('select-all-permissions');

        // Obtener todos los checkboxes de permisos
        const permissionCheckboxes = document.querySelectorAll('.name');

        // Escuchar cambios en el checkbox 'Seleccionar Todos'
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;

            // Marcar/desmarcar todos los checkboxes de permisos
            permissionCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });
    });
</script>