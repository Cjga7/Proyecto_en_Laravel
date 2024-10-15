<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Comprobar si la respuesta no es un archivo
        if (!$response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            // Añadir cabeceras para evitar cacheo en el navegador
            return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                            ->header('Pragma', 'no-cache')
                            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
        }

        return $response; // Retorna la respuesta sin modificar si es un archivo
    }
}


