<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }
        
        // Check if request is to admin area
        if (str_starts_with($request->path(), 'admin')) {
            return route('admin.login');
        }
        
        // If you also have a client login, you could add:
        // return route('client.login');
        
        // For now, just redirect to admin login
        return route('admin.login');
    }
}