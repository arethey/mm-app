<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole {
    public function handle(Request $request, Closure $next, ...$roles): Response {

        $user_role = Auth::user()->user_role_id == 1 ? 'admin' : 'user';

        if (!Auth::check() || !in_array($user_role, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
