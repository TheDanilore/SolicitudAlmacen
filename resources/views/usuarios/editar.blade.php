<head>
    <link href="{{ asset('css/toggle-switch.css') }}" rel="stylesheet" type="text/css">
</head>

<x-app-layout>
<div class="card cardForm" style="max-width: 900px;">
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
                    <h3>Editar Usuario</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('usuarios.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name" class="col-form-label">Apodo:</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" minlength="5" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" minlength="10" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-form-label">Contraseña:</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-form-label">Confirmar Contraseña:</label>
                                <input type="password" class="form-control" id="password-confirm" name="password_confirmation">
                            </div>

                            <div class="form-group">
                                <label for="persona_id">Persona:</label>
                                <select class="form-control" id="persona_id" name="persona_id" required>
                                    <option value="">Seleccionar Persona</option>
                                    @foreach ($personas as $persona)
                                    <option value="{{ $persona->id }}" {{ $persona->id == $user->persona_id ? 'selected' : '' }}>
                                        {{ $persona->primer_nombre }} {{ $persona->segundo_nombre }}
                                        {{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="unidad_id">Unidad:</label>
                                <select class="form-control" id="unidad_id" name="unidad_id" required>
                                    <option value="">Seleccionar Unidad</option>
                                    @foreach ($unidades as $unidad)
                                    <option value="{{ $unidad->id }}" {{ $unidad->id == $user->unidad_id ? 'selected' : '' }}>
                                        {{ $unidad->descripcion }} --> {{ $unidad->dependencia->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="roles">Roles:</label>
                                <select class="form-control" id="roles" name="roles[]" multiple required>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ in_array($role, $userRole) ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="estado_usuario_id">Estado:</label><br>
                                <div class="switch-button">
                                    <input type="hidden" name="estado_usuario_id" value="2"> <!-- Valor por defecto cuando está apagado -->
                                    <input type="checkbox" id="estado_usuario_id" value="1" name="estado_usuario_id" class="switch-button__checkbox" {{ old('estado_usuario_id', $user->estado_usuario_id) == 1 ? 'checked' : '' }}>
                                    <label for="estado_usuario_id" class="switch-button__label"></label>
                                </div>
                                <span id="estado_label">{{ old('estado_usuario_id', $user->estado_usuario_id) == 1 ? 'Activo' : 'Desactivado' }}</span>
                            </div>
                            <br>
                            <div class="car-body">

                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <a id="btnSalirUsuario" class="btn btn-dark" href="{{ route('usuarios.index') }}">Salir</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>