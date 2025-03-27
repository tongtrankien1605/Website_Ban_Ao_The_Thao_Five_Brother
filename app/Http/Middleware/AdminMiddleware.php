<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // check Auth, nếu user role không phải admin thì không thể truy cập

        if (!Auth::check() || Auth::user()->role == 1) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này!');
        }

        return $next($request);
    }
}
