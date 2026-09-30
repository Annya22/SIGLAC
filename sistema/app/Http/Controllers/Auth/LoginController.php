<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Ingrese su correo electrónico.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'password.required' => 'Ingrese su contraseña.',
        ]);

        if (!Auth::attempt($credenciales)) {
            return back()
                ->withErrors([
                    'email' => 'Las credenciales ingresadas no son correctas.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $usuario = Auth::user();

        if (!$usuario->activo) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Su cuenta se encuentra inactiva.',
                ])
                ->onlyInput('email');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}