<x-app-layout>
    <div class="card cardForm" style="max-width: 5000px;">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>

            <div class="py-12">
                {{ __("Bienvenido a nuestro sistema") }}
                @role('Administrador')
                <p>Administrador</p>
                @endrole
                @role('Jefe de Dependencia')
                <p>Jefe de Dependencia</p>
                @endrole
                @role('Empleado de Unidad')
                <p>Empleado de Unidad</p>
                @endrole
                @role('Empleado de Almacen')
                <p>Empleado de Almacen</p>
                @endrole
                @role('Jefe de Almacen')
                <p>Jefe de Almacen</p>
                @endrole
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        @role('Administrador')
        <!-- Total de Usuarios -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text font-weight-bold text-primary text-uppercase mb-1">
                                Total de Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cantidadUsuarios }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Roles -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text font-weight-bold text-success text-uppercase mb-1">
                                Total de Roles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cantidadRoles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Roles -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text font-weight-bold text-info text-uppercase mb-1">
                                Total de Permisos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cantidadPermisos }} </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-id-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @hasanyrole('Jefe de Almacen|Empleado de Almacen|Administrador')
        <!-- Solicitudes Pendientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text font-weight-bold text-warning text-uppercase mb-1">
                                Solicitudes Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="solicitudesPendientes">{{ $cantidadPendiente }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole
        @hasanyrole('Jefe de Dependencia')
        <!-- Solicitudes Pendientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text font-weight-bold text-warning text-uppercase mb-1">
                                Solicitudes Pendientes por Revisar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="solicitudesPendientes">{{ $cantidadPendienteUnidad }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Solicitudes Pendientes con el almacen-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text font-weight-bold text-info text-uppercase mb-1">
                                Solicitudes enviadas Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="solicitudesPendientes">{{ $cantidadPendienteUnidadAlmacen }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole
        @hasanyrole('Empleado de Unidad')
        <!-- Solicitudes Pendientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text font-weight-bold text-warning text-uppercase mb-1">
                                Solicitudes enviadas Pendientes de Aprobacion</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="solicitudesPendientes">{{ $cantidadPendienteSolicitud }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

    </div>
</x-app-layout>