<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use File;
use Illuminate\Support\Facades\File as FacadesFile;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    
    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email,' . Auth::user()->id],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);
    
        $user = $request->user();
    
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . Str::random(10) . '.' . $avatar->getClientOriginalExtension();
            
            // Guardamos el avatar en storage/app/public/avatars
            if ($avatar->storeAs('avatars', $filename, 'public')) {
                // Si se guarda exitosamente, actualiza la ruta en la base de datos
                
                // Elimina el avatar antiguo si no es el predeterminado
                if ($user->avatar != 'img/uploads/user-defecto.jpg') {
                    Storage::disk('public')->delete(Str::after($user->avatar, 'storage/'));
                }
                
                $user->avatar = 'storage/avatars/' . $filename;
            } else {
                return redirect()->back()->withErrors(['avatar' => 'No se pudo guardar la imagen. Por favor, inténtelo de nuevo.']);
            }
        }
    
        $user->fill($request->safe()->only(['name', 'email']));
    
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        $user->save();
    
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
