<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShareMenuPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $menuItems = [];

        if ($user) {
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();

            // Define the menu items based on permissions
            if (in_array('view_solicitudes', $permissions)) {
                $menuItems['solicitudes'] = true;
            }
            if (in_array('view_almacen', $permissions)) {
                $menuItems['almacen'] = true;
            }
            if (in_array('view_dependencias', $permissions)) {
                $menuItems['dependencia'] = true;
            }
            if (in_array('view_unidades', $permissions)) {
                $menuItems['unidad'] = true;
            }
            if (in_array('view_movimientos', $permissions)) {
                $menuItems['movimientos'] = true;
            }
            if (in_array('view_gestion_entradas', $permissions)) {
                $menuItems['gestion_entradas'] = true;
            }
            if (in_array('view_gestion_salidas', $permissions)) {
                $menuItems['gestion_salidas'] = true;
            }
            if (in_array('view_gestion_productos', $permissions)) {
                $menuItems['gestion_productos'] = true;
            }
            if (in_array('view_gestion_proveedores', $permissions)) {
                $menuItems['gestion_proveedores'] = true;
            }
            if (in_array('view_gestion_usuarios', $permissions)) {
                $menuItems['gestion_usuarios'] = true;
            }
        }

        view()->share('menuItems', $menuItems);

        return $next($request);
    }
}
