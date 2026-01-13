<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has any of the allowed roles
        foreach ($roles as $role) {
            if ($user->role->name === $role) {
                return $next($request);
            }
        }

        // If user doesn't have required role, redirect based on their role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access');
        } elseif ($user->isKasir()) {
            return redirect()->route('cashier.pos.index')->with('error', 'Unauthorized access');
        } else {
            return redirect()->route('customer.home')->with('error', 'Unauthorized access');
        }
    }
}