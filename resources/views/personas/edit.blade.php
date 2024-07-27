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
                    <h3>Editar Persona</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('personas.update', $persona->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="primer_nombre" class="col-form-label">Primer Nombre:</label>
                                    <input minlength="2" type="text" class="form-control @error('primer_nombre') is-invalid @enderror" id="primer_nombre" name="primer_nombre" value="{{ old('primer_nombre', $persona->primer_nombre) }}" required autofocus>
                                    @error('primer_nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="segundo_nombre" class="col-form-label">Segundo Nombre:</label>
                                    <input type="text" class="form-control @error('segundo_nombre') is-invalid @enderror" id="segundo_nombre" name="segundo_nombre" value="{{ old('segundo_nombre', $persona->segundo_nombre) }}">
                                    @error('segundo_nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="apellido_paterno" class="col-form-label">Apellido Paterno:</label>
                                    <input minlength="5" type="text" class="form-control @error('apellido_paterno') is-invalid @enderror" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno', $persona->apellido_paterno) }}" required>
                                    @error('apellido_paterno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="apellido_materno" class="col-form-label">Apellido Materno:</label>
                                    <input minlength="5" type="text" class="form-control @error('apellido_materno') is-invalid @enderror" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno', $persona->apellido_materno) }}" required>
                                    @error('apellido_materno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <div class="input-group">
                                        <select id="prefijo_telefono" name="prefijo_telefono" class="form-control">
                                            <option value="+51">+51</option>
                                            <option value="+1">+1</option>
                                            <option value="+44">+44</option>
                                        </select>
                                        <input type="text" minlength="9" id="telefono" name="telefono" class="form-control" placeholder="987654321" value="{{ old('telefono', $persona->telefono) }}">
                                    </div>
                                    @error('telefono')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="direccion" class="col-form-label">Dirección:</label>
                                    <input type="text" minlength="10" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $persona->direccion) }}" required>
                                    @error('direccion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tipo_documento_identidad_id" class="col-form-label">Tipo Documento Identidad:</label>
                                    <select class="form-control @error('tipo_documento_identidad_id') is-invalid @enderror" id="tipo_documento_identidad_id" name="tipo_documento_identidad_id" required>
                                        <option value="">Seleccione</option>
                                        @foreach($tipo as $t)
                                        <option value="{{ $t->id }}" {{$t->id? 'selected' : ''  }}>
                                            {{ $t->abreviatura }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('tipo_documento_identidad_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="numero_documento" class="col-form-label">Número Documento:</label>
                                    <input type="text" minlength="8" class="form-control @error('numero_documento') is-invalid @enderror" id="numero_documento" name="numero_documento" value="{{ old('numero_documento', $persona->numero_documento) }}" required>
                                    @error('numero_documento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <br>
                            <div class="car-body">
                                <button type="submit" id="btnGuardar" class="btn btn-success">Actualizar</button>
                                <a href="{{ route('personas.index') }}" class="btn btn-dark">Salir</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>