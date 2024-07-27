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
                    <h3>Editar Rol</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('roles.update', $role->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="name">Nombre del Rol:</label>
                                    <input type="text" id="name" name="name" class="form-control" minlength="5" value="{{ $role->name }}" required>
                                </div>
                            </div>



                            <div class="form-group">
                                <label>Permisos para este Rol:</label>
                                <br>
                                <!-- Checkbox para seleccionar todos -->

                                <input type="checkbox" id="select-all-permissions">
                                <label>
                                    <h5>Seleccionar Todos</h5>
                                </label>
                                <br>
                                <br>
                                @foreach($permission as $value)
                                <label>
                                    <input type="checkbox" name="permission[]" value="{{ $value->name }}" class="name" 
                                    {{ in_array($value->name, $rolePermissions) ? 'checked' : '' }}>
                                    {{ $value->name }}
                                </label>
                                <br>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label for="estado_rol_id">Estado:</label><br>
                                <div class="switch-button">
                                    <input type="hidden" name="estado_rol_id" value="2"> <!-- Valor por defecto cuando está apagado -->
                                    <input type="checkbox" id="estado_rol_id" value="1" name="estado_rol_id" class="switch-button__checkbox" {{ old('estado_rol_id', $role->estado_rol_id) == 1 ? 'checked' : '' }}>
                                    <label for="estado_rol_id" class="switch-button__label"></label>
                                </div>
                                <span id="estado_label">{{ old('estado_rol_id', $role->estado_rol_id) == 1 ? 'Activo' : 'Desactivado' }}</span>
                            </div>
                            <br>


                            <div class="car-body">
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