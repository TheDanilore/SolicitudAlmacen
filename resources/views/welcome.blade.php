<!DOCTYPE html>
<html lang="en">

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

<body>
    <div id="content">
        <button id="mode" class="btn btn-primary ml-auto">Modo</button>
dawdawwwwwwwwwwwwwwwwwwwwwwwwwww
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mode = document.getElementById('mode');
            const content = document.getElementById('content');
            mode.addEventListener('click', function() {
                content.classList.add('bg-gray-500');
                content.classList.remove('bg-gray-100');
            });
        });
    </script>
</body>

</html>