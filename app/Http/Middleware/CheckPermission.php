<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::user()->can($permission)) {
            return $next($request);
        }

        // Redirigir a la vista personalizada
        return response()->view('404');
    }
}
