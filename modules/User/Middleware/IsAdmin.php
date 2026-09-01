<?php

namespace Mod\User\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// Sample only!
class IsAdmin
{
    /**
     * Obsługa żądania.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()) {
            $user = Auth::user();
            
            if (!$user || !$user->is_admin) {
                abort(403, 'Brak dostępu do panelu administratora.');
            }
        }

        return $next($request);
    }
}
