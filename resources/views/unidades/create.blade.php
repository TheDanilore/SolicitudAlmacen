<x-app-layout>
    <div class="card cardForm" style="max-width: 600px;">
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
                <div class="row">
                    <div class="card-title text-gray-700">
                        <h4>Crear Unidad</h4>
                    </div>
                    <div class="col-12 text-gray-900">
                        <form method="POST" action="{{ route('unidades.store') }}">
                            @csrf
                            <div class="form-group ">
                                <label for="descripcion">Descripción</label>
                                <input type="text" class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion') }}">
                                @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="dependencia_id">Dependencia</label>
                                <select name="dependencia_id" id="dependencia_id" class="form-control">
                                    <option value="">Seleccionar Dependencia</option>
                                    @foreach($dependencias as $dependencia)

                                    <option value="{{ $dependencia->id }}">{{ $dependencia->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('dependencia_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <br>
                            <div class="car-body">
                                <button type="submit" id="btnGuardar" class="btn btn-success">Crear Unidad</button>
                                <a href="{{ route('unidades.index') }}" class="btn btn-dark">Salir</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>