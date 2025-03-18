<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('client.layouts.partials.header-bottom', function ($view) {
            if (Auth::check()) {
                $cartItem = CartItem::where('id_user', Auth::id())->with('skuses')->get();

                $total = 0;
                $quantity = collect($cartItem)->sum('quantity');

                foreach ($cartItem as $cart) {
                    $total += $cart->price * $cart->quantity;
                    // $quantity += $cart->quantity;
                }

                $view->with([
                    'cartItem' => $cartItem,
                    'total' => $total,
                    'quantity' => $quantity
                ]);
            } else {
                $view->with([
                    'total' => 0,
                    'quantity' => 0
                ]);
            }
        });
    }
}
