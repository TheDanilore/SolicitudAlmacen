<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image-x/icon" href="{{ asset('img/almacen-logo.ico') }}">
    <meta name="description" content="Sistema de Almacen">
    <meta name="author" content="DANILORE">
    <title>Almacen</title>
    <!-- Font Awesome -->
    <link href="{{ asset('vendor/fontawesome-free-6.5.2-web/css/fontawesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/fontawesome-free-6.5.2-web/css/brands.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/fontawesome-free-6.5.2-web/css/solid.css') }}" rel="stylesheet" />
    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free-6.5.2-web/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">
    <!-- Estilo del estado-->
    <link rel="stylesheet" href="{{ asset('css/estados.css') }}">
    <link rel="stylesheet" href="{{ asset('css/indicadores.css') }}">
    <!-- Datatable-->
    <link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('vendor/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body id="page-top" class="light-mode">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.navigation')
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('layouts.topbar')
                <!-- End of Topbar -->
                <!-- Page Heading -->
                @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                @endisset
                <!-- Content Main -->
                <div class="container-fluid">
                    {{ $slot }}
                </div>
                <!-- End Content Main -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white" id="footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; DANILORE 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Listo para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">¿Deseas salir y cerrar sesión?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="btn btn-primary" href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{ __('Salir') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript and dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    <!-- DATATABLES JS -->
    <script type="text/javascript" src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>
    <!-- Custom JS -->
    <script type="text/javascript" src="{{ asset('js/producto.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/persona.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/usuario.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/roles.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/permiso.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/unidad_medida.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/proveedor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/unidad.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/dependencia.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/categoria_producto.js') }}"></script>

    <!-- Initialize dropdown manually -->
    <script>
        $(document).ready(function() {
            $('#userDropdown').dropdown();

            /* Recupera los id en una constante */
            const modeToggle = document.getElementById('modeToggle');
            const body = document.body;
            const navbar = document.getElementById('navbar');
            const content = document.getElementById('content');
            const accordionSidebar = document.getElementById('accordionSidebar');
            const textPrincipalMenuItems = document.querySelectorAll('.textPrincipalMenu');
            const footer = document.getElementById('footer');
            const nameUser = document.getElementById('nameUser');
            const btnCrearItems = document.querySelectorAll('.btnCrear');
            const btnDescargar = document.getElementById('btnDescargar');
            const btnVerItems = document.querySelectorAll('.btnVer');
            const btnEditarItems = document.querySelectorAll('.btnEditar');
            const btnEliminarItems = document.querySelectorAll('.btnEliminar');
            const btnMenu3RayasItems = document.querySelectorAll('.btnMenu3Rayas');
            const cardIndexItems = document.querySelectorAll('.cardIndex');
            const cardFormItems = document.querySelectorAll('.cardForm');
            const estadoActivoItems = document.querySelectorAll('.estadoActivo');
            const estadoDesactivadoItems = document.querySelectorAll('.estadoDesactivado');

            function setDarkMode() {
                body.classList.add('dark-mode');
                body.classList.remove('light-mode');
                navbar.classList.add('bg-gray-700');
                navbar.classList.remove('bg-white');

                content.classList.add('bg-gray-500');
                content.classList.remove('bg-gray-100');

                accordionSidebar.classList.add('bg-gradient-dark');
                accordionSidebar.classList.remove('bg-gradient-primary');

                textPrincipalMenuItems.forEach(item => item.classList.add('text-gray-200'));
                textPrincipalMenuItems.forEach(item => item.classList.remove('text-gray-300'));

                footer.classList.add('bg-gray-500');
                footer.classList.remove('bg-gray-100');
                footer.classList.add('text-gray-200');
                footer.classList.remove('text-gray-700');

                nameUser.classList.add('text-gray-100');
                nameUser.classList.remove('text-gray-900');
                /*Botonessss */
                btnCrearItems.forEach(item => item.classList.add('btn-success-oscuro', 'text-white'));
                btnCrearItems.forEach(item => item.classList.remove('btn-success'));
                btnDescargar.classList.add('btn-primary-oscuro', 'text-white');
                btnDescargar.classList.remove('btn-primary');
                btnVerItems.forEach(item => item.classList.add('btn-primary-oscuro', 'text-white'));
                btnVerItems.forEach(item => item.classList.remove('btn-primary'));
                btnEditarItems.forEach(item => item.classList.add('btn-info-oscuro', 'text-white'));
                btnEditarItems.forEach(item => item.classList.remove('btn-info'));
                btnEliminarItems.forEach(item => item.classList.add('btn-danger-oscuro', 'text-white'));
                btnEliminarItems.forEach(item => item.classList.add('btn-danger'));
                btnMenu3RayasItems.forEach(item => item.classList.add('bg-gray-700', 'text-white'));

                cardIndexItems.forEach(item => item.classList.add('card-table-index-oscuro'));
                cardIndexItems.forEach(item => item.classList.remove('card-table-index'));
                cardFormItems.forEach(item => item.classList.add('card-oscuro'));
                cardFormItems.forEach(item => item.classList.remove('card'));

                estadoActivoItems.forEach(item => item.classList.add('estado-activo-oscuro'));
                estadoActivoItems.forEach(item => item.classList.remove('estado-activo'));
                estadoDesactivadoItems.forEach(item => item.classList.add('estado-desactivado-oscuro'));
                estadoDesactivadoItems.forEach(item => item.classList.remove('estado-desactivado'));
                localStorage.setItem('mode', 'dark');
            }

            function setLightMode() {
                body.classList.add('light-mode');
                body.classList.remove('dark-mode');

                navbar.classList.add('bg-white');
                navbar.classList.remove('bg-gray-700');

                content.classList.add('bg-gray-100');
                content.classList.remove('bg-gray-500');
                
                accordionSidebar.classList.add('bg-gradient-primary');
                accordionSidebar.classList.remove('bg-gradient-dark');
                textPrincipalMenuItems.forEach(item => item.classList.add('text-gray-300'));
                textPrincipalMenuItems.forEach(item => item.classList.remove('text-gray-200'));
                
                footer.classList.add('bg-gray-100');
                footer.classList.remove('bg-gray-500');
                footer.classList.add('text-gray-700');
                footer.classList.remove('text-gray-200');

                nameUser.classList.add('text-gray-900');
                nameUser.classList.remove('text-gray-100');

                btnCrearItems.forEach(item => item.classList.add('btn-success'));
                btnCrearItems.forEach(item => item.classList.remove('btn-success-oscuro', 'text-white'));
                btnDescargar.classList.add('btn-primary');
                btnDescargar.classList.remove('btn-primary-oscuro', 'text-white');
                btnVerItems.forEach(item => item.classList.add('btn-primary'));
                btnVerItems.forEach(item => item.classList.remove('btn-primary-oscuro', 'text-white'));
                btnEditarItems.forEach(item => item.classList.add('btn-info'));
                btnEditarItems.forEach(item => item.classList.remove('btn-info-oscuro', 'text-white'));
                btnEliminarItems.forEach(item => item.classList.add('btn-danger'));
                btnEliminarItems.forEach(item => item.classList.remove('btn-danger-oscuro', 'text-white'));
                btnMenu3RayasItems.forEach(item => item.classList.remove('bg-gray-700', 'text-white'));

                cardIndexItems.forEach(item => item.classList.add('card-table-index'));
                cardIndexItems.forEach(item => item.classList.remove('card-table-index-oscuro'));
                cardFormItems.forEach(item => item.classList.add('card'));
                cardFormItems.forEach(item => item.classList.remove('card-oscuro'));

                estadoActivoItems.forEach(item => item.classList.add('estado-activo'));
                estadoActivoItems.forEach(item => item.classList.remove('estado-activo-oscuro'));
                estadoDesactivadoItems.forEach(item => item.classList.add('estado-desactivado'));
                estadoDesactivadoItems.forEach(item => item.classList.remove('estado-desactivado-oscuro'));
                localStorage.setItem('mode', 'light');
            }

            modeToggle.addEventListener('click', function() {
                if (body.classList.contains('light-mode')) {
                    setDarkMode();
                } else {
                    setLightMode();
                }
            });

            // Recuperar modo guardado al cargar la página
            const savedMode = localStorage.getItem('mode');
            if (savedMode === 'dark') {
                setDarkMode();
            } else {
                setLightMode();
            }
        });
    </script>
</body>

</html>