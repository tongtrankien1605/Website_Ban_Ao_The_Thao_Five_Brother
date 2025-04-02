<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutTimer
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Only apply to checkout page
        if ($request->route()->getName() !== 'checkout') {
            return $next($request);
        }

        // Set checkout start time if not exists
        if (!session()->has('checkout_start_time')) {
            session(['checkout_start_time' => now()->timestamp]);
        }

        // Check if timer has expired
        $startTime = session('checkout_start_time');
        $elapsed = now()->timestamp - $startTime;
        
        if ($elapsed > 60) {
            session()->forget('checkout_start_time');
            return redirect()->route('show.cart')
                ->withErrors('Phiên thanh toán đã hết hạn. Vui lòng thử lại.');
        }

        return $next($request);
    }
} 