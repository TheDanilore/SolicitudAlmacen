<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        // Verificar las credenciales sin iniciar sesión
        if (!Auth::validate($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Obtener el usuario por sus credenciales
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        // Verificar si el usuario está inactivo
        if ($user->estado_usuario_id == 2) {
            throw ValidationException::withMessages([
                'email' => 'El usuario está inactivo.',
            ]);
        }

        // Verificar si alguno de los roles del usuario está inactivo
        $rolesInactivos = $user->roles()->where('estado_rol_id', 2)->count();
        if ($rolesInactivos > 0) {
            throw ValidationException::withMessages([
                'email' => 'El rol del usuario está inactivo.',
            ]);
        }

        // Intentar iniciar sesión
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
