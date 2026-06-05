<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de autenticación del panel de administración.
 *
 * Gestiona el login y logout de los usuarios administradores
 * utilizando el sistema de autenticación nativo de Laravel.
 */
class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     *
     * Si el usuario ya está autenticado, lo redirige al dashboard.
     */
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el intento de inicio de sesión.
     *
     * Valida las credenciales y redirige al dashboard en caso de éxito,
     * o devuelve al formulario con error en caso de fallo.
     */
    public function login(Request $request)
    {
        // Validación de campos requeridos
        $credenciales = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Ingrese un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // Intentar autenticación
        if (Auth::attempt($credenciales, $request->boolean('recordar'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                             ->with('exito', '¡Bienvenido al panel de administración!');
        }

        // Credenciales inválidas
        return back()
            ->withErrors(['email' => 'Las credenciales ingresadas son incorrectas.'])
            ->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario autenticado.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
                         ->with('info', 'Ha cerrado sesión correctamente.');
    }
}
