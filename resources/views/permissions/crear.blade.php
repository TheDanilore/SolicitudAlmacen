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
                    <h3>Crear Permiso</h3>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-gray-900">
                        <form method="POST" action="{{ route('permissions.store') }}">
                            @csrf

                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="name" class="col-form-label">Nombre:</label>
                                    <input type="text" minlength="5" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-body">
                                <button type="submit" id="btnGuardar" class="btn btn-success">Guardar</button>
                                <a id="btnSalirPermiso" class="btn btn-dark" href="{{ route('permissions.index') }}">Salir</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
