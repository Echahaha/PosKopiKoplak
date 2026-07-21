<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        // Cek apakah role user sesuai (owner/barista)
        if (Auth::user()->role !== $role) {
            return redirect('/dashboard')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}
