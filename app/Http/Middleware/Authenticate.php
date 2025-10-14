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

        if($request->is("admin") || $request->is("admin/*"))
        // Default to admin login form for other cases
            return route('admin.loginform');
        // Check if the request is coming from a student or admin based on URL prefix
        // else  if ($request->is('student/*' )|| $request->is('student')) {
        // }
        return route('student.loginform'); // Redirect to student login form
        
    }
}
