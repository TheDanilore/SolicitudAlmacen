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
                    <h3>Crear Proveedor</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('proveedor.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="ruc" class="col-form-label">Ruc:</label>
                                <input type="number" class="form-control @error('ruc') is-invalid @enderror" id="ruc" name="ruc" value="{{ old('ruc') }}" required autofocus>
                                @error('ruc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="razon_social" class="col-form-label">Razon Social:</label>
                                <input type="text" minlength="11" class="form-control @error('razon_social') is-invalid @enderror" id="razon_social" name="razon_social" value="{{ old('razon_social') }}" required autofocus>
                                @error('razon_social')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="direccion" class="col-form-label">Dirección:</label>
                                <input type="text" minlength="10" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                                @error('direccion')
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
                                    <input type="text" minlength="9" id="telefono" name="telefono" class="form-control" placeholder="987654321" value="{{ old('telefono') }}">
                                </div>
                                @error('telefono')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <br>
                            <div class="card-body">
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <a id="btnSalirDependencia" class="btn btn-dark" href="{{ route('proveedor.index') }}">Salir</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>