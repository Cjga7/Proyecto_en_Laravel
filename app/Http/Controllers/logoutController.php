<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class logoutController extends Controller
{
    public function logout(Request $request)
    {
        // Cerrar sesión y limpiar la sesión
        Session::flush();
        Auth::logout();

        // Evitar que se acceda a páginas usando el botón 'Atrás'
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigir al login con cabeceras para evitar caché
        return redirect()->route('login')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',  // Evitar caché
            'Pragma' => 'no-cache',  // HTTP 1.0
            'Expires' => '0',  // Fecha de expiración en el pasado
        ]);
    }
}

