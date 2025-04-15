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
        if (! $request->expectsJson()) {
            // Kiểm tra nếu URL bắt đầu bằng '/admin' thì chuyển về admin.login
            if (str_starts_with($request->path(), 'admin')) {
                return route('admin.login');
            }
            return route('login');
        }
        
        return null;
    }
}
