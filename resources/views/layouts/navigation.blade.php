<ul class="navbar-nav sidebar bg-gradient-primary sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">

        <img src="{{ asset('img/almacen-logo.png') }}" alt="almacen" class="img-profile ">
        <div class="sidebar-brand-text mx-3">Almacen</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Dependencia
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    @hasanyrole('Jefe de Almacen|Responsable de Almacen|Jefe de Dependencia|Responsable de Unidad|Administrador')
    <li class="nav-item">
        <a class="nav-link collapsed text-gray-200" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapseSolicitud" aria-expanded="true" aria-controls="collapseSolicitud">
            <i class="fa-solid fa-folder-plus"></i>
            <span>Solicitud de Producto</span>
        </a>
        <div id="collapseSolicitud" class="collapse" aria-labelledby="headingSolicitud" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!--Jefe de Area-->
                @hasanyrole('Jefe de Almacen|Responsable de Almacen|Administrador')
                <h6 class="collapse-header">Almacen</h6>
                <a class="collapse-item" href="{{ route('solicitud_almacen.index') }}">Solicitudes Pendientes</a>
                @endhasanyrole

                <!--Jefe de Area-->
                @hasanyrole('Jefe de Dependencia|Administrador')
                <h6 class="collapse-header">Dependencia</h6>
                <a class="collapse-item" href="{{ route('solicitud_dependencia.index') }}">Solicitudes Pendientes</a>
                @endhasanyrole

                <!--Responsable de Unidad-->
                @hasanyrole('Responsable de Unidad|Administrador')
                <h6 class="collapse-header">Unidad</h6>
                <a class="collapse-item" href="{{ route('solicitud_unidad.index') }}">Ver Solicitudes</a>
                @endhasanyrole

            </div>
        </div>
    </li>
    @endhasanyrole

    @hasanyrole('Jefe de Almacen|Responsable de Almacen|Administrador')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Almacen
    </div>
    <!-- Nav Item - Pages Collapse Menu -->


    <li class="nav-item">
        <a class="nav-link collapsed text-gray-200" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapseMovimientos" aria-expanded="true" aria-controls="collapseMovimientos">
            <i class="fa-solid fa-arrows-up-down-left-right"></i>
            <span>Movimientos</span>
        </a>
        <div id="collapseMovimientos" class="collapse" aria-labelledby="headingMovimientos" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Movimientos</h6>
                <a class="collapse-item" href="{{ route('movimientos.index') }}">Movimientos</a>
            </div>
        </div>
    </li>
    @endhasanyrole

    <!-- Nav Item - Pages Collapse Menu -->

    @hasanyrole('Jefe de Almacen|Responsable de Almacen|Administrador')
    <li class="nav-item">
        <a class="nav-link collapsed text-gray-200" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapseentrada" aria-expanded="true" aria-controls="collapseentrada">
            <i class="fa-solid fa-truck-ramp-box"></i>
            <span>Gestion de Entradas</span>
        </a>
        <div id="collapseentrada" class="collapse" aria-labelledby="headingEntrada" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Entradas de Productos</h6>
                <a class="collapse-item" href="{{ route('entradas.index') }}">Entrada de Productos</a>
            </div>
        </div>
    </li>
    @endhasanyrole

    <!-- Nav Item - Pages Collapse Menu -->
    @hasanyrole('Jefe de Almacen|Responsable de Almacen|Administrador')
    <li class="nav-item">
        <a class="nav-link collapsed text-gray-100" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapseSalidas" aria-expanded="true" aria-controls="collapseSalidas">
            <i class="fa-solid fa-square-arrow-up-right"></i>
            <span>Gestion de Salidas</span>
        </a>
        <div id="collapseSalidas" class="collapse" aria-labelledby="headingSalidas" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Salidas de Productos</h6>
                <a class="collapse-item" href="{{ route('salidas.index') }}">Salidas de Productos</a>
            </div>
        </div>
    </li>
    @endhasanyrole

    <!-- Nav Item - Pages Collapse Menu -->
    @hasanyrole('Jefe de Almacen|Responsable de Almacen|Administrador')
    <li class="nav-item">
        <a class="nav-link collapsed text-gray-100" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Gestion de Productos</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Productos</h6>
                <a class="collapse-item" href="{{ route('productos.index') }}">Productos</a>
                <h6 class="collapse-header">Categorias</h6>
                <a class="collapse-item" href="{{ route('categoriaproductos.index') }}">Categorias</a>
                <h6 class="collapse-header">Unidades de Medida</h6>
                <a class="collapse-item" href="{{ route('unidadmedida.index') }}">Unidades de Medidas</a>
                <h6 class="collapse-header">Ubicacion</h6>
                <a class="collapse-item" href="{{ route('ubicacion.index') }}">Ubicacion</a>
            </div>
        </div>
    </li>
    @endhasanyrole

    <!-- Nav Item - Pages Collapse Menu -->
    @hasanyrole('Jefe de Almacen|Responsable de Almacen|Administrador')
    <li class="nav-item">
        <a class="nav-link collapsed text-gray-100" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapseProveedor" aria-expanded="true" aria-controls="collapseProveedor">
            <i class="fa-solid fa-boxes-packing"></i>
            <span>Gestion de Proveedores</span>
        </a>
        <div id="collapseProveedor" class="collapse" aria-labelledby="headingProveedor" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion de Proveedores</h6>
                <a class="collapse-item" href="{{ route('proveedor.index') }}">Proveedores</a>
            </div>
        </div>
    </li>
    @endhasanyrole


    @hasrole('Administrador')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Administrador
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <!-- Gestión de Usuarios-->


    <li class="nav-item">
        <a class="nav-link collapsed text-gray-100" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
            <i class="fa-solid fa-users"></i>
            <span>Gestión de Usuarios</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Usuarios:</h6>
                <a class="collapse-item" href="{{route('usuarios.index')}}">Usuarios</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Roles:</h6>
                <a class="collapse-item" href="{{route('roles.index')}}">Roles</a>
                <h6 class="collapse-header">Permisos:</h6>
                <a class="collapse-item" href="{{route('permissions.index')}}">Permisos</a>
                <h6 class="collapse-header">Personas:</h6>
                <a class="collapse-item" href="{{route('personas.index')}}">Personas</a>
            </div>
        </div>
    </li>
    @endrole

    @hasrole('Administrador')
    <li class="nav-item">
        <a class="nav-link collapsed text-gray-100" id="textPrincipalMenu" href="#" data-toggle="collapse" data-target="#collapseUnidades" aria-expanded="true" aria-controls="collapseUnidades">
            <i class="fa-solid fa-warehouse"></i>
            <span>Gestión de Dependencias</span>
        </a>
        <div id="collapseUnidades" class="collapse" aria-labelledby="headingUnidades" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Unidades (SubÁreas):</h6>
                <a class="collapse-item" href="{{route('unidades.index')}}">Unidades</a>
                <h6 class="collapse-header">Dependencias (Áreas):</h6>
                <a class="collapse-item" href="{{route('dependencias.index')}}">Dependencias</a>
            </div>
        </div>
    </li>
    @endrole


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>




</ul>