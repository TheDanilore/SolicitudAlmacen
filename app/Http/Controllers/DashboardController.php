<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\SolicitudDependencia;
use App\Models\SolicitudUnidad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{



    public function index()
    {
        try {
            $cantidadUsuarios = User::count();
            $cantidadRoles = Role::count();
            $cantidadPermisos = Permission::count();

            // Solicitudes pendientes de revisar en el almacén
            $cantidadPendiente = SolicitudDependencia::where('estado', 'pendiente')->count();

            // Solicitudes pendientes por revisar por el jefe de dependencia
            $dependencia_id = Auth::user()->unidad->dependencia_id;
            $cantidadPendienteUnidad = SolicitudUnidad::where('dependencia_id', $dependencia_id)->where('estado', 'pendiente')->count();

            // Solicitudes con el almacén
            $cantidadPendienteUnidadAlmacen = SolicitudDependencia::where('dependencia_id', $dependencia_id)->where('estado', 'pendiente')->count();

            // Solicitudes del empleado de unidad
            $userId = Auth::id();
            $cantidadPendienteSolicitud = SolicitudUnidad::whereHas('audit', function ($query) use ($userId) {
                $query->where('user_id_created', $userId);
            })->where('estado', 'pendiente')->count();

            return view('dashboard', compact(
                'cantidadUsuarios',
                'cantidadRoles',
                'cantidadPermisos',
                'cantidadPendiente',
                'cantidadPendienteUnidad',
                'cantidadPendienteSolicitud',
                'cantidadPendienteUnidadAlmacen'
            ));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Ocurrió un error al cargar los datos: ' . $e->getMessage());
        }
    }
}
