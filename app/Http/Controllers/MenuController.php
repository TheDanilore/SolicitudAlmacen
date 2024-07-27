<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            // Definir los permisos necesarios para cada ítem del menú
            $menuPermissions = [
                'solicitud_producto' => ['solicitud_producto_index'],
                'almacen' => ['solicitud_almacen_index'],
                'dependencia' => ['solicitud_dependencia_index'],
                'unidad' => ['solicitud_unidad_index'],
                'movimientos' => ['movimientos_index'],
                'gestion_entradas' => ['entradas_index'],
                'gestion_salidas' => ['salidas_index'],
                'gestion_productos' => ['productos_index'],
                'gestion_proveedores' => ['proveedores_index'],
                'gestion_usuarios' => ['usuarios_index'],
            ];

            // Filtrar los permisos que tiene el usuario
            $menuItems = [];
            foreach ($menuPermissions as $key => $permissions) {
                foreach ($permissions as $permission) {
                    if ($user->can($permission)) {
                        $menuItems[$key] = true;
                        break;
                    }
                }
            }

            // Compartir los items del menú con todas las vistas
            View::share('menuItems', $menuItems);

            return $next($request);
        });
    }
}
