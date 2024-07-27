<?php

use Illuminate\Support\Facades\Route;

//controladores
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaProductoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DependenciaController;
use App\Http\Controllers\EntradaProductoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SalidaProductoController;
use App\Http\Controllers\SolicitudAlmacenController;
use App\Http\Controllers\SolicitudDependenciaController;
use App\Http\Controllers\SolicitudUnidadController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\UnidadMedidaController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();


Route::get('/index', function () {
    return view('index');
})->middleware(['auth', 'verified'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['share_menu_permissions'])->group(function () {
    // Define tus rutas aquí
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RolController::class);
    //Usuarios
    Route::resource('usuarios', UsuarioController::class);
    //Personas
    Route::resource('personas', PersonaController::class);
    //Entrada de productos
    Route::resource('entradas', EntradaProductoController::class);
    //Salida de productos
    Route::resource('salidas', SalidaProductoController::class);
    //Movimientos de productos
    Route::resource('movimientos', MovimientoController::class);
    Route::get('/movimientos/{id}/kardex-pdf', [MovimientoController::class, 'kardexPdf'])->name('movimientos.kardexPdf');

    //unidad subarea
    Route::resource('unidades', UnidadController::class);
    //Dependencias (Areas)
    Route::resource('dependencias', DependenciaController::class);
    //Proveedores
    Route::resource('proveedor', ProveedorController::class);
    //Ubicacion
    Route::resource('ubicacion', UbicacionController::class);
    /*Productos */
    Route::resource('productos', ProductoController::class);
    /*Categoria Productos */
    Route::resource('categoriaproductos', CategoriaProductoController::class);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*Permisos */
    Route::resource('permissions', PermissionController::class);


    //solicitudes de unidad a jefatura
    Route::resource('solicitud_unidad', SolicitudUnidadController::class);


    //Reportes en pdf
    Route::get('/entradas/{id}/export-pdf', [EntradaProductoController::class, 'exportSingleToPdf'])->name('entradas.exportSingleToPdf');
    Route::get('/entradas/{id}/export-pdf-formato', [EntradaProductoController::class, 'formatoPdf'])->name('entradas.formatoPdf');

    Route::get('/salidas/{id}/export-pdf', [SalidaProductoController::class, 'exportSingleToPdf'])->name('salidas.exportSingleToPdf');
    Route::get('/salidas/{id}/export-pdf-formato', [SalidaProductoController::class, 'formatoPdf'])->name('salidas.formatoPdf');

    /*Formatos (FUR) */
    Route::get('/solicitud_unidad/{id}/export-pdf', [SolicitudUnidadController::class, 'formatoPdf'])->name('solicitud_unidad.formatoPdf');
    Route::get('/solicitud_dependencia/{id}/export-pdf', [SolicitudDependenciaController::class, 'formatoPdf'])->name('solicitud_dependencia.formatoPdf');
    Route::get('/solicitud_almacen/{id}/export-pdf', [SolicitudAlmacenController::class, 'formatoPdf'])->name('solicitud_almacen.formatoPdf');
});

//Exportar en excel, word, pdf
Route::get('productosexport', [ProductoController::class, 'export'])->name('productos.export');
Route::get('ubicacionesexport', [UbicacionController::class, 'export'])->name('ubicacion.export');
Route::get('unidadmedidaexport', [UnidadMedidaController::class, 'export'])->name('unidadmedida.export');
Route::get('categoriaproductosexport', [CategoriaProductoController::class, 'export'])->name('categoriaproductos.export');
Route::get('proveedoresexport', [ProveedorController::class, 'export'])->name('proveedor.export');
Route::get('dependenciasexport', [DependenciaController::class, 'export'])->name('dependencias.export');
Route::get('permisosexport', [PermissionController::class, 'export'])->name('permissions.export');
Route::get('personasexport', [PersonaController::class, 'export'])->name('personas.export');
Route::get('rolesexport', [RolController::class, 'export'])->name('roles.export');
Route::get('unidadesexport', [UnidadController::class, 'export'])->name('unidades.export');
Route::get('usuariosexport', [UsuarioController::class, 'export'])->name('usuarios.export');
Route::get('movimientosexport', [MovimientoController::class, 'export'])->name('movimientos.export');

Route::get('entradasexportar', [EntradaProductoController::class, 'export'])->name('entradas.export');
Route::get('salidasexportar', [SalidaProductoController::class, 'export'])->name('salidas.export');

Route::get('solicitudunidadexportar', [SolicitudUnidadController::class, 'export'])->name('solicitud_unidad.export');


/*Unidad de Medida Productos */
Route::resource('unidadmedidas', UnidadMedidaController::class);
Route::get('/unidadmedida', [UnidadMedidaController::class, 'index'])->name('unidadmedida.index');
Route::post('/newunidadmedida', [UnidadMedidaController::class, 'store'])->name('unidadmedida.store');
Route::get('/editar-unidadmedida/{id}', [UnidadMedidaController::class, 'edit'])->name('editarUnidadmedida');
Route::put('/actualizar-unidadmedida/{id}', [UnidadMedidaController::class, 'update'])->name('unidadmedida.update');
Route::delete('/eliminar-unidadmedida/{id}', [UnidadMedidaController::class, 'destroy'])->name('unidadmedida.destroy');


//Movimiento
Route::get('movimientos/{id}', [MovimientoController::class, 'show'])->name('movimientos.showEntrada');
Route::get('movimientos/{id}', [MovimientoController::class, 'show'])->name('movimientos.showSalida');

//Soliitudes de jefatura a jefatura de almacen
Route::resource('solicitud_almacen', SolicitudAlmacenController::class);
Route::get('solicitud_almacen', [SolicitudAlmacenController::class, 'index'])->name('solicitud_almacen.index');
Route::get('almacen_registro', [SolicitudAlmacenController::class, 'completo'])->name('solicitud_almacen.completo');
Route::get('solicitud_almacen/{id}', [SolicitudAlmacenController::class, 'show'])->name('solicitud_almacen.show');
Route::get('almacen_registro/{id}', [SolicitudAlmacenController::class, 'verCompleto'])->name('almacen_registro.show');
Route::get('solicitud_almacen/approve/{id}', [SolicitudAlmacenController::class, 'approve'])->name('solicitud_almacen.approve');
Route::get('solicitud_almacen/reject/{id}', [SolicitudAlmacenController::class, 'reject'])->name('solicitud_almacen.reject');
Route::get('/solicitud_almacen/pendientes', [SolicitudAlmacenController::class, 'getPendientes'])->name('solicitud.pendientes');
Route::get('almacen_registro/{id}/pdf', [SolicitudAlmacenController::class, 'generarPDF'])->name('almacen_registro.pdf');




// Rutas para SolicitudDependenciaController
//Ver solicitudes pedidas desde unidad a jefatura
Route::resource('solicitud_dependencia', SolicitudDependenciaController::class);
Route::get('solicitud_dependencia', [SolicitudDependenciaController::class, 'index'])->name('solicitud_dependencia.index');
Route::get('solicitud_dependencia/{id}', [SolicitudDependenciaController::class, 'show'])->name('solicitud_dependencia.show');
Route::get('solicitud_dependencia_registro/{id}', [SolicitudDependenciaController::class, 'verRegistro'])->name('solicitud_dependencia_registro.show');
Route::get('solicitud_dependencia/approve/{id}', [SolicitudDependenciaController::class, 'approve'])->name('solicitud_dependencia.approve');
Route::get('solicitud_dependencia/reject/{id}', [SolicitudDependenciaController::class, 'reject'])->name('solicitud_dependencia.reject');
Route::get('solicitud_dependencia_registro', [SolicitudDependenciaController::class, 'registro'])->name('solicitud_dependencia.registro');
Route::get('solicitud_dependencia_almacen', [SolicitudDependenciaController::class, 'registroAlmacen'])->name('solicitud_dependencia_almacen.index');
Route::get('solicitud_dependencia_almacen/{id}', [SolicitudDependenciaController::class, 'showRegistroAlmacen'])->name('solicitud_dependencia_almacen.show');











require __DIR__ . '/auth.php';
