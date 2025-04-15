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
        // Nếu chưa đăng nhập hoặc là user thường (role = 1)
        if (!Auth::check() || Auth::user()->role == 1) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }
            return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập trang này!');
        }

        return $next($request);
    }
}
