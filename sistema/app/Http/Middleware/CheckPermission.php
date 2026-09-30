<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->activo) {
            abort(403, 'Su cuenta se encuentra inactiva.');
        }

        if (!$user->hasPermission($permission)) {
            abort(403, 'No tiene permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
