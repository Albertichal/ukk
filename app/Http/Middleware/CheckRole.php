<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $allowed = explode(',', $roles);

        if (!in_array(auth()->user()->role, $allowed)) {
            abort(403);
        }

        return $next($request);
    }
}
