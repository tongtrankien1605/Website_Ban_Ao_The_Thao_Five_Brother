<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('client.index');
})->name('index');
Route::get('/single-product', function () {
    return view('client.single-product');
})->name('single-product');
Route::get('/single-blog', function () {
    return view('client.single-blog');
})->name('single-blog');
Route::get('/shop', function () {
    return view('client.shop');
})->name('shop');
Route::get('/wishlist', function () {
    return view('client.wishlist');
})->name('wishlist');
Route::get('/my-account', function () {
    return view('client.my-account');
})->name('my-account');
Route::get('/login-register', function () {
    return view('client.login-register');
})->name('login-register');
Route::get('/contact', function () {
    return view('client.contact');
})->name('contact');
Route::get('/checkout', function () {
    return view('client.checkout');
})->name('checkout');
Route::get('/cart', function () {
    return view('client.cart');
})->name('cart');
Route::get('/blog', function () {
    return view('client.blog');
})->name('blog');
Route::get('/404', function () {
    return view('client.404');
})->name('404');


