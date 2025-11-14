<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Checkrole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        // Convert $roles to array if it's a string
        $rolesArray = is_array($roles) ? $roles : explode(',', $roles);
        
        // Trim whitespace from each role
        $rolesArray = array_map('trim', $rolesArray);
        
        if(in_array($request->user()->role, $rolesArray)){
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
    }
}