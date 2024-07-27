<x-app-layout>
<div class="card cardForm" style="max-width: 700px;">
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
                    <h3>Crear Ubicación</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('ubicacion.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="code">Código</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" id="code" class="form-control" value="{{ old('code') }}">
                                @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <input type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion') }}">
                                @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <br>
                            <div class="car-body">
                                <button type="submit" id="btnGuardar" class="btn btn-success">Crear Unidad</button>
                                <a href="{{ route('ubicacion.index') }}" class="btn btn-dark">Salir</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>