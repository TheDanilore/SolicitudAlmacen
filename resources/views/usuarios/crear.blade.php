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
                    <h3>Crear Usuario</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('usuarios.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name" class="col-form-label">Apodo:</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" minlength="5" id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" minlength="10" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-form-label">Contraseña:</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="col-form-label">Confirmar Contraseña:</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="form-group">
                                <label for="persona_id">Persona:</label>
                                <select class="form-control" id="persona_id" name="persona_id" required>
                                    <option value="">Seleccionar Persona</option>
                                    @foreach ($personas as $persona)
                                    <option value="{{ $persona->id }}" {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
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
                                    <option value="{{ $unidad->id }}" {{ old('unidad_id') == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->descripcion }} --> {{ $unidad->dependencia->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="roles">Roles:</label>
                                <select class="form-control" id="roles" name="roles[]" multiple require>
                                    <option value="">Seleccionar Rol</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role }}">
                                        {{ $role }}
                                    </option>
                                    @endforeach
                                </select>
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